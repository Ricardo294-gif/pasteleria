@props(['type' => 'info', 'dismissible' => true, 'autoDismiss' => true])

@php
    $iconClass = match($type) {
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill',
        default => 'bi-info-circle-fill'
    };
    
    $classes = "custom-alert custom-alert-{$type}";
    if ($autoDismiss) {
        $classes .= " auto-dismiss";
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} style="min-width: 300px; white-space: normal;">
    <div class="icon">
        <i class="bi {{ $iconClass }}"></i>
    </div>
    <div class="content" style="white-space: normal;">
        {{ $slot }}
    </div>
    @if($dismissible)
    <button type="button" class="btn-close" aria-label="Cerrar"></button>
    @endif
</div> 