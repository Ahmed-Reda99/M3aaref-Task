<?php

namespace App\Http\Controllers;

use App\Http\Services\AiService;
use App\Http\Services\PlaylistService;
use App\Http\Services\YoutubeService;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    public function __construct(
        private AiService $aiService,
        private YoutubeService $youtubeService,
        private PlaylistService $playlistService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $request->categories ? explode(',', $request->categories) : [];
        $selectedCategory = $request->selected_category;

        $categoriesTabs = $this->playlistService->getPlaylistsCountByCategories($categories);

        if($selectedCategory) {
            $playlists = $this->playlistService->getPlaylistsByCategories([$selectedCategory]);
        } else {
            $playlists = $this->playlistService->getPlaylistsByCategories($categories);
        }

        return view('home', get_defined_vars());
    }

    public function fetch(Request $request)
    {
        // can be moved to a FormRequest for better validation and separation of concerns but no need here
        $request->validate([
            'categories' => 'required|array|min:1',
            'categories.*' => 'string|distinct', // adjust min and max length as needed
        ]);

        foreach ($request->categories as $category) {
            $titles = $this->aiService->generateTitles($category);

            if($titles[0] === 'illegal category') { // to handle the case when the category is illegal and the AI returns a specific message, you can adjust this condition based on the actual response you get from the AI service.
                continue;
            }

            foreach ($titles as $title) {
                $playlists = $this->youtubeService->searchPlaylists($title);
                foreach ($playlists as $playlist) {
                    $this->playlistService->store([
                        'playlist_id' => $playlist['id']['playlistId'],
                        'title' => $playlist['snippet']['title'],
                        'description' => $playlist['snippet']['description'],
                        'thumbnail' => $playlist['snippet']['thumbnails']['high']['url'],
                        'channel_name' => $playlist['snippet']['channelTitle'],
                        'category' => $category,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Playlists fetched and stored successfully!',
            'categories' => $request->categories
        ]);
    }
}
