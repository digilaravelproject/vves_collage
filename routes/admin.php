<?php

use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\Admin\HomepageSetupController;
use App\Http\Controllers\Admin\InstagramFeedController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadAdminController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SiteManagementController;
use App\Http\Controllers\Admin\SmtpSettingController;
use App\Http\Controllers\Admin\TrustSectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventItemController;
use App\Http\Controllers\GalleryCategoryController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WhyChooseUsController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::get('admin', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(['can:view roles'])->group(function () {
        Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::post('/roles-permissions/assign', [RolePermissionController::class, 'assign'])->name('roles-permissions.assign');
        Route::post('/roles-permissions/create-role', [RolePermissionController::class, 'createRole'])->name('roles-permissions.create-role');
        Route::post('/roles-permissions/create-permission', [RolePermissionController::class, 'createPermission'])->name('roles-permissions.create-permission');
    });
    
    // Workflow Management
    Route::prefix('workflow')->name('workflow.')->group(function () {
        Route::get('/', [WorkflowController::class, 'index'])->name('index');
        Route::get('/{pendingAction}', [WorkflowController::class, 'show'])->name('show');
        
        // Strictly for Approvers
        Route::middleware(['role:Approver|Super Admin', 'can:workflow.approve'])->group(function () {
            Route::post('/{pendingAction}/approve', [WorkflowController::class, 'approve'])->name('approve');
            Route::post('/{pendingAction}/reject', [WorkflowController::class, 'reject'])->name('reject');
        });
    });

    Route::resource('menus', MenuController::class);
    Route::post('/menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggle-status');
    Route::get('/website-settings', [WebsiteSettingController::class, 'index'])->name('website-settings.index');
    Route::post('/website-settings', [WebsiteSettingController::class, 'update'])->name('website-settings.update');
    Route::post('website-settings/delete-media', [WebsiteSettingController::class, 'deleteBannerMedia'])->name('website-settings.delete-media');

    // Homepage Setup
    Route::get('/homepage-setup', [HomepageSetupController::class, 'index'])->name('homepage.index');
    Route::post('/homepage-setup/save', [HomepageSetupController::class, 'save'])->name('homepage.save');
    Route::post('/homepage-setup/upload', [HomepageSetupController::class, 'upload'])->name('homepage.upload');

    Route::middleware(['workflow'])->group(function () {
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except(['show'])->names('banners');
        Route::post('banners/{banner}/toggle-status', [\App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
        Route::post('banners/reorder', [\App\Http\Controllers\Admin\BannerController::class, 'reorder'])->name('banners.reorder');
    });

    Route::resource('trust', TrustSectionController::class)
        ->except(['show', 'destroy']) // keep index, create, store, edit, update
        ->parameters([
            'trust' => 'trustSection',
        ])
        ->names('trust');

    Route::delete('trust/image/{image}', [TrustSectionController::class, 'destroyImage'])
        ->name('trust.image.destroy');

    Route::delete('trust/pdf/{trustSection}', [TrustSectionController::class, 'removePdf'])
        ->name('trust.pdf.remove');
    Route::prefix('pagebuilder')->name('pagebuilder.')->middleware(['workflow'])->group(function () {
        // CRUD pages (index, create, edit, delete)
        Route::get('/', [PageBuilderController::class, 'index'])->name('index');
        Route::get('/create', [PageBuilderController::class, 'create'])->name('create');
        Route::post('/store', [PageBuilderController::class, 'store'])->name('store');
        Route::get('/edit/{page}', [PageBuilderController::class, 'edit'])->name('edit');
        Route::match(['post', 'put'], '/update/{page}', [PageBuilderController::class, 'update'])->name('update');
        Route::delete('/delete/{page}', [PageBuilderController::class, 'destroy'])->name('delete');
        Route::post('{page}/toggle-status', [PageBuilderController::class, 'toggleStatus'])
            ->name('toggleStatus');
        Route::post('{page}/duplicate', [PageBuilderController::class, 'duplicate'])
            ->name('duplicate');

        // AJAX media listing (for media library)
        Route::get('/media-library', [PageBuilderController::class, 'getMedia'])->name('media');

        // 🧱 Page Builder (Elementor-style)
        Route::get('/builder/{page}', [PageBuilderController::class, 'builder'])
            ->name('builder')
            ->where('page', '^(?!media$).*');
        Route::get('/preview/{page}', [PageBuilderController::class, 'preview'])->name('preview');

        // Save builder data (HTML, CSS, JSON, etc.)
        Route::post('/builder/save/{page}', [PageBuilderController::class, 'saveBuilder'])->name('builder.save');

        // AJAX media uploads (for images, videos, etc.)
        Route::post('/builder/upload/{page}', [PageBuilderController::class, 'uploadMedia'])->name('builder.upload');
    });

    // Content Modules with Workflow
    Route::middleware(['workflow', 'can:institution.view'])->group(function () {
        Route::resource('announcements', AnnouncementController::class)->except(['show'])->names('announcements');
        Route::resource('event-items', EventItemController::class)->except(['show'])->names('event-items');
        Route::resource('academic-calendar', AcademicCalendarController::class)->except(['show'])->names('academic-calendar');
        Route::resource('gallery-images', GalleryImageController::class)->except(['show'])->names('gallery-images');
        Route::resource('testimonials', TestimonialController::class)->except(['show'])->names('testimonials');
        Route::resource('institutions', \App\Http\Controllers\Admin\InstitutionController::class)->except(['show'])->names('institutions');
        Route::prefix('institutions/{institution}')->name('institutions.')->controller(\App\Http\Controllers\Admin\InstitutionController::class)->group(function () {
            Route::post('toggle-status', 'toggleStatus')->name('toggle-status');
            Route::post('save-result', 'saveResult')->name('save-result');
            Route::post('save-principal', 'savePrincipal')->name('save-principal');
            Route::post('save-pta', 'savePtaMember')->name('save-pta');
            Route::post('save-award', 'saveAward')->name('save-award');
            Route::post('save-staff', 'saveStaff')->name('save-staff');
            Route::post('upload-gallery', 'uploadGallery')->name('upload-gallery');
            Route::delete('remove-image/{image_type}', 'removeImage')->name('remove-image');
            Route::delete('delete-sub-item/{item_type}/{item_id}', 'deleteSubItem')->name('delete-sub-item');
        });
        Route::resource('popups', PopupController::class)->except(['show'])->names('popups');
        Route::post('popups/{popup}/toggle-status', [PopupController::class, 'toggleStatus'])->name('popups.toggle-status');
    });

    Route::middleware(['can:view gallery'])->group(function () {
        Route::resource('instagram-feeds', InstagramFeedController::class)->except(['show', 'create', 'edit'])->names('instagram-feeds');
        Route::post('instagram-feeds/{instagramFeed}/toggle-status', [InstagramFeedController::class, 'toggleStatus'])->name('instagram-feeds.toggle-status');
        Route::resource('event-categories', EventCategoryController::class)->except(['show'])->names('event-categories');
        Route::resource('gallery-categories', GalleryCategoryController::class)->except(['show'])->names('gallery-categories');
        Route::resource('why-choose-us', whyChooseUsController::class)->except(['show'])->names('why-choose-us');

        // Notifications
        Route::get('notifications/list-active-featured', [NotificationController::class, 'listActiveFeatured'])
            ->name('notifications.list-active-featured');
        Route::resource('notifications', NotificationController::class)->except(['show'])->names('notifications');
        Route::post('notifications/{notification}/toggle-status', [NotificationController::class, 'toggleStatus'])->name('notifications.toggle-status');
        Route::post('notifications/{notification}/toggle-featured', [NotificationController::class, 'toggleFeatured'])->name('notifications.toggle-featured');
        Route::post('notifications/{notification}/toggle-feature-on-top', [NotificationController::class, 'toggleFeatureOnTop'])->name('notifications.toggle-feature-on-top');
    });

    // Media Managemnet
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'store'])->name('media.store');
    Route::post('/media/delete', [MediaController::class, 'destroy'])->name('media.destroy');

    // --- User Management Routes ---
    Route::middleware(['can:view users'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{user}/resend-credentials', [UserController::class, 'resendCredentials'])->name('users.resend-credentials');
    });
    // Aap delete ke liye bhi add kar sakte hain:
    // Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Cache & Core Management
    Route::middleware(['can:manage settings'])->group(function () {
        Route::prefix('site-management')->name('site.')->controller(SiteManagementController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/clear-all-cache', 'clearAllCache')->name('cache.clear-all');
            Route::post('/re-optimize', 'reOptimizeApp')->name('cache.re-optimize');
            Route::post('/toggle-maintenance', 'toggleMaintenance')->name('toggle-maintenance');
            Route::post('/set-env', 'setAppEnv')->name('set-env');
            Route::post('/toggle-debug', 'toggleDebug')->name('toggle-debug');
        });
        Route::get('smtp-settings', [SmtpSettingController::class, 'index'])->name('smtp.index');
        Route::post('smtp-settings', [SmtpSettingController::class, 'store'])->name('smtp.store');
        Route::post('smtp-settings/test', [SmtpSettingController::class, 'testConnection'])->name('smtp.test');
    });

    Route::get('leads/admissions', [LeadAdminController::class, 'admissions'])->name('leads.admissions');
    Route::get('leads/enquiries', [LeadAdminController::class, 'enquiries'])->name('leads.enquiries');

    // Popups Management
    Route::resource('popups', PopupController::class)->except(['show'])->names('popups');
    Route::post('popups/{popup}/toggle-status', [PopupController::class, 'toggleStatus'])->name('popups.toggle-status');
});
