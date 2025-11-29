<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Log incoming request
        Log::info('Contact form submitted', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Please check your input',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Attempting to send email...');

            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'contact_message' => $request->message, // ✅ PASTIKAN 'contact_message'
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];

            // Log data sebelum kirim
            Log::info('Contact data prepared', $contactData);

            // Kirim email
            Mail::send('emails.contact', $contactData, function($message) use ($contactData) {
                $message->to('sjayanata00@gmail.com') // ✅ EMAIL ANDA
                        ->subject('📧 New Message from: ' . $contactData['name'])
                        ->replyTo($contactData['email']);
            });

            Log::info('Email sent successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully.'
            ]);

        } catch (\Exception $e) {
            // Log detailed error
            Log::error('Contact form FAILED: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error. Please try again later.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}