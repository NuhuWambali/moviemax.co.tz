<?php
// app/Http/Controllers/Admin/SeriesController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Series::latest()->paginate(20);
        return view('admin.series.index', compact('series'));
    }
    
    public function create()
    {
        return view('admin.series.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'release_year' => 'required|integer',
            'language' => 'required|string',
            'rating' => 'required|string',
            'poster_path' => 'nullable|url',
            'trailer_url' => 'nullable|url',
            'seasons_count' => 'required|integer',
        ]);
        
        Series::create($request->all());
        
        return redirect()->route('admin.series.index')->with('success', 'Series added successfully!');
    }
    
    public function edit(Series $series)
    {
        return view('admin.series.edit', compact('series'));
    }
    
    public function update(Request $request, Series $series)
    {
   
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'release_year' => 'required|integer',
            'language' => 'required|string',
            'rating' => 'required|string',
            'poster_path' => 'nullable|url',
            'trailer_url' => 'nullable|url',
            'seasons_count' => 'required|integer',
            'is_active' => 'boolean',
        ]);
        
        $series->update($request->all());
        
        return redirect()->route('admin.series.index')->with('success', 'Series updated successfully!');
    }
    
    public function destroy(Series $series)
    {
        // Delete all episodes first
        $episodes = Movie::where('series_id', $series->id)->get();
        foreach ($episodes as $episode) {
            // Delete video file if exists
            if ($episode->file_path && Storage::disk('private')->exists($episode->file_path)) {
                Storage::disk('private')->delete($episode->file_path);
            }
            $episode->delete();
        }
        $series->delete();
        
        return redirect()->route('admin.series.index')->with('success', 'Series and all episodes deleted successfully!');
    }
    
    public function toggleStatus(Series $series)
    {
        $series->is_active = !$series->is_active;
        $series->save();
        
        return redirect()->back()->with('success', 'Series status updated!');
    }
    
    // ============ EPISODE MANAGEMENT ============
    
    public function episodes(Series $series)
    {
        $episodes = Movie::where('series_id', $series->id)
                        ->where('type', 'episode')
                        ->orderBy('season_number')
                        ->orderBy('episode_number')
                        ->get();
        
        return view('admin.series.episodes', compact('series', 'episodes'));
    }
    
    public function createEpisode(Series $series)
    {
        return view('admin.series.create-episode', compact('series'));
    }
    
    public function storeEpisode(Request $request, Series $series)
    {

        $request->validate([
            'episode_title' => 'required|string|max:255',
            'season_number' => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'duration' => 'required|string',
            'video_file' => 'nullable|file|mimes:mp4,mkv,avi',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|string',
        ]);
        
        // Handle video upload
        $filePath = $request->file_path;
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $videoName = time() . '_episode_s' . $request->season_number . 'e' . $request->episode_number . '.' . $videoFile->getClientOriginalExtension();
            $filePath = $videoFile->storeAs('private/episodes', $videoName, 'private');
            
            $sizeInGB = round($videoFile->getSize() / (1024 * 1024 * 1024), 2);
            $request->merge(['file_size' => $sizeInGB . ' GB']);
        }
        
        // Check if episode with same season/episode already exists
        $exists = Movie::where('series_id', $series->id)
            ->where('season_number', $request->season_number)
            ->where('episode_number', $request->episode_number)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['error' => 'Episode S' . $request->season_number . 'E' . $request->episode_number . ' already exists!']);
        }
        
        Movie::create([
            'title' => $series->title . " - S{$request->season_number}E{$request->episode_number}",
            'episode_title' => $request->episode_title,
            'description' => $request->description ?? "Episode {$request->episode_number} of Season {$request->season_number}",
            'genre' => $series->genre,
            'release_year' => $series->release_year,
            'duration' => $request->duration,
            'language' => $series->language,
            'rating' => $series->rating,
            'poster_path' => $series->poster_path,
            'file_path' => $filePath,
            'file_size' => $request->file_size,
            'is_active' => true,
            'type' => 'episode',
            'series_id' => $series->id,
            'episode_number' => $request->episode_number,
            'season_number' => $request->season_number
        ]);
        
        return redirect()->route('admin.series.episodes', $series)->with('success', 'Episode added successfully!');
    }
    
    public function editEpisode(Series $series, $episodeId)
    {
        $episode = Movie::where('id', $episodeId)
            ->where('series_id', $series->id)
            ->where('type', 'episode')
            ->firstOrFail();
        
        return view('admin.series.edit-episode', compact('series', 'episode'));
    }
    
    public function updateEpisode(Request $request, Series $series, $episodeId)
    {
        $episode = Movie::where('id', $episodeId)
            ->where('series_id', $series->id)
            ->where('type', 'episode')
            ->firstOrFail();
        
        $request->validate([
            'episode_title' => 'required|string|max:255',
            'season_number' => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'duration' => 'required|string',
            'video_file' => 'nullable|file|mimes:mp4,mkv,avi',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|string',
        ]);
        
        // Check for duplicate season/episode (excluding current episode)
        $exists = Movie::where('series_id', $series->id)
            ->where('season_number', $request->season_number)
            ->where('episode_number', $request->episode_number)
            ->where('id', '!=', $episode->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['error' => 'Episode S' . $request->season_number . 'E' . $request->episode_number . ' already exists!']);
        }
        
        // Handle video upload
        $filePath = $request->file_path;
        if ($request->hasFile('video_file')) {
            // Delete old file
            if ($episode->file_path && Storage::disk('private')->exists($episode->file_path)) {
                Storage::disk('private')->delete($episode->file_path);
            }
            
            $videoFile = $request->file('video_file');
            $videoName = time() . '_episode_s' . $request->season_number . 'e' . $request->episode_number . '.' . $videoFile->getClientOriginalExtension();
            $filePath = $videoFile->storeAs('private/episodes', $videoName, 'private');
            
            $sizeInGB = round($videoFile->getSize() / (1024 * 1024 * 1024), 2);
            $request->merge(['file_size' => $sizeInGB . ' GB']);
        }
        
        $episode->update([
            'title' => $series->title . " - S{$request->season_number}E{$request->episode_number}",
            'episode_title' => $request->episode_title,
            'description' => $request->description,
            'duration' => $request->duration,
            'file_path' => $filePath,
            'file_size' => $request->file_size,
            'episode_number' => $request->episode_number,
            'season_number' => $request->season_number
        ]);
        
        return redirect()->route('admin.series.episodes', $series)->with('success', 'Episode updated successfully!');
    }
    
    public function destroyEpisode(Series $series, $episodeId)
    {
        $episode = Movie::where('id', $episodeId)
            ->where('series_id', $series->id)
            ->where('type', 'episode')
            ->firstOrFail();
        
        // Delete video file
        if ($episode->file_path && Storage::disk('private')->exists($episode->file_path)) {
            Storage::disk('private')->delete($episode->file_path);
        }
        
        $episode->delete();
        
        return redirect()->route('admin.series.episodes', $series)->with('success', 'Episode deleted successfully!');
    }
}