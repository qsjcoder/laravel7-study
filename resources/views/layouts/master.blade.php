<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','首页')</title>
</head>
<body>
    {{-- 模板继承&组件引入  --}}
    <div class="container">
        @yield('content')
    </div>
    @section('footerScript')
        父类:这里可以被直接显示
        <script src="{{asset('js/app.js')}}"></script>
    @show
</body>
</html>