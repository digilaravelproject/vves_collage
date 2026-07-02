<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Action Required</title>
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
            border: 1px solid #e5e7eb;
        }
        .header {
            background: #1E234B;
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
            border-bottom: 4px solid #FFD700;
        }
        .logo {
            max-height: 70px;
            width: auto;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #ffffff;
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
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e5e7eb;
            align-items: center;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
            flex-shrink: 0;
        }
        .value {
            font-weight: 700;
            color: #111827;
            font-size: 14px;
            text-align: right;
            padding-left: 15px;
        }
        .badge {
            background: #FFD700;
            color: #1E234B;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .btn {
            display: inline-block;
            background: #1E234B;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            margin-top: 24px;
            box-shadow: 0 4px 12px rgba(30, 35, 75, 0.2);
            transition: transform 0.2s;
            border: 2px solid #FFD700;
        }
        .footer {
            padding: 24px;
            text-align: center;
            background: #f9fafb;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if (setting('college_logo'))
                <img src="{{ url('storage/' . setting('college_logo')) }}" alt="College Logo" class="logo">
            @endif
            <h1>{{ setting('college_name') ?? config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Workflow Action Required</h2>
            <p>A new request has been submitted and is pending your review and approval. Here are the details of the request:</p>
            
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Submitted By</span>
                    <span class="value">{{ $pendingAction->maker->name }} ({{ $pendingAction->maker->email }})</span>
                </div>
                <div class="info-row">
                    <span class="label">Action Type</span>
                    <span class="value"><span class="badge">{{ $pendingAction->action }}</span></span>
                </div>
                <div class="info-row">
                    <span class="label">Model Class</span>
                    <span class="value">{{ class_basename($pendingAction->model_type) }}</span>
                </div>
                @if($pendingAction->institution)
                <div class="info-row">
                    <span class="label">Institution</span>
                    <span class="value">{{ $pendingAction->institution->name }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="label">Submitted At</span>
                    <span class="value">{{ $pendingAction->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/workflow/' . $pendingAction->id) }}" class="btn">Review Request</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ setting('college_name') ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
