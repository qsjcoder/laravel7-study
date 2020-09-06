<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>larave + vue 表单请求</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
    <div id="app">
        <div class="container">
            <form>
                {{-- 需要编写FileUploadComponent.vue组件来配合 --}}
                {{-- 控制台报错组件注册尚未成功 --}}
                <fileupload-component></fileupload-component>  
                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
