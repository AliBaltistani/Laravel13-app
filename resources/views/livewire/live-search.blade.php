{{-- Live Search Livewire Component (Demo8 popup style) --}}
<div class="header-search header-search-popup header-search-category d-none d-sm-block" wire:ignore.self>
    <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
    <form wire:submit.prevent="submitSearch">
        <div class="header-search-wrapper">
            <input type="search" class="form-control" placeholder="I'm searching for..."
                   wire:model.live.debounce.300ms="query"
                   wire:keydown.escape="hideResults"
                   autocomplete="off">
            <div class="select-custom">
                <select wire:model.live="categorySlug">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @foreach($cat->children()->active()->ordered()->get() as $child)
                            <option value="{{ $child->slug }}">- {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <button class="btn icon-search-3" type="submit"></button>
        </div>
    </form>
</div>
