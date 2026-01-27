@props([
    'title',
    'value',
    'color' => 'dark'
])

<div class="col-md-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            <small class="text-muted">{{ $title }}</small>
            <h4 class="fw-bold text-{{ $color }}">
                {{ $value }}
            </h4>
        </div>
    </div>
</div>
