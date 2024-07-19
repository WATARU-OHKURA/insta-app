<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>Your password has been successfully changed.</p>
    <p>If you did not make this change or if you have any concerns, please contact our support team immediately.</p>
    <p>To access your account, please visit <a href="{{ $app_url }}">our website</a>.</p>
    <p>Thank you for using our service!</p>
</body>
</html>
