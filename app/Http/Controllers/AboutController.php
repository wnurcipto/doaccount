<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();
        $company = CompanyInfo::getInfo($user->id);
        return view('about.index', compact('company'));
    }

    public function update(Request $request)
    {
        // Block free account
        if ($this->isFreeAccount()) {
            return $this->redirectFreeAccount();
        }

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $this->currentUser();
        $company = CompanyInfo::getInfo($user->id);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('company', 'public');
            $validated['logo'] = $logoPath;
        } else {
            // Keep existing logo if not uploading new one
            $validated['logo'] = $company->logo;
        }

        // Pastikan user_id tetap sama
        $validated['user_id'] = $user->id;
        $company->update($validated);

        return redirect()->route('about.index')
            ->with('success', 'Informasi perusahaan berhasil diperbarui');
    }
}
