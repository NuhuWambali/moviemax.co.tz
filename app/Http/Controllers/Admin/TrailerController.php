<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrailerController extends Controller
{
    public function index()
    {
        $trailers = Trailer::with('creator')->latest()->paginate(20);
        return view('admin.trailers.index', compact('trailers'));
    }

    public function create()
    {
        return view('admin.trailers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $name = time() . '_trailer_thumb.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('trailers', $name, 'public');
            $data['thumbnail'] = Storage::url($path);
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $name = time() . '_trailer_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('trailers', $name, 'public');
            $data['file_path'] = Storage::url($path);
            $data['source_type'] = 'file';
        }

        if (!$request->file_path_url && empty($data['file_path'])) {
            $data['source_type'] = 'youtube';
        }

        $data['created_by'] = auth()->id();

        Trailer::create($data);

        return redirect()->route('admin.trailers.index')
            ->with('success', 'Trailer "' . $data['title'] . '" added successfully!');
    }

    public function edit(Trailer $trailer)
    {
        return view('admin.trailers.edit', compact('trailer'));
    }

    public function update(Request $request, Trailer $trailer)
    {
        $data = $this->validateData($request, $trailer);

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $name = time() . '_trailer_thumb.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('trailers', $name, 'public');
            $data['thumbnail'] = Storage::url($path);
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $name = time() . '_trailer_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('trailers', $name, 'public');
            $data['file_path'] = Storage::url($path);
            $data['source_type'] = 'file';
        }

        if ($request->source_type === 'file' && $request->file_path_url) {
            $data['file_path'] = $request->file_path_url;
            $data['source_type'] = 'file';
        }

        if ($request->source_type === 'youtube') {
            $data['source_type'] = 'youtube';
        }

        $trailer->update($data);

        return redirect()->route('admin.trailers.index')
            ->with('success', 'Trailer "' . $data['title'] . '" updated successfully!');
    }

    public function destroy(Trailer $trailer)
    {
        $trailer->delete();
        return redirect()->route('admin.trailers.index')
            ->with('success', 'Trailer deleted.');
    }

    public function toggleStatus(Trailer $trailer)
    {
        $trailer->update(['is_active' => !$trailer->is_active]);
        return redirect()->route('admin.trailers.index')
            ->with('success', 'Trailer status updated.');
    }

    private function validateData(Request $request, ?Trailer $trailer = null): array
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'thumbnail_url'  => 'nullable|url',
            'video_file'     => 'nullable|file|mimes:mp4,m4v,webm,ogg|max:102400',
            'file_path_url'  => 'nullable|url',
            'trailer_url'    => 'nullable|url',
            'source_type'    => 'nullable|in:youtube,file',
            'is_active'      => 'nullable|boolean',
        ];

        $validated = $request->validate($rules);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'trailer_url' => $request->trailer_url,
            'is_active'   => $request->boolean('is_active'),
        ];

        if ($request->thumbnail_url) {
            $data['thumbnail'] = $request->thumbnail_url;
        }

        return $data;
    }
}