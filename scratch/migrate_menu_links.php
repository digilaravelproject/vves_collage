<?php

use App\Models\Page;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

DB::transaction(function() {
    $pages = Page::whereNotNull('menu_id')->get();
    foreach ($pages as $page) {
        $menu = Menu::find($page->menu_id);
        if ($menu) {
            $menu->update(['page_id' => $page->id]);
            echo "Linked Menu #{$menu->id} ({$menu->title}) to Page #{$page->id} ({$page->title})\n";
        }
    }
});

echo "Data migration complete.\n";
