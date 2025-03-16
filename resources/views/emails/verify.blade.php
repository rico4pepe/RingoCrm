<!-- resources/views/emails/verify.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Custom styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            margin: 25px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background-color: #45a049;
        }
        .footer {
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
        .url-text {
            word-break: break-all;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Optional: Add your logo here -->
            <!-- <img src="https://yourwebsite.com/logo.png" alt="Company Logo" class="logo"> -->
            <h1>Email Verification</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>

            <p>Thank you for creating an account with us! To complete your registration and access all features, please verify your email address by clicking the button below:</p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </p>

            <p>If you did not create an account, no further action is required.</p>

            <p><strong>Note:</strong> This verification link will expire in 60 minutes.</p>

            <p>If you're having trouble with the button above, copy and paste the URL below into your web browser:</p>
            <div class="url-text">{{ $url }}</div>

            <p>Thank you for joining us!</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Ringo Telecommunications. All rights reserved.</p>
            <p>If you need any assistance, please contact our support team at support@ringo.com.ng</p>
        </div>
    </div>
</body>
</html>
