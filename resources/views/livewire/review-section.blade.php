{{-- Review Section Livewire Component --}}
<div class="product-reviews-content">
    <h3 class="reviews-title">{{ $reviews->count() }} review(s) for this product</h3>

    @if($reviews->count())
    <div class="comment-list">
        @foreach($reviews as $review)
        <div class="comments">
            <figure class="img-thumbnail">
                <img src="{{ asset('themes/porto/images/blog/author.jpg') }}" alt="{{ $review->user?->full_name ?? 'User' }}" width="80" height="80">
            </figure>

            <div class="comment-block">
                <div class="comment-header">
                    <div class="comment-arrow"></div>
                    <div class="ratings-container float-sm-right">
                        <div class="product-ratings">
                            <span class="ratings" style="width:{{ ($review->rating / 5) * 100 }}%"></span>
                            <span class="tooltiptext tooltip-top"></span>
                        </div>
                    </div>

                    <span class="comment-by">
                        <strong>{{ $review->user?->full_name ?? 'Anonymous' }}</strong>
                        @if($review->is_verified_purchase)
                            <span class="badge badge-success ml-1">Verified Purchase</span>
                        @endif
                        – {{ $review->created_at->format('M d, Y') }}
                    </span>
                </div>

                <div class="comment-content">
                    @if($review->title)
                        <h4 class="mb-1">{{ $review->title }}</h4>
                    @endif
                    <p>{{ $review->body }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
    @endif

    {{-- Review Form --}}
    <div class="add-product-review mt-4">
        <h3 class="review-title">Add a review</h3>

        @if($successMessage)
            <div class="alert alert-success">{{ $successMessage }}</div>
        @endif
        @if($errorMessage)
            <div class="alert alert-danger">{{ $errorMessage }}</div>
        @endif

        @auth
        <form wire:submit.prevent="submitReview">
            <div class="rating-form">
                <label for="rating">Your Rating <span class="required">*</span></label>
                <span class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <a class="star-{{ $i }}" href="#" wire:click.prevent="$set('rating', {{ $i }})" style="{{ $rating >= $i ? 'color: #fcb941;' : '' }}">{{ $i }}</a>
                    @endfor
                </span>
            </div>

            <div class="form-group">
                <label>Review Title <span class="required">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" placeholder="Give your review a title">
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Your Review <span class="required">*</span></label>
                <textarea class="form-control @error('body') is-invalid @enderror" wire:model="body" cols="5" rows="6" placeholder="Write your review here..."></textarea>
                @error('body') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-dark ls-n-15" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submitReview">Submit</span>
                <span wire:loading wire:target="submitReview">Submitting...</span>
            </button>
        </form>
        @else
        <p>Please <a href="{{ route('login') }}">log in</a> to submit a review.</p>
        @endauth
    </div>
</div>
