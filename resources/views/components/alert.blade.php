@props(['type' => null])

@if ($type && isset($slot) && $slot->isNotEmpty())
    <div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
        @if ($type === 'success')
            <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
        @elseif ($type === 'danger' || $type === 'error')
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
        @else
            <i class="bi bi-info-circle-fill fs-5 me-2 text-primary"></i>
        @endif
        <div class="flex-grow-1">
            {{ $slot }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(209, 250, 229, 0.85); color: #065f46;">
        <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
        <div class="flex-grow-1 fw-medium">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(254, 226, 226, 0.85); color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
        <div class="flex-grow-1 fw-medium">
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(254, 243, 199, 0.85); color: #92400e;">
        <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-warning"></i>
        <div class="flex-grow-1 fw-medium">
            {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); background: rgba(254, 226, 226, 0.88); color: #991b1b;">
        <div class="d-flex align-items-center mb-1">
            <i class="bi bi-x-circle-fill fs-5 me-2 text-danger"></i>
            <strong class="flex-grow-1">Terdapat beberapa kesalahan:</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
