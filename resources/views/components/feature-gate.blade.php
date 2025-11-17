@if($hasAccess)
    {{ $slot }}
@else
    <div class="alert alert-info d-flex align-items-center">
        <i class="bi bi-lock me-2"></i>
        <div>
            <strong>Fitur Premium</strong><br>
            <small>Fitur ini hanya tersedia untuk plan {{ ucfirst($requiredPlan) }} ke atas. 
            <a href="#" class="alert-link">Upgrade Sekarang</a></small>
        </div>
    </div>
@endif