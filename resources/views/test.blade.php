<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test视图</title>
</head>
<body>
<!-- 2020.08.22 -->
<!-- 1、url方式访问 -->
    <a href="{{url('user/666')}}">url路由</a> <!--id:666-->
<!-- 2、别名方式 -->
    <!--1、路由没有参数时: 通过路由定义后访问http://lv7.test/test，然后点击页面路由命名1，在页面中就会打印 用户ID:6 ,并且URL路由跳转到http://lv7.test/user3 -->
    <a href="{{route('user.profile')}}">路由命名1</a> 
    <!-- 2、通过数组传递参数 -->
    <a href="{{route('user.profile',['id'=>100])}}">路由命名2</a>  <!--返回 用户ID:100-->
    <!-- 3、简化路由参数 -->
    <a href="{{route('user.profile',[200])}}">路由命名3</a>

   
</body>
</html>
