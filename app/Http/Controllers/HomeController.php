<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $videos = Cache::remember(
            'youtube_videos',
            now()->addHours(1),
            function () {

                $response = Http::get(
                    'https://www.googleapis.com/youtube/v3/search',
                    [
                        'part' => 'snippet',
                        'channelId' => env('YOUTUBE_CHANNEL_ID'),
                        'maxResults' => 10,
                        'order' => 'date',
                        'type' => 'video',
                        'key' => env('YOUTUBE_API_KEY'),
                    ]
                );

                return $response->json()['items'] ?? [];
            }
        );

        return view('dashboard', compact('videos'));
    }
}
