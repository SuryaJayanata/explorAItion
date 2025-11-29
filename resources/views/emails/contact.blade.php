<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message - eduSPACE</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; }
        .field { margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px; border-left: 4px solid #667eea; }
        .label { font-weight: bold; color: #667eea; display: block; margin-bottom: 5px; }
        .footer { text-align: center; margin-top: 20px; padding: 15px; color: #666; font-size: 12px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 New Contact Message</h1>
        <p>eduSPACE Contact Form</p>
    </div>
    
    <div class="content">
        <div class="field">
            <span class="label">From:</span> 
            {{ $name ?? 'N/A' }} ({{ $email ?? 'N/A' }})
        </div>
        
        <div class="field">
            <span class="label">Time:</span> 
            {{ $timestamp ?? now()->format('Y-m-d H:i:s') }}
        </div>
        
       
        
        <div class="field">
            <span class="label">Message Content:</span>
            <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                {{ $contact_message ?? 'No message provided' }}
            </div>
        </div>

    </div>
    
    <div class="footer">
        <p>This email was sent from the eduSPACE contact form.</p>
        <p>You can reply directly to: {{ $email ?? 'N/A' }}</p>
        <p>&copy; {{ date('Y') }} eduSPACE. All rights reserved.</p>
    </div>
</body>
</html>