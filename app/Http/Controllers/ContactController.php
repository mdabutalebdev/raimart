<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Services\TelegramNotifier;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAll();

        return view('contact', compact('settings'));
    }

    public function store(Request $request, TelegramNotifier $telegram)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = ContactMessage::create($data);

        $telegram->contactMessage($message);

        return back()->with('status', 'Thanks for reaching out! We will get back to you soon.');
    }
}
