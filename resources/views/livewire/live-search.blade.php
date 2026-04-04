{{-- Live Search Livewire Component --}}
<div style="flex: 1; display: flex; align-items: center;">
    {{-- Porto Search Bar (matches original Porto HTML structure exactly) --}}
    <div class="header-icon header-search header-search-inline header-search-category text-right mt-0" style="flex: 1;">
        <a href="#" class="search-toggle" role="button"><i class="icon-search-3"></i></a>
        <form wire:submit.prevent="submitSearch">
            <div class="header-search-wrapper">
                <input type="search" class="form-control" placeholder="Search..."
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
                <button class="btn icon-magnifier p-0" type="submit"></button>
            </div>
        </form>
    </div>
</div>
