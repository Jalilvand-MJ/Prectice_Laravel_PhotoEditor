<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Code Enter</title>
</head>
<body>

<form action="/verify" method="post">
    @csrf
    @if (session('status') == 'Invalid')
        <div style="color:red">
            کد وارد شده اشتباه است!
        </div>
    @endif
    <p>
        کد ارسال شده به
        {{$phone}}
        را وارد کنید
    </p>
    <input name="phone" type="hidden" value="{{$phone}}">
    <input name="code" pattern="[0-9]" placeholder="*****" required type="number">
    <button type="submit">
        تایید
    </button>
</form>
</body>
</html>
