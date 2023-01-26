<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Website</title>
  @vite('resources/css/app.css')
</head>

<body>
    @include('partials.navBar')
    @yield('content')
    @include('partials.footer')

</body>
</html>
