<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin-top: 0;">New User Registration</h2>
    </div>

    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <p>Hello Admin,</p>

        <p>A new user has been registered in the system:</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $user->name }}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
            @if ($user->mobile)
                <p style="margin: 5px 0;"><strong>Mobile:</strong> {{ $user->mobile }}</p>
            @endif
            <p style="margin: 5px 0;"><strong>Registered At:</strong> {{ $user->created_at->format('F j, Y g:i A') }}
            </p>
        </div>

        <p>Please review the new user registration in the admin panel.</p>
    </div>

    <div
        style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #6c757d; font-size: 12px; text-align: center;">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
    </div>
</body>

</html>
