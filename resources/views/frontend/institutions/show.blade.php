@extends('layouts.app')
@section('title', $institution->meta_title ?: $institution->name)
@section('meta_description', $institution->meta_description ?: Str::limit($institution->institutional_journey, 160))

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('institutions.list') }}">Institutions</a></li>
                <li class="breadcrumb-item active">{{ $institution->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-2 font-weight-bold">{{ $institution->name }}</h1>
                <p class="text-muted mb-4">{{ $institution->category_label }} | Established: {{ $institution->year_of_establishment ?? 'N/A' }}</p>

                @if($institution->featured_image)
                    <div class="mb-5">
                        <img src="{{ asset('storage/' . $institution->featured_image) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 450px; object-fit: cover;">
                    </div>
                @endif

                {{-- Sections --}}
                <div class="mb-5">
                    <h3 class="mb-3 border-bottom pb-2">Institutional Journey</h3>
                    <div class="lead shadow-sm p-4 bg-white rounded-xl border border-gray-100 prose max-w-none">{!! $institution->institutional_journey !!}</div>
                </div>

                @if($institution->growth_graph)
                    <div class="mb-5">
                        <h3 class="mb-3">Growth Graph</h3>
                        <img src="{{ asset('storage/' . $institution->growth_graph) }}" class="img-fluid rounded border p-2">
                    </div>
                @endif

                @if($institution->principal)
                    <div class="card mb-5 border-0 shadow-sm bg-light">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ $institution->principal->photo ? asset('storage/' . $institution->principal->photo) : 'https://ui-avatars.com/api/?name='.urlencode($institution->principal->name).'&size=150' }}" class="rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="col-md-9">
                                    <h4 class="mb-1 font-weight-bold">{{ $institution->principal->name }}</h4>
                                    <p class="text-primary font-weight-medium mb-3">{{ $institution->principal->designation }}</p>
                                    <p class="font-italic text-muted" style="white-space: pre-line;">"{{ $institution->principal->description }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Dynamic Sections (Flexible blocks) --}}
                @foreach($institution->sections as $section)
                    @if($section->content)
                        <div class="mb-5">
                            <h3 class="mb-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $section->type)) }}</h3>
                            <div style="white-space: pre-line;">{!! $section->content !!}</div>
                        </div>
                    @endif
                @endforeach

                {{-- Results --}}
                @if($institution->results->count() > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Past Results & Achievements</h3>
                        <div class="row g-4">
                            @foreach($institution->results as $res)
                                <div class="col-md-6">
                                    <div class="card h-100 border shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($res->student_photo)
                                                    <img src="{{ asset('storage/' . $res->student_photo) }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h5 class="mb-0 font-weight-bold">{{ $res->title }}</h5>
                                                    <span class="badge bg-info">{{ $res->medium }} | {{ $res->year }}</span>
                                                </div>
                                            </div>
                                            <div class="mb-2"><strong>Overall Result:</strong> <span class="text-success font-weight-bold">{{ $res->overall_result }}</span></div>
                                            @if($res->grades)
                                                @php $grades = is_array($res->grades) ? $res->grades : json_decode($res->grades, true); @endphp
                                                <div class="small">
                                                    @foreach(['A', 'B', 'C'] as $g)
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>Grade {{ $g }}:</span>
                                                            <span class="font-weight-bold">{{ $grades[$g] ?? '0' }}%</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($res->description)
                                                <p class="mt-3 text-muted small">{{ $res->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PTA Members --}}
                @if($institution->ptaMembers->count() > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">PTA Members</h3>
                        <div class="row g-3">
                            @foreach($institution->ptaMembers as $member)
                                <div class="col-md-4 col-6">
                                    <div class="text-center">
                                        <img src="{{ $member->photo ? asset('storage/' . $member->photo) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&size=100' }}" class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                                        <h6 class="mb-1 font-weight-bold">{{ $member->name }}</h6>
                                        <p class="text-muted small">{{ $member->mobile }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Awards --}}
                @if($institution->awards->count() > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Awards & Recognition</h3>
                        <div class="row g-4">
                            @foreach($institution->awards as $award)
                                <div class="col-md-6">
                                    <div class="card h-100 border shadow-sm overflow-hidden">
                                        @if($award->photo)
                                            <img src="{{ asset('storage/' . $award->photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title font-weight-bold">{{ $award->title }}</h5>
                                            <p class="card-text text-muted small">{{ $award->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Gallery --}}
                @if($institution->galleries->count() > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Gallery</h3>
                        <div class="row g-3">
                            @foreach($institution->galleries as $img)
                                <div class="col-md-4 col-6">
                                    <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="img-fluid rounded" style="height: 200px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Sidebar: Contact Info --}}
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white font-weight-bold py-3">Contact Information</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                                <span>{{ $institution->address ?? 'Not available' }}</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                <span>{{ $institution->phone ?? 'Not available' }}</span>
                            </li>
                            @if($institution->website)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-globe text-primary me-2"></i>
                                    <a href="{{ $institution->website }}" target="_blank" class="text-decoration-none">{{ str_replace(['http://', 'https://'], '', $institution->website) }}</a>
                                </li>
                            @endif
                        </ul>

                        <h6 class="font-weight-bold mb-3">Connect With Us</h6>
                        <div class="d-flex gap-2">
                            @if($institution->social_links)
                                @php $socials = is_array($institution->social_links) ? $institution->social_links : json_decode($institution->social_links, true); @endphp
                                @foreach(['facebook', 'instagram', 'linkedin', 'youtube'] as $s)
                                    @if(isset($socials[$s]) && $socials[$s])
                                        <a href="{{ $socials[$s] }}" class="btn btn-outline-primary btn-sm rounded-circle" target="_blank">
                                            <i class="bi bi-{{ $s }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
