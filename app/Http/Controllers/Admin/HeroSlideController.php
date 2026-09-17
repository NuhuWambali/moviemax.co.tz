<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->paginate(20);
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        $trailers = Trailer::orderBy('title')->get(['id', 'title', 'slug']);
        return view('admin.hero-slides.create', compact('trailers'));
    }

    public function store(Request $request)
    {
        $data = $this->collectData($request);

        if ($request->hasFile('hero_file')) {
            $data['image_path'] = $this->storeImage($request);
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = $request->image_url;
        }

        HeroSlide::create($data);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide "' . $data['title'] . '" added successfully!');
    }

    public function edit(HeroSlide $heroSlide)
    {
        $trailers = Trailer::orderBy('title')->get(['id', 'title', 'slug']);
        return view('admin.hero-slides.edit', compact('heroSlide', 'trailers'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $this->collectData($request);

        if ($request->hasFile('hero_file')) {
            $data['image_path'] = $this->storeImage($request);
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = $request->image_url;
        } else {
            $data['image_path'] = $heroSlide->image_path;
        }

        $heroSlide->update($data);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully!');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted.');
    }

    public function toggleStatus(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);

        return back()->with('success', $heroSlide->is_active ? 'Slide activated.' : 'Slide deactivated.');
    }

    private function collectData(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'image_url' => 'nullable|url',
            'hero_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'trailer_url' => 'nullable|url',
            'link_type' => 'nullable|in:trailer',
            'link_id' => 'nullable|integer|required_with:link_type',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        return [
            'title' => $validated['title'],
            'tagline' => $validated['tagline'] ?? $validated['title'],
            'trailer_url' => $validated['trailer_url'] ?? null,
            'link_type' => $validated['link_type'] ?? null,
            'link_id' => $validated['link_type'] ? ($validated['link_id'] ?? null) : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ];
    }

    private function storeImage(Request $request): string
    {
        $file = $request->file('hero_file');
        $name = time() . '_hero_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('heroes', $name, 'public');
        return Storage::url($path);
    }
}