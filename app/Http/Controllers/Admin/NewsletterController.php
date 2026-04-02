<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        $subscribers = $query->latest()->paginate(20)->withQueryString();
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Name', 'Subscribed At']);
            foreach ($subscribers as $s) {
                fputcsv($file, [$s->email, $s->name, $s->created_at->format('Y-m-d H:i')]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'subscribers-' . now()->format('Y-m-d') . '.csv');
    }

    public function broadcastForm()
    {
        $count = NewsletterSubscriber::where('is_active', true)->count();
        return view('admin.newsletter.broadcast', compact('count'));
    }

    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        foreach ($subscribers as $subscriber) {
            Mail::raw($request->message, function ($mail) use ($subscriber, $request) {
                $mail->to($subscriber->email)->subject($request->subject);
            });
        }

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Broadcast sent to ' . $subscribers->count() . ' subscribers.');
    }
}
