<?php

namespace App\Http\Services;

use App\Models\Playlist;

class PlaylistService
{
    public function store(array $data)
    {
        return Playlist::updateOrCreate(
            ['playlist_id' => $data['playlist_id']], // prevent duplicates based on playlist_id
            $data
        );
    }

    public function getPlaylistsByCategories(array $categories)
    {
        return Playlist::whereIn('category', $categories)->paginate(8);
    }

    public function getPlaylistsCountByCategories(array $categories)
    {
        return Playlist::whereIn('category', $categories)
            ->select('category')
            ->selectRaw('count(*) as playlists_count')
            ->groupBy('category')
            ->get();
    }
}
