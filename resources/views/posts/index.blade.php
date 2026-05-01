<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
        <h1 style="font-family: Poppins, sans-serif;">All Posts</h1>
        <ul style="font-family: Poppins, sans-serif; list-style-type: none; padding: 0;">
            @foreach ($posts as $post)
                <li style="margin-bottom: 10px;">{{ $post->name }} - Age: {{ $post->age }} - Weight: {{ $post->weight }}</li>
            @endforeach
    </div>
</body>
</html>