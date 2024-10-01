<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Logged In User</h1>

    <p>Logged in as: {{ $user->name }}</p>

    <p>Email: {{ $user->email }}</p>

    <img src="{{ $user->image_url }}" alt="User Avatar">

    <p>
        <pre>
            {{ json_encode($tracks[0], JSON_PRETTY_PRINT) }}
        </pre>
    </p>
</body>

</html>
