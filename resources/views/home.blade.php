@extends('layouts.app')
@section('content')

{{-- Render homepage sections dynamically (managed via admin Homepage Builder) --}}
@foreach($sections as $section)
    @if($section->is_active)
        @php $partialName = 'partials.homepage.' . $section->type; @endphp

        @if(view()->exists($partialName))
            @include($partialName, ['section' => $section, 'sectionData' => $sectionData])
        @else
            {{-- Fallback for unknown section types --}}
            @if(config('app.debug'))
            <div class="container my-3">
                <div class="alert alert-warning">
                    <strong>Missing partial:</strong> {{ $partialName }} for section "{{ $section->title }}" ({{ $section->key }})
                </div>
            </div>
            @endif
        @endif
    @endif
@endforeach

@endsection
