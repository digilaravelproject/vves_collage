@extends('layouts.app')
@section('title', 'Our Institutions')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Our Institutions</h1>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="btn-group flex-wrap gap-2">
                    <a href="{{ route('institutions.list') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    @foreach($categories as $key => $label)
                        <a href="{{ route('institutions.list', ['category' => $label]) }}" class="btn {{ request('category') == $label ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($institutions as $inst)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        @if($inst->featured_image)
                            <img src="{{ asset('storage/' . $inst->featured_image) }}" class="card-img-top" alt="{{ $inst->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title font-weight-bold">{{ $inst->name }}</h5>
                            <p class="text-muted small mb-3">{{ $inst->category }}</p>
                            <a href="{{ route('institutions.show', $inst->slug) }}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No institutions found in this category.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
