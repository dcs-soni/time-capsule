<?php

use Illuminate\Support\Facades\Route;

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

Route::get('/feed.json', function () {
    $capsules = Statamic\Facades\Entry::query()
        ->where('collection', 'capsules')
        ->where('visibility', 'public')
        ->where('unlock_date', '<=', now())
        ->orderBy('unlock_date', 'desc')
        ->get()
        ->map(function ($entry) {
            return [
                'title' => $entry->get('title'),
                'message' => $entry->get('message'),
                'unlocked' => $entry->get('unlock_date')->toDateString(),
                'url' => $entry->url(),
            ];
        });

    return response()->json($capsules);
});

