@php
    $company = \App\Models\CompanyInfo::getInfo(auth()->id());
@endphp

<div class="print-header" style="border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            @if($company->logo)
                <img src="{{ public_path('storage/' . $company->logo) }}" alt="Logo" 
                     style="max-width: 100px; max-height: 100px;">
            @else
                <div style="width: 100px; height: 100px; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <span style="font-size: 2rem; color: #999;">LOGO</span>
                </div>
            @endif
        </div>
        <div class="col-md-10">
            <h3 style="margin: 0; font-weight: bold; color: #333;">{{ $company->nama_perusahaan }}</h3>
            @if($company->alamat)
                <p style="margin: 5px 0; font-size: 12px; color: #666;">{{ $company->alamat }}</p>
            @endif
            <p style="margin: 5px 0; font-size: 12px; color: #666;">
                @if($company->kota && $company->provinsi)
                    {{ $company->kota }}, {{ $company->provinsi }}
                    @if($company->kode_pos) {{ $company->kode_pos }} @endif
                @endif
            </p>
            <p style="margin: 5px 0; font-size: 12px; color: #666;">
                @if($company->telepon) Telp: {{ $company->telepon }} @endif
                @if($company->email) | Email: {{ $company->email }} @endif
                @if($company->website) | Website: {{ $company->website }} @endif
            </p>
        </div>
    </div>
</div>

