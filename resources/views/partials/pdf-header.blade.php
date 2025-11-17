@php
    $company = \App\Models\CompanyInfo::getInfo(auth()->id());
@endphp

<div style="border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
    <div style="display: flex; align-items: flex-start; gap: 20px;">
        <!-- Logo di Kiri -->
        <div style="flex-shrink: 0;">
            @if($company->logo)
                <img src="{{ Storage::url($company->logo) }}" alt="Logo" 
                     style="width: 80px; height: 80px; object-fit: contain;">
            @else
                <div style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 1.5rem; color: #999;">LOGO</span>
                </div>
            @endif
        </div>
        
        <!-- Informasi di Kanan -->
        <div style="flex: 1; min-width: 0;">
            <h3 style="margin: 0 0 8px 0; font-weight: bold; color: #333; font-size: 18px;">{{ $company->nama_perusahaan ?? 'PT. Rama Advertize' }}</h3>
            @if($company->alamat)
                <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">{{ $company->alamat }}</p>
            @endif
            <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">
                @if($company->kota && $company->provinsi)
                    {{ $company->kota }}, {{ $company->provinsi }}
                    @if($company->kode_pos) {{ $company->kode_pos }} @endif
                @endif
            </p>
            <p style="margin: 3px 0; font-size: 11px; color: #666; line-height: 1.4;">
                @if($company->telepon) Telp: {{ $company->telepon }} @endif
                @if($company->telepon && $company->email) | @endif
                @if($company->email) Email: {{ $company->email }} @endif
                @if(($company->telepon || $company->email) && $company->website) | @endif
                @if($company->website) Website: {{ $company->website }} @endif
            </p>
        </div>
    </div>
</div>

