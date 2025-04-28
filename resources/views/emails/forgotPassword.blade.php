<!DOCTYPE html>
<html>
    <head>
        <title>League Management Web-App - Forgot Password Notification</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                background-color: #6f7172;
                color: #fff;
                padding: 20px 0;
                text-align: center;
            }
            .content {
                padding: 20px;
            }
            .footer {
                padding: 20px;
                font-size: 12px;
                color: #777;
                text-align: center;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Password Reset Request</h2>
            </div>
            <div class="content">
                <p>Dear {{ $data['name'] }},</p>

                <p>We received a request to reset your password for your League Management Web-App account.</p>

                <p>A system-generated temporary password has been created for you. For your security, please log in using the credentials below and change your password immediately after logging in.</p>
    
                <p><strong>Account Details:</strong></p>
                <p><b>Username (Email):</b> {{ $data['email'] }}</p>
                <p><b>Temporary Password:</b> {{ $data['newPassword'] }}</p>
    

                {{-- Change the emails based of the final email of the app --}}
             <p>If you did not request this password reset, please contact our support team immediately at <a href="mailto:email_address@gmail.com">email_address@gmail.com</a>.</p>
             
             <p>Thank you for using League Management Web-App!</p>
             <p><em>(This is an automated message. Please do not reply to this email.)</em></p>
            </div>
            
        <div class="footer">
            &copy; {{ date('Y') }} League Management Web-App. All rights reserved.
        </div>
    </div>
    </body>
</html>
