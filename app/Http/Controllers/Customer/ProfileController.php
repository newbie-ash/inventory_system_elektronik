<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show account settings and profile edit form for customer.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile;

        return view('customer.settings', compact('user', 'profile'));
    }

    /**
     * Update customer profile, contact, address, avatar and security.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'avatar_cropped' => ['nullable', 'string'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'avatar.max' => 'Ukuran foto maksimal 5MB.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        // Handle password change if requested
        if ($request->filled('new_password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Password saat ini tidak sesuai.',
                ]);
            }

            $user->password = Hash::make($request->new_password);
        }

        // Update basic user info
        $user->name = $validated['name'];
        if (! empty($validated['email'])) {
            $user->email = $validated['email'];
        }
        $user->save();

        // Handle avatar upload / crop
        $avatarPath = null;
        $destinationPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, public_path('uploads/avatars'));

        if (! File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        if (! is_writable($destinationPath)) {
            @chmod($destinationPath, 0777);
        }

        // 1. Cropped base64 image (from modal editor)
        if ($request->filled('avatar_cropped') && str_starts_with($request->avatar_cropped, 'data:image/')) {
            $dataUri = $request->avatar_cropped;
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $matches)) {
                $ext = strtolower($matches[1]);
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }
                $base64Content = substr($dataUri, strpos($dataUri, ',') + 1);
                $decoded = base64_decode($base64Content);
                if ($decoded !== false) {
                    $filename = 'avatar_'.$user->id.'_'.time().'_'.uniqid().'.'.$ext;
                    $targetPath = $destinationPath.DIRECTORY_SEPARATOR.$filename;
                    File::put($targetPath, $decoded);
                    $avatarPath = 'uploads/avatars/'.$filename;
                }
            }
        }
        // 2. Direct file upload
        elseif ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = 'avatar_'.$user->id.'_'.time().'_'.uniqid().'.'.$avatarFile->getClientOriginalExtension();
            $targetPath = $destinationPath.DIRECTORY_SEPARATOR.$filename;

            try {
                $avatarFile->move($destinationPath, $filename);
            } catch (\Throwable $e) {
                @copy($avatarFile->getRealPath(), $targetPath);
                if (! File::exists($targetPath)) {
                    File::put($targetPath, File::get($avatarFile->getRealPath()));
                }
            }

            if (File::exists($targetPath)) {
                $avatarPath = 'uploads/avatars/'.$filename;
            }
        }

        if ($avatarPath && ! empty($user->profile?->avatar)) {
            $oldAvatar = $user->profile->avatar;
            if (str_starts_with($oldAvatar, 'uploads/avatars/') && File::exists(public_path($oldAvatar))) {
                @File::delete(public_path($oldAvatar));
            }
        }

        // Update profile
        $bio = $validated['bio'] ?? $validated['address'] ?? ($user->profile->bio ?? null);
        $profileData = [
            'full_name' => $validated['name'],
            'phone_number' => $validated['phone_number'] ?? ($user->profile->phone_number ?? null),
            'department' => $validated['username'] ?? ($user->profile->department ?? null),
            'bio' => $bio,
        ];

        if ($avatarPath) {
            $profileData['avatar'] = $avatarPath;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('customer.settings')->with('success', 'Pengaturan profil berhasil diperbarui.');
    }

    /**
     * Show contact & address settings page.
     */
    public function contact(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile;

        return view('settings.contact', compact('user', 'profile'));
    }

    /**
     * Update contact and location data.
     */
    public function updateContact(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->email = $validated['email'];
        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name,
                'phone_number' => $validated['phone_number'] ?? null,
                'bio' => $validated['address'] ?? null,
            ]
        );

        return redirect()->route('settings.contact')->with('success', 'Kontak dan alamat penugasan berhasil diperbarui.');
    }

    /**
     * Show security & password settings page.
     */
    public function security(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile;

        return view('settings.security', compact('user', 'profile'));
    }

    /**
     * Update account password.
     */
    public function updateSecurity(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('settings.security')->with('success', 'Password akun berhasil diperbarui.');
    }

    /**
     * Show system preferences settings page.
     */
    public function preferences(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile;

        return view('settings.preferences', compact('user', 'profile'));
    }

    /**
     * Update system preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        return redirect()->route('settings.preferences')->with('success', 'Preferensi sistem berhasil disimpan.');
    }
}
