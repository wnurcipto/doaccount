@php
    $company = \App\Models\CompanyInfo::getInfo(auth()->id());
@endphp

<div class="print-watermark" style="display: none;">
    @if($company->logo)
        <img src="{{ Storage::url($company->logo) }}" alt="Watermark" class="watermark-logo">
    @else
        <div class="watermark-text">{{ $company->nama_perusahaan ?? 'DOCUMENT' }}</div>
    @endif
</div>

<style>
@media print {
    .print-watermark {
        display: block !important;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        z-index: 0;
        opacity: 0.08;
        pointer-events: none;
    }
    
    .watermark-logo {
        width: 400px;
        height: 400px;
        object-fit: contain;
    }
    
    .watermark-text {
        font-size: 120px;
        font-weight: bold;
        color: #999;
        white-space: nowrap;
    }
    
    .main-content,
    .card,
    .card-body,
    table,
    h2, h3, h4, h5 {
        position: relative;
        z-index: 1;
    }
}

@media screen {
    .print-watermark {
        display: none !important;
    }
}
</style>

