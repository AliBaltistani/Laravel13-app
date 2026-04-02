<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = PostComment::with(['post', 'user']);
        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        $comments = $query->latest()->paginate(20)->withQueryString();
        return view('admin.comments.index', compact('comments'));
    }

    public function toggle(PostComment $comment)
    {
        $comment->update(['is_approved' => !$comment->is_approved]);
        return back()->with('success', 'Comment ' . ($comment->is_approved ? 'approved' : 'rejected') . '.');
    }

    public function destroy(PostComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        PostComment::whereIn('id', $request->ids)->update(['is_approved' => true]);
        return back()->with('success', count($request->ids) . ' comments approved.');
    }
}
