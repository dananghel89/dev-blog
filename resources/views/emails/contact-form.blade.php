<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Message</title>
    <style>
        /* Reset styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        /* Container */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #0a0c0f 0%, #1a1a1a 100%);
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #6366f1;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: #ffffff;
        }
        
        .logo span {
            color: #6366f1;
        }
        
        .header-title {
            color: #9aa0a6;
            margin: 10px 0 0;
            font-size: 16px;
        }
        
        /* Badge */
        .date-badge {
            display: inline-block;
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            margin-top: 15px;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        
        /* Field styling */
        .field {
            margin-bottom: 30px;
        }
        
        .field-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #0a0c0f;
            margin-bottom: 8px;
            font-size: 16px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }
        
        .field-label i {
            color: #6366f1;
            margin-right: 10px;
            font-style: normal;
            font-size: 18px;
        }
        
        .field-value {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #6366f1;
            color: #333;
            font-size: 16px;
            word-break: break-word;
        }
        
        .field-value a {
            color: #6366f1;
            text-decoration: none;
        }
        
        .field-value a:hover {
            text-decoration: underline;
        }
        
        /* Message content specific */
        .message-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #6366f1;
            color: #333;
            font-size: 16px;
            line-height: 1.8;
            white-space: pre-wrap;
            font-family: 'Inter', monospace;
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
            margin: 30px 0;
        }
        
        /* Footer */
        .email-footer {
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .reply-button {
            display: inline-block;
            background-color: #6366f1;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        
        .reply-button:hover {
            background-color: #4f52e0;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #9aa0a6;
            text-decoration: none;
            font-size: 14px;
        }
        
        .social-links a:hover {
            color: #6366f1;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .email-content {
                padding: 20px 15px;
            }
            
            .field-value,
            .message-content {
                padding: 12px 15px;
            }
        }
        
        /* Emoji/Icon styling */
        .icon {
            font-size: 20px;
            margin-right: 8px;
        }
        
        /* Status indicators */
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            background-color: #e8f5e9;
            color: #2e7d32;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1 class="logo">{{ config('app.name') }}<span>.</span></h1>
            <p class="header-title">Full Stack Development Blog</p>
            <span class="date-badge">
                📅 {{ now()->format('F j, Y') }} at {{ now()->format('g:i A') }}
            </span>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div style="margin-bottom: 25px; text-align: center;">
                <h2 style="color: #0a0c0f; margin-bottom: 5px;">📬 New Contact Form Submission</h2>
                <p style="color: #6c757d; margin: 0;">Someone has sent you a message from your blog</p>
            </div>
            
            <!-- Divider -->
            <div class="divider"></div>
            
            <!-- Name Field -->
            <div class="field">
                <div class="field-label">
                    <i>👤</i> Sender Information
                </div>
                <div class="field-value">
                    <strong style="color: #6366f1;">{{ $data['name'] }}</strong>
                    <span style="color: #6c757d; margin-left: 10px;">({{ $data['email'] }})</span>
                </div>
            </div>
            
            <!-- Subject Field -->
            <div class="field">
                <div class="field-label">
                    <i>📝</i> Subject
                </div>
                <div class="field-value">
                    {{ $data['subject'] }}
                </div>
            </div>
            
            <!-- Message Field -->
            <div class="field">
                <div class="field-label">
                    <i>💬</i> Message
                </div>
                <div class="message-content">
                    {{ nl2br(e($data['message'])) }}
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div style="background-color: #f0f4ff; border-radius: 8px; padding: 15px; margin: 30px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="text-align: center; padding: 5px;">
                            <div style="color: #6366f1; font-size: 20px; font-weight: 700;">{{ strlen($data['message']) }}</div>
                            <div style="color: #6c757d; font-size: 12px;">Characters</div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="color: #6366f1; font-size: 20px; font-weight: 700;">{{ str_word_count($data['message']) }}</div>
                            <div style="color: #6c757d; font-size: 12px;">Words</div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="color: #6366f1; font-size: 20px; font-weight: 700;">{{ explode('@', $data['email'])[1] ?? 'unknown' }}</div>
                            <div style="color: #6c757d; font-size: 12px;">Email Domain</div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Reply Button -->
            <div style="text-align: center;">
                <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subject'] }}" class="reply-button">
                    ✉️ Reply to {{ $data['name'] }}
                </a>
            </div>
            
            <!-- Additional Info -->
            <div style="margin-top: 30px; padding: 20px; background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    <strong>⏰ Response Time Goal:</strong> Aim to respond within 24-48 hours for the best experience.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p class="footer-text">
                This message was sent from the contact form on <strong>{{ config('app.name') }}</strong>
            </p>
            
            <div class="social-links">
                <a href="#">GitHub</a> •
                <a href="#">Twitter</a> •
                <a href="#">LinkedIn</a> •
                <a href="#">Blog</a>
            </div>
            
            <p class="footer-text" style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                <span style="color: #999;">Built with Laravel and ❤️</span>
            </p>
        </div>
    </div>
</body>
</html>