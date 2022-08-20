<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Photo No. {{$id}}</title>
</head>
<body>

<form action="{{'/photos/' . $id }}" method="GET">
    <input name="action" value="show" hidden>
    <button type="submit">Show</button>
</form>


<form action="{{'/photos/' . $id }}" method="GET">
    <input name="action" value="gray" hidden>
    <button type="submit">Gray</button>
</form>

<form action="{{'/photos/' . $id }}" method="GET">
    <input name="action" value="blur" hidden>
    <button type="submit">Blur</button>
    <input max="300" min="10" name="size" step="10" type="range" value="100">
</form>

<form action="{{ route('photos.destroy', $id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit"> Delete</button>
</form>

</body>
</html>
