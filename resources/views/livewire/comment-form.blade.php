{{-- Comment Form Livewire View --}}
<div class="comment-respond mt-5 p-4 p-md-5" style="background-color: #f6f6f6; border-radius: 4px;">
    <h3 style="font-size: 20px; font-family: 'Poppins', sans-serif; font-weight: 800; color: #222; margin-bottom: 25px;">Leave a Comment</h3>

    @if($successMessage)
        <div class="alert alert-success">{{ $successMessage }}</div>
    @endif

    <form wire:submit.prevent="submitComment">
        @guest
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group mb-0">
                    <label style="font-weight: 700; font-size: 13px; color: #222;">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" required style="background: #fff; border: 1px solid #e1e1e1; padding: 12px 15px; border-radius: 4px; box-shadow: none;">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group mb-0">
                    <label style="font-weight: 700; font-size: 13px; color: #222;">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" required style="background: #fff; border: 1px solid #e1e1e1; padding: 12px 15px; border-radius: 4px; box-shadow: none;">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        @endguest

        <div class="form-group mb-4">
            <label style="font-weight: 700; font-size: 13px; color: #222;">Comment <span class="text-danger">*</span></label>
            <textarea class="form-control @error('body') is-invalid @enderror" wire:model="body" rows="6" required style="background: #fff; border: 1px solid #e1e1e1; padding: 15px; border-radius: 4px; box-shadow: none; resize: vertical;"></textarea>
            @error('body') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn text-white font-weight-bold px-4 py-2" wire:loading.attr="disabled" style="background-color: #222529; border-radius: 3px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
            <span wire:loading.remove wire:target="submitComment">Post Comment</span>
            <span wire:loading wire:target="submitComment">Posting...</span>
        </button>
    </form>
</div>
