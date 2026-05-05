<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; }
        .header { background: #16a34a; color: white; padding: 20px; text-align: center; border-radius: 6px; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 20px 0; }
        .btn { display: inline-block; background: #16a34a; color: white; padding: 12px 24px;
               text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { color: #888; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Approved</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $user->name }}</strong>,</p>
            <p>Your teacher account on <strong>EnglishEasy</strong> has been <strong>approved</strong> by the administrator.</p>
            <p>You can now log in and start creating lessons and exercises for your students.</p>
            <a href="{{ config('app.frontend_url') }}/login" class="btn">Go to Dashboard</a>
        </div>
        <div class="footer">
            <p>EnglishEasy Team</p>
        </div>
    </div>
</body>
</html>
