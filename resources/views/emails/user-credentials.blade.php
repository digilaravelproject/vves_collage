<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
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
        .credential-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }
        .credential-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e5e7eb;
        }
        .credential-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        .value {
            font-weight: 700;
            color: #111827;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            margin-top: 24px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
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
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $user->name }}!</h2>
            <p>Your account has been successfully created. You can now log in to the portal using the credentials provided below.</p>
            
            <div class="credential-box">
                <div class="credential-row">
                    <span class="label">Portal URL</span>
                    <span class="value"><a href="{{ url('/admin') }}" style="color: #4f46e5; text-decoration: none;">Click Here</a></span>
                </div>
                <div class="credential-row">
                    <span class="label">Email Address</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential-row">
                    <span class="label">Temporary Password</span>
                    <span class="value" style="font-family: monospace; letter-spacing: 1px;">{{ $password }}</span>
                </div>
                <div class="credential-row">
                    <span class="label">Assigned Role</span>
                    <span class="value">{{ $role }}</span>
                </div>
            </div>

            <p style="color: #ef4444; font-size: 13px; font-weight: 600;">
                <i class="bi bi-shield-lock"></i> Please change your password immediately after your first login.
            </p>

            <div style="text-align: center;">
                <a href="{{ url('/admin') }}" class="btn">Login to Dashboard</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>If you did not expect this email, please contact <a href="mailto:{{ config('mail.from.address') }}">Support</a>.</p>
        </div>
    </div>
</body>
</html>
