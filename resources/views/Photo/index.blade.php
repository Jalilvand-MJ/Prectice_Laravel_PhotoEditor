<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Photos</title>
</head>
<body>
<header>
    <a href="/logout">LogOut</a>
    <a href="/photos/create">NEW</a>
</header>
<h1>Your Photos</h1>
@forelse ($data as $item)
    <li>
        <a href={{'/photos/' . $item->id . '/edit'}}>{{ $item->id }}</a>
    </li>
@empty
    <p>No Photos :/</p>
@endforelse
</body>
</html>
