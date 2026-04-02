<?php

namespace App\Livewire;

use App\Models\PostComment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CommentForm extends Component
{
    public $postId;
    public $name = '';
    public $email = '';
    public $body = '';
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'body' => 'required|string|min:3|max:2000',
    ];

    public function mount($postId)
    {
        $this->postId = $postId;
        if (Auth::check()) {
            $this->name = Auth::user()->full_name;
            $this->email = Auth::user()->email;
        }
    }

    public function submitComment()
    {
        $this->validate();

        PostComment::create([
            'post_id' => $this->postId,
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'body' => $this->body,
            'is_approved' => Auth::check(), // Auto-approve for logged-in users
        ]);

        $this->reset('body');
        $this->successMessage = Auth::check()
            ? 'Your comment has been posted!'
            : 'Your comment has been submitted and is pending approval.';
    }

    public function render()
    {
        return view('livewire.comment-form');
    }
}
