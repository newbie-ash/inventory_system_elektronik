<?php

namespace App\Http\Controllers;

use App\Models\PromoBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class PromoBannerController extends Controller
{
    /**
     * Display list of promo banners and the active editor.
     */
    public function index(): View
    {
        $banners = PromoBanner::latest()->get();
        $activeBanner = PromoBanner::getActiveBanner() ?? $banners->first() ?? new PromoBanner([
            'tag' => 'PROMO SPESIAL GAJIAN:',
            'title' => 'Potongan hingga 15% untuk kategori PC & Gadget',
            'description' => 'Dapatkan penawaran terbatas untuk produk elektronik & hardware pilihan minggu ini.',
            'button_text' => 'Klaim Voucher',
            'button_url' => '#katalog-grid',
            'is_active' => true,
        ]);

        return view('banners.index', compact('banners', 'activeBanner'));
    }

    /**
     * Store a newly created promo banner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tag' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'button_text' => ['required', 'string', 'max:50'],
            'button_url' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive) {
            PromoBanner::where('is_active', true)->update(['is_active' => false]);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename = 'banner_'.time().'_'.uniqid().'.'.$imageFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/banners');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $imageFile->move($destinationPath, $filename);
            $imagePath = 'uploads/banners/'.$filename;
        }

        PromoBanner::create([
            'tag' => $validated['tag'] ?? 'PROMO:',
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'button_text' => $validated['button_text'],
            'button_url' => $validated['button_url'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner promo baru berhasil ditambahkan.');
    }

    /**
     * Update the specified promo banner in storage.
     */
    public function update(Request $request, PromoBanner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'tag' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'button_text' => ['required', 'string', 'max:50'],
            'button_url' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isActive = $request->boolean('is_active', true);

        if ($isActive) {
            PromoBanner::where('id', '!=', $banner->id)->update(['is_active' => false]);
        }

        $imagePath = $banner->image;

        if ($request->boolean('remove_image')) {
            if ($banner->image && File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($banner->image && File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }

            $imageFile = $request->file('image');
            $filename = 'banner_'.time().'_'.uniqid().'.'.$imageFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/banners');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $imageFile->move($destinationPath, $filename);
            $imagePath = 'uploads/banners/'.$filename;
        }

        $banner->update([
            'tag' => $validated['tag'] ?? '',
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'button_text' => $validated['button_text'],
            'button_url' => $validated['button_url'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner promo berhasil diperbarui.');
    }

    /**
     * Remove the specified promo banner from storage.
     */
    public function destroy(PromoBanner $banner): RedirectResponse
    {
        if ($banner->image && File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }

        $banner->delete();

        // If no active banner left, activate the most recent one
        if (! PromoBanner::where('is_active', true)->exists()) {
            PromoBanner::latest()->first()?->update(['is_active' => true]);
        }

        return redirect()->route('banners.index')->with('success', 'Banner promo berhasil dihapus.');
    }
}
