<?php

namespace App\View\Components;

use App\Services\FeatureAccess;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FeatureGate extends Component
{
    public bool $hasAccess;
    public string $requiredPlan;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $feature,
        public string $plan = 'professional'
    ) {
        $user = Auth::user();
        $this->hasAccess = $user ? FeatureAccess::userHasAccess($user, $feature) : false;
        $this->requiredPlan = FeatureAccess::getRequiredPlan($feature);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.feature-gate');
    }
}
