<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>We have received your page request.Soon we will review your request!</p>
    <p>Regards,<br>{{ config('app.name') }}</p>
</body>
</html>
