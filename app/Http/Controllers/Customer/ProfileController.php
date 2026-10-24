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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
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
        $user->email = $validated['email'];
        $user->save();

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = 'avatar_'.$user->id.'_'.time().'.'.$avatarFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/avatars');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $avatarFile->move($destinationPath, $filename);
            $avatarPath = 'uploads/avatars/'.$filename;
        }

        // Update profile
        $profileData = [
            'full_name' => $validated['name'],
            'phone_number' => $validated['phone_number'] ?? null,
            'department' => $validated['username'] ?? null,
            'bio' => $validated['address'] ?? null,
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
}
