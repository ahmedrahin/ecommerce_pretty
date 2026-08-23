<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f2f4f6; font-family: 'Segoe UI', Roboto, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f2f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 40px;">
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <img src="{{ asset(config('app.logo')) }}" alt="">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h2 style="color: #333333; margin: 0;">🔐 Password Reset Request</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #555555; font-size: 16px; line-height: 1.6;">
                            <p>Hello <strong>{{ $name }}</strong>,</p>
                            <p>We received a request to reset your password. Click the button below to set a new password. This link will expire after a certain time for security purposes.</p>

                            <div style="text-align: center; margin: 30px 0;">
                                @if( $isAdmin == 1 )
                                    <a href="{{ route('admin.password.reset', ['token' => $token, 'email' => $email]) }}" 
                                       style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; display: inline-block;">
                                        Reset Your Password
                                    </a>
                                @else
                                    <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" 
                                       style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; display: inline-block;">
                                        Reset Your Password
                                    </a>
                                @endif
                            </div>

                            <p>If you didn’t request this, you can safely ignore this email.</p>
                            <p>Stay secure,<br><strong>{{ config('app.name') }} Team</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 30px; font-size: 12px; color: #888888; text-align: center;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
