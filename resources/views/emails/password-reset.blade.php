<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
        }
        .content h2 {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
        }
        .info-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
            color: #92400e;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #f59e0b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            margin: 24px 0;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            transition: transform 0.2s;
        }
        .footer {
            padding: 24px;
            text-align: center;
            background: #f9fafb;
            color: #9ca3af;
            font-size: 12px;
        }
        .footer a {
            color: #f59e0b;
            text-decoration: none;
        }
        .sub-text {
            font-size: 12px;
            color: #6b7280;
            word-break: break-all;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $name }}!</h2>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">Reset Password</a>
            </div>

            <div class="info-box">
                <p style="margin: 0;">This password reset link will expire in <strong>{{ $count }} minutes</strong>.</p>
            </div>

            <p>If you did not request a password reset, no further action is required.</p>

            <div class="sub-text">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br>
                <a href="{{ $url }}" style="color: #f59e0b;">{{ $url }}</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Security Team, {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
