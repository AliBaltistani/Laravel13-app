{{-- Breadcrumb Component
    Usage: @include('partials.breadcrumb', ['title' => 'Page Title', 'items' => [['label' => 'Home', 'url' => '/'], ['label' => 'Shop', 'url' => '/shop']]])
--}}
<div class="page-header">
    <div class="container d-flex flex-column align-items-center">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    @if(isset($items) && is_array($items))
                        @foreach($items as $item)
                            @if(!$loop->last || !isset($title))
                                <li class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a></li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                            @endif
                        @endforeach
                    @endif
                    @if(isset($title) && (!isset($items) || !is_array($items) || count($items) === 0))
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    @endif
                </ol>
            </div>
        </nav>
        @isset($title)
            <h1>{{ $title }}</h1>
        @endisset
    </div>
</div>
