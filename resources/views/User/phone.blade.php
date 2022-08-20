<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Enter</title>
</head>
<body>

<form action="/login" method="POST">
    @csrf
    <p>
        سلام :)
        <br>
        شماره موبایل خود را وارد کنید
    </p>

    @if (session('status') == 'Expired')
        <div style="color:red">
            کد منقضی شده است. دوباره تلاش کنید.
        </div>
    @endif
    @error('phone')
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    @enderror
    <input name="phone" pattern="09[0-9]{9}" placeholder="09xxxxxxxxx" required type="tel">
    <button type="submit">
        تایید
    </button>
</form>

</body>
</html>
