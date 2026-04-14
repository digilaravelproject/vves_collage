<?php

use App\Models\User;
use App\Models\Announcement;
use App\Models\PendingAction;
use App\Models\WorkflowLog;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

test('Maker-Checker: Traps staff creation and allows checker to approve', function () {
    // 1. Setup Role Players
    $staff = User::factory()->create();
    $staff->assignRole('Staff');

    $checker = User::factory()->create();
    $checker->assignRole('Checker');

    // 2. Staff (Maker) creates content
    $payload = [
        'title' => 'Automated Workflow Test',
        'content' => 'Detailed content trapped in shadow versioning.',
        'type' => 'student',
        'status' => 1
    ];

    $this->actingAs($staff)
        ->post(route('admin.announcements.store'), $payload)
        ->assertRedirect(route('admin.dashboard'))
        ->assertSessionHas('success', 'Your changes have been submitted for review.');

    // ASSERT: Not in production yet
    $this->assertDatabaseMissing('announcements', ['title' => 'Automated Workflow Test']);
    
    // ASSERT: Trapped in pending_actions
    $this->assertDatabaseHas('pending_actions', [
        'maker_id' => $staff->id,
        'status' => 'pending',
        'model_type' => Announcement::class
    ]);

    $pendingAction = PendingAction::where('status', 'pending')->first();

    // 3. Checker (Approver) verifies and publishes
    $this->actingAs($checker)
        ->post(route('admin.workflow.approve', $pendingAction))
        ->assertRedirect(route('admin.workflow.index'))
        ->assertSessionHas('success', 'Action approved and applied successfully.');

    // ASSERT: Now it exists in production
    $this->assertDatabaseHas('announcements', [
        'title' => 'Automated Workflow Test'
    ]);

    // ASSERT: Action marked as approved
    expect($pendingAction->fresh()->status)->toBe('approved');
    expect($pendingAction->fresh()->checker_id)->toBe($checker->id);
});

test('Maker-Checker: Allows rejection with feedback', function () {
    $staff = User::factory()->create();
    $staff->assignRole('Staff');

    $checker = User::factory()->create();
    $checker->assignRole('Checker');

    $payload = ['title' => 'Reject Me', 'content' => '...', 'type' => 'student', 'status' => 1];
    $this->actingAs($staff)->post(route('admin.announcements.store'), $payload);
    
    $pendingAction = PendingAction::first();

    // Checker Rejects
    $this->actingAs($checker)
        ->post(route('admin.workflow.reject', $pendingAction), [
            'notes' => 'Fix the typo'
        ])
        ->assertRedirect(route('admin.workflow.index'));

    expect($pendingAction->fresh()->status)->toBe('rejected');
    
    // Check audit log
    $this->assertDatabaseHas('workflow_logs', [
        'pending_action_id' => $pendingAction->id,
        'status' => 'rejected',
        'note' => 'Fix the typo'
    ]);
});

test('Maker-Checker: Prevents self-approval security loophole', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['Staff', 'Checker']); // Giving both for testing

    $payload = ['title' => 'Self Approval Loophole', 'content' => '...', 'type' => 'student', 'status' => 1];
    $this->actingAs($staff)->post(route('admin.announcements.store'), $payload);
    
    $pendingAction = PendingAction::first();

    // Attempt self-approval
    $this->actingAs($staff)
        ->post(route('admin.workflow.approve', $pendingAction))
        ->assertSessionHas('error', 'Security Policy: You cannot approve your own submitted actions.');

    expect($pendingAction->fresh()->status)->toBe('pending');
});

test('Maker-Checker: Admin bypasses the workflow automatically', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $payload = [
        'title' => 'Instant Production Content',
        'content' => 'No trap for admins',
        'type' => 'faculty',
        'status' => 1
    ];

    $this->actingAs($admin)
        ->post(route('admin.announcements.store'), $payload);

    // ASSERT: Exists instantly in production
    $this->assertDatabaseHas('announcements', ['title' => 'Instant Production Content']);
    
    // ASSERT: NO pending action created
    $this->assertDatabaseMissing('pending_actions', ['model_type' => Announcement::class]);
});
