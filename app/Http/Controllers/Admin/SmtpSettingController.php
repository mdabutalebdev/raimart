<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\MailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SmtpSettingController extends Controller
{
    private const KEYS = [
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_email',
        'smtp_from_name',
    ];

    public function edit()
    {
        $settings = SiteSetting::getAll();

        return view('admin.smtp.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'string', 'max:10'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl,none'],
            'smtp_from_email' => ['nullable', 'email', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (self::KEYS as $key) {
            // Blank password means "keep the saved one" so it isn't wiped on every save.
            if ($key === 'smtp_password' && blank($data[$key] ?? null)) {
                continue;
            }

            SiteSetting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.smtp.edit')->with('status', 'SMTP settings saved.');
    }

    /** Sends a plain test email using the saved settings. */
    public function test(Request $request)
    {
        $data = $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        if (! MailConfig::isConfigured()) {
            return redirect()->route('admin.smtp.edit')->with('error', 'Save your SMTP host, port and credentials first.');
        }

        MailConfig::apply();

        try {
            Mail::raw('This is a test email from your Raimart website. Your SMTP settings are working correctly.', function ($message) use ($data) {
                $message->to($data['test_email'])->subject('Raimart SMTP test');
            });

            return redirect()->route('admin.smtp.edit')->with('status', 'Test email sent to '.$data['test_email'].'.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.smtp.edit')->with('error', 'Failed: '.$e->getMessage());
        }
    }
}
