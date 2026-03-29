<?php

namespace App\Http\Services;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Log;

class YoutubeService
{
    public function __construct(private Client $client)
    {
        $this->client->setDeveloperKey(env('YOUTUBE_API_KEY'));
    }

    public function searchPlaylists($title)
    {
        // work can be done in a background job for better performance and user experience but no need here
        try {
            $youtube = new YouTube($this->client);

            $queryParams = [
                'q' => $title,
                'maxResults' => 2,
                'type' => 'playlist'
            ];

            $response = $youtube->search->listSearch('snippet', $queryParams);

            return $response['items'];
        } catch (\Throwable $th) {
            Log::error('Error searching playlists: ' . $th->getMessage());
            throw $th;
        }
    }
}
