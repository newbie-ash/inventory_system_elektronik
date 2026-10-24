@props(['type' => null])

@if ($type && isset($slot) && $slot->isNotEmpty())
    <div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 3px solid var(--colors-accent); color: var(--colors-body);">
        @if ($type === 'success')
            <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
        @elseif ($type === 'danger' || $type === 'error')
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2" style="color: var(--colors-accent);"></i>
        @else
            <i class="bi bi-info-circle-fill fs-5 me-2 text-primary"></i>
        @endif
        <div class="flex-grow-1 font-monospace small">
            {{ $slot }}
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 3px solid #22c55e; color: var(--colors-body);">
        <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
        <div class="flex-grow-1 font-monospace small">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 3px solid var(--colors-accent); color: var(--colors-body);">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2" style="color: var(--colors-accent);"></i>
        <div class="flex-grow-1 font-monospace small">
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 3px solid #eab308; color: var(--colors-body);">
        <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-warning"></i>
        <div class="flex-grow-1 font-monospace small">
            {{ session('warning') }}
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-dismissible fade show mb-4" role="alert" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 3px solid var(--colors-accent); color: var(--colors-body);">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2" style="color: var(--colors-accent);"></i>
            <strong class="font-monospace text-white">Terdapat kesalahan pengisian data:</strong>
        </div>
        <ul class="mb-0 ps-3 small font-monospace" style="color: var(--colors-muted);">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
