<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <p>Hello ,</p>

    <p>Your 6-digit verification code is:</p>

    <h2 style="color: #2d3748;">{{ $user->verification_code }}</h2>

    <p>Please enter this code in the app to verify your email address.</p>

    <p>Thanks,<br>
    {{ config('app.name') }} Team</p>

    
</body>
</html>
