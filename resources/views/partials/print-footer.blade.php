@php
    $company = \App\Models\CompanyInfo::getInfo(auth()->id());
@endphp

<div class="print-footer" style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 30px; text-align: center; font-size: 11px; color: #666;">
    @if($company->footer_text)
        <p style="margin: 0;">{{ $company->footer_text }}</p>
    @endif
    <p style="margin: 5px 0 0 0;">
        Dicetak pada: {{ date('d F Y, H:i:s') }} | Halaman <span class="page-number"></span>
    </p>
</div>

