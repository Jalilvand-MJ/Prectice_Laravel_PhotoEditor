<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload New Photo</title>
</head>
<body>
<form action="/photos" enctype="multipart/form-data" method="POST">
    @csrf
    @if (session('status') == 'Failed')
        <div style="color:red">
            دوباره تلاش کنید. :(
        </div>
    @endif
    @error('photo')
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    @enderror
    <input accept="image/jpeg" name="photo" type="file" required>
    <button type="submit">Upload</button>
</form>
</body>
</html>
