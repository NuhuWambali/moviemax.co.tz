<?php
// app/Http/Controllers/DownloadController.php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download a movie and increment download count
     */
    public function downloadMovie($id)
    {
        $movie = Movie::findOrFail($id);
        
        // Increment download count for movie
        $movie->increment('download_count');
        
        // External URL (works locally or hosted like Cloudinary)
        $videoUrl = $movie->video_url;
        if (!empty($videoUrl) && preg_match('#^https?://#i', $videoUrl)) {
            $downloadUrl = $this->forceDownloadUrl($videoUrl);
            if ($downloadUrl) {
                return redirect()->away($downloadUrl);
            }

            // Fallback: stream the remote file through the server as a download
            return response()->streamDownload(
                function () use ($videoUrl) {
                    $stream = @fopen($videoUrl, 'r');
                    if ($stream) {
                        while (!feof($stream)) {
                            echo fread($stream, 1024 * 1024);
                            flush();
                        }
                        fclose($stream);
                    }
                },
                $movie->title . '.mp4',
                ['Content-Type' => 'video/mp4']
            );
        }
        
        // Find the file
        $filePath = $this->findMovieFile($movie->file_path);
        
        if (!$filePath) {
            abort(404, 'Movie file not found');
        }
        
        return response()->download($filePath, $movie->title . '.mp4', [
            'Content-Type' => 'video/mp4',
        ]);
    }
    
    /**
     * Stream a movie and increment view count (optional)
     */
    public function streamMovie($id)
    {
        $movie = Movie::findOrFail($id);
        
        $videoUrl = $movie->video_url;
        if (!empty($videoUrl) && preg_match('#^https?://#i', $videoUrl)) {
            return redirect()->away($videoUrl);
        }
        
        $filePath = $this->findMovieFile($movie->file_path);
        
        if (!$filePath) {
            abort(404, 'Movie file not found');
        }
        
        return response()->file($filePath, [
            'Content-Type' => 'video/mp4',
            'Cache-Control' => 'no-cache, private',
        ]);
    }
    
    /**
     * Download a series (full season or specific episode)
     */
    public function downloadSeries($id, $season = null, $episode = null)
    {
        $series = Series::findOrFail($id);
        
        // Increment download count for series
        $series->increment('download_count');
        
        // If specific episode, download that episode
        if ($season && $episode) {
            $episodeFile = "series/{$series->id}/season_{$season}/episode_{$episode}.mp4";
            $filePath = $this->findSeriesFile($episodeFile);
            
            if (!$filePath) {
                abort(404, 'Episode not found');
            }
            
            return response()->download($filePath, $series->title . "_S{$season}E{$episode}.mp4");
        }
        
        // Otherwise, download the entire series (you might want to zip it)
        // For now, just download the first episode or return error
        $firstEpisode = $this->findFirstEpisode($series->id);
        if ($firstEpisode) {
            return response()->download($firstEpisode, $series->title . '.mp4');
        }
        
        abort(404, 'Series file not found');
    }
    
    /**
     * Turn an external video URL into one that forces a browser download.
     * Cloudinary supports the fl_attachment flag for this.
     */
    private function forceDownloadUrl(string $url): ?string
    {
        if (preg_match('#^https://res\.cloudinary\.com/([^/]+)/video/(upload|fetch)/#i', $url, $m)) {
            $base = 'https://res.cloudinary.com/' . $m[1] . '/video/' . $m[2] . '/fl_attachment/';
            $rest = substr($url, strlen('https://res.cloudinary.com/' . $m[1] . '/video/' . $m[2] . '/'));
            if ($rest !== '') {
                return $base . $rest;
            }
        }

        return null;
    }

    /**
     * Find movie file in various possible locations
     */
    private function findMovieFile($filePath)
    {
        $possiblePaths = [
            storage_path('app/public/movies/' . basename($filePath)),
            storage_path('app/public/' . $filePath),
            storage_path('app/private/movies/' . basename($filePath)),
            storage_path('app/' . $filePath),
            storage_path('app/private/' . $filePath),
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Find series file
     */
    private function findSeriesFile($filePath)
    {
        $possiblePaths = [
            storage_path('app/private/series/' . basename($filePath)),
            storage_path('app/' . $filePath),
            storage_path('app/private/' . $filePath),
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Find first episode of a series
     */
    private function findFirstEpisode($seriesId)
    {
        $basePath = storage_path('app/private/series/' . $seriesId);
        
        if (is_dir($basePath)) {
            foreach (scandir($basePath) as $season) {
                if ($season != '.' && $season != '..') {
                    $seasonPath = $basePath . '/' . $season;
                    if (is_dir($seasonPath)) {
                        foreach (scandir($seasonPath) as $episode) {
                            if (preg_match('/\.(mp4|mkv|avi)$/i', $episode)) {
                                return $seasonPath . '/' . $episode;
                            }
                        }
                    }
                }
            }
        }
        
        return null;
    }
}