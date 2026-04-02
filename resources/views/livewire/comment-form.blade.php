{{-- Comment Form Livewire View --}}
<div class="comment-respond mt-4">
    <h3>Leave a Comment</h3>

    @if($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif

    <form wire:submit.prevent="submitComment">
        @guest
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" required>
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        @endguest

        <div class="form-group">
            <label>Comment <span class="required">*</span></label>
            <textarea class="form-control @error('body') is-invalid @enderror" wire:model="body" rows="4" required></textarea>
            @error('body') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submitComment">Post Comment</span>
            <span wire:loading wire:target="submitComment">Posting...</span>
        </button>
    </form>
</div>
