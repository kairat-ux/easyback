<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 6px; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 20px 0; }
        .footer { color: #888; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>We received your message</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $name }}</strong>,</p>
            <p>Thank you for contacting EnglishEasy. We have received your message and will reply within 24 hours.</p>
            <p>In the meantime, feel free to browse our platform: <a href="{{ config('app.frontend_url') }}">{{ config('app.frontend_url') }}</a></p>
        </div>
        <div class="footer">
            <p>EnglishEasy Team</p>
        </div>
    </div>
</body>
</html>
