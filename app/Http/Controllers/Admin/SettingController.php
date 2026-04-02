<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settingService = app(SettingService::class);

        foreach ($request->except(['_token', '_method']) as $key => $value) {
            // Convert dot notation: general__site_name → general.site_name
            $settingKey = str_replace('__', '.', $key);

            // Handle file uploads
            if ($request->hasFile($key)) {
                $old = Setting::get($settingKey);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $value = $request->file($key)->store('settings', 'public');
            }

            $settingService->set($settingKey, $value);
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    public function testEmail()
    {
        try {
            $adminEmail = auth()->user()->email;
            Mail::raw('This is a test email from ' . Setting::get('general.site_name', 'Porto Shop'), function ($mail) use ($adminEmail) {
                $mail->to($adminEmail)->subject('Test Email');
            });
            return back()->with('success', 'Test email sent to ' . $adminEmail);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: ' . $e->getMessage());
        }
    }
}
