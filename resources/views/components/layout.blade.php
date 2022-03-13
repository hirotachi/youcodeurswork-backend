<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link
        href='https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@4cac1a6/css/all.css'
        rel='stylesheet'
        type='text/css' crossorigin='anonymous'
    />
    <link rel="stylesheet" href="{{asset("css/app.css")}}">
    @stack("styles")
    <title>YouCodeursWork - {{$title ?? "Home"}}</title>
</head>
<body>
{{$slot}}
@stack("scripts")
</body>
</html>
