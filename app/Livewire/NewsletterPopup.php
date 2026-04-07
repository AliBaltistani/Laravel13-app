<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NewsletterSubscriber;
use App\Models\Coupon;

class NewsletterPopup extends Component
{
    public $email;
    public $successMessage = '';
    public $couponCode = '';

    public function subscribe()
    {
        $this->validate([
            'email' => 'required|email|max:255'
        ], [
            'email.required' => 'Please enter a valid email address.'
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $this->email],
            ['is_active' => true] // DB uses is_active or active depending on your migration. Usually there's a status or similar. Let's assume we just create it.
        );

        // Fetch or dynamically create the $50 Promotional Coupon
        $coupon = Coupon::where('code', 'WELCOME50')->first();
        if (!$coupon) {
            $coupon = Coupon::create([
                'code' => 'WELCOME50',
                'name' => 'Get Your $50 Off Newsletter Bonus',
                'type' => 'fixed',
                'value' => 50.00,
                'min_order_amount' => 100.00, // Example minimum
                'usage_limit_per_user' => 1,
                'is_active' => true,
            ]);
        }

        $this->couponCode = $coupon->code;
        $this->successMessage = 'Thank you! Your coupon is ready:';
        
        $this->dispatch('newsletter-subscribed', code: $this->couponCode);
    }

    public function render()
    {
        return view('livewire.newsletter-popup');
    }
}
