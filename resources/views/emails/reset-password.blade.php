<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Venturo Siakad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #007bff;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        .token {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-family: monospace;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reset Your Password</h2>
    </div>
    
    <div class="content">
        <p>Hello,</p>
        
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <p>Please use the following token to reset your password:</p>
        
        <div class="token">
            {{ $token }}
        </div>
        
        <p>This password reset token will expire in 60 minutes.</p>
        
        <p>If you did not request a password reset, no further action is required.</p>
        
        <p>Regards,<br>The Venturo Team</p>
        
        <div class="footer">
            <p>If you're having trouble clicking the button, copy and paste the token into your application.</p>
        </div>
    </div>
</body>
</html>