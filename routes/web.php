<?php

use Illuminate\Support\Facades\Route;


// Static export home route
Route::get('/', function () {
    $entry = \Statamic\Facades\Entry::find('home');
    return $entry ? $entry->toResponse(request()) : abort(404);
});

// Static export capsule routes
Route::get('/capsules/{slug}', function ($slug) {
    $entry = \Statamic\Facades\Entry::whereCollection('capsules')
        ->where('slug', $slug)
        ->first();
    return $entry ? $entry->toResponse(request()) : abort(404);
});

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

