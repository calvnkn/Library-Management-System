<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #1a56db; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 24px 32px; color: #374151; line-height: 1.6; }
        .body p { margin: 0 0 16px; }
        .footer { padding: 16px 32px; background: #f9fafb; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $notifTitle }}</h1>
        </div>
        <div class="body">
            <p>Hello, {{ $memberName }}!</p>
            <p>{{ $notifMessage }}</p>
            <p>Log in to your account to view your books and current status.</p>
        </div>
        <div class="footer">
            This is an automated notification from the Library Management System. Please do not reply to this email.
        </div>
    </div>
</body>
</html>
