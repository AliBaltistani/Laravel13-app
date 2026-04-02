<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReviewSection extends Component
{
    public $productId;
    public $rating = 5;
    public $title = '';
    public $body = '';
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'required|string|max:255',
        'body' => 'required|string|min:10|max:2000',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            $this->errorMessage = 'Please log in to submit a review.';
            return;
        }

        $this->validate();

        // Check if user already reviewed
        $existing = Review::where('product_id', $this->productId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $this->errorMessage = 'You have already reviewed this product.';
            return;
        }

        Review::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'is_approved' => false,
            'is_verified_purchase' => \App\Models\OrderItem::whereHas('order', fn($q) => $q->where('user_id', Auth::id()))
                ->where('product_id', $this->productId)
                ->exists(),
        ]);

        $this->reset(['rating', 'title', 'body', 'errorMessage']);
        $this->rating = 5;
        $this->successMessage = 'Thank you! Your review has been submitted and is pending approval.';
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->productId)
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.review-section', compact('reviews'));
    }
}
