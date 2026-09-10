<?php
// app/Http/Controllers/Admin/MovieController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('type', 'movie')->latest()->paginate(20);
        return view('admin.movies.index', compact('movies'));
    }
    
    public function create()
    {
        return view('admin.movies.create');
    }
    
    // public function store(Request $request)
    // {

   
    //     // Validate the request
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'genre' => 'required|string',
    //         'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
    //         'duration' => 'required|string',
    //         'language' => 'required|string',
    //         'rating' => 'required|string',
    //         'poster_path' => 'nullable|url',
    //         'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    //         'video_file' => 'nullable|file|mimes:mp4,mkv,avi|max:10240',
    //         'file_path' => 'nullable|string',
    //         'file_size' => 'nullable|string',
    //     ]);
        
    //     // Handle poster
    //     $posterPath = $request->poster_path;
    //     if ($request->hasFile('poster_file')) {
    //         $posterFile = $request->file('poster_file');
    //         $posterName = time() . '_poster.' . $posterFile->getClientOriginalExtension();
    //         $posterFile->storeAs('public/posters', $posterName);
    //         $posterPath = '/storage/posters/' . $posterName;
    //     }
        
    //     // Handle video file
    //     $filePath = $request->file_path;
    //     if ($request->hasFile('video_file')) {
    //         $videoFile = $request->file('video_file');
    //         $videoName = time() . '_movie_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
            
    //         // Store the file
    //         // $filePath = $videoFile->storeAs('private/movies', $videoName, 'private');
    //         $filePath = $videoFile->storeAs('movies', $videoName, 'private');
            
    //         // Get file size
    //         $fileSize = $videoFile->getSize();
    //         $sizeInGB = round($fileSize / (1024 * 1024 * 1024), 2);
    //         $request->merge(['file_size' => $sizeInGB . ' GB']);
    //     }
        
    //     // Create movie record
    //     $movie = Movie::create([
    //         'title' => $request->title,
    //         'description' => $request->description,
    //         'genre' => $request->genre,
    //         'release_year' => $request->release_year,
    //         'duration' => $request->duration,
    //         'language' => $request->language,
    //         'rating' => $request->rating,
    //         'poster_path' => $posterPath,
    //         'file_path' => $filePath,
    //         'file_size' => $request->file_size,
    //         'views' => 0,
    //         'download_count' => 0,
    //         'is_active' => true,
    //         'type' => 'movie'
    //     ]);
        
    //     return redirect()->route('admin.movies.index')
    //         ->with('success', 'Movie "' . $movie->title . '" added successfully!');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration' => 'required|string',
            'language' => 'required|string',
            'rating' => 'required|string',
            'poster_path' => 'nullable|url',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video_file' => 'nullable|file|mimes:mp4,mkv,avi|max:1024000',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|string',
            'trailer_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('_token', 'poster_file', 'video_file');

        /*
        |--------------------------------------------------------------------------
        | POSTER HANDLING
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('poster_file')) {
            $posterFile = $request->file('poster_file');
            $posterName = time() . '_poster.' . $posterFile->getClientOriginalExtension();

            $path = $posterFile->storeAs('posters', $posterName, 'public');

            $data['poster_path'] = Storage::url($path);
        } else {
            $data['poster_path'] = $request->poster_path;
        }

        /*
        |--------------------------------------------------------------------------
        | VIDEO HANDLING
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $videoName = time() . '_movie_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();

            $path = $videoFile->storeAs('movies', $videoName, 'private');

            $data['file_path'] = $path;

            $sizeInGB = round($videoFile->getSize() / (1024 * 1024 * 1024), 2);
            $data['file_size'] = $sizeInGB . ' GB';
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT VALUES
        |--------------------------------------------------------------------------
        */
        $data['views'] = 0;
        $data['download_count'] = 0;
        $data['is_active'] = $request->has('is_active') ? $request->is_active : true;
        $data['type'] = 'movie';

        /*
        |--------------------------------------------------------------------------
        | CREATE MOVIE
        |--------------------------------------------------------------------------
        */
        $movie = Movie::create($data);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie "' . $movie->title . '" added successfully!');
    }
    
    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }
    
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'release_year' => 'required|integer',
            'duration' => 'required|string',
            'language' => 'required|string',
            'rating' => 'required|string',
            'poster_path' => 'nullable|url',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video_file' => 'nullable|file|mimes:mp4,mkv,avi|max:1024000',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|string',
            'trailer_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);
    
        $updateData = $request->except('_token', '_method', 'poster_file', 'video_file');
    
        /*
        |--------------------------------------------------------------------------
        | POSTER HANDLING
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('poster_file')) {
            $posterFile = $request->file('poster_file');
            $posterName = time() . '_poster.' . $posterFile->getClientOriginalExtension();
    
            $path = $posterFile->storeAs('posters', $posterName, 'public');
    
            $updateData['poster_path'] = Storage::url($path);
        } else {
            $updateData['poster_path'] = $request->poster_path;
        }
    
        /*
        |--------------------------------------------------------------------------
        | VIDEO HANDLING 
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $videoName = time() . '_movie_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
    
            $path = $videoFile->storeAs('movies', $videoName, 'private');
    
            $updateData['file_path'] = $path;
    
            $sizeInGB = round($videoFile->getSize() / (1024 * 1024 * 1024), 2);
            $updateData['file_size'] = $sizeInGB . ' GB';
        }
    
        /*
        |--------------------------------------------------------------------------
        | UPDATE MOVIE
        |--------------------------------------------------------------------------
        */
        $movie->update($updateData);
    
        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie updated successfully!');
    }
    
    public function destroy(Movie $movie)
    {
        // Delete associated files
        if ($movie->file_path && Storage::disk('private')->exists($movie->file_path)) {
            Storage::disk('private')->delete($movie->file_path);
        }
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully!');
    }
    
    public function toggleStatus(Movie $movie)
    {
        $movie->is_active = !$movie->is_active;
        $movie->save();
        
        return redirect()->back()->with('success', 'Movie status updated!');
    }
}