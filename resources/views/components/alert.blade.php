@props(['type' => null])

@if ($type && isset($slot) && $slot->isNotEmpty())
    <div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        @if ($type === 'success')
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        @elseif ($type === 'danger' || $type === 'error')
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        @else
            <i class="bi bi-info-circle-fill fs-5 me-2"></i>
        @endif
        <div class="flex-grow-1">
            {{ $slot }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
        <div class="flex-grow-1">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
        <div class="flex-grow-1">
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
        <div class="flex-grow-1">
            {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center mb-1">
            <i class="bi bi-x-circle-fill fs-5 me-2"></i>
            <strong class="flex-grow-1">Terdapat beberapa kesalahan input:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
