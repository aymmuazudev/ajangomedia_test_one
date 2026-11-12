<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? '' }}</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f5f7; font-family: 'Open Sans', 'Segoe UI', Arial, sans-serif; color: #1f2937; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .email-header { background: #000000; padding: 20px 24px; }
        .email-brand { font-size: 18px; font-weight: 700; color: #ffffff; letter-spacing: 0.02em; }
        .email-brand-accent { color: #FE7600; }
        .email-body { padding: 32px 24px; }
        .email-body h1 { font-size: 22px; margin: 0 0 16px; color: #111827; }
        .email-body h2 { font-size: 18px; margin: 24px 0 12px; color: #111827; }
        .email-body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px; color: #374151; }
        .email-body a { color: #0058D4; text-decoration: none; }
        .email-body .lead { font-size: 16px; font-weight: 600; color: #111827; }
        .email-button {
            display: inline-block; background: #0058D4; color: #ffffff;
            font-size: 15px; font-weight: 600; text-decoration: none;
            padding: 12px 28px; border-radius: 6px; margin: 8px 0 24px;
        }
        .email-meta {
            font-size: 13px; color: #6b7280; background: #f9fafb;
            border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;
            margin: 16px 0;
        }
        .email-meta p { margin: 0 0 4px; font-size: 13px; color: #6b7280; }
        .email-meta p:last-child { margin-bottom: 0; }
        .email-meta strong { color: #374151; }
        .email-footer { background: #000000; padding: 20px 24px; text-align: center; }
        .email-footer p { margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5; }
        .email-footer a { color: #d1d5db; }
        @media (max-width: 600px) {
            .email-body { padding: 24px 16px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <span class="email-brand">AJANGO MEDIA <span class="email-brand-accent">News. Information. Impact.</span></span>
        </div>

        <div class="email-body">
            @yield('content')
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} AJANGO MEDIA LTD. All rights reserved.</p>
            <p>This message was sent automatically. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
