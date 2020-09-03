@extends('layouts.master')

@section('title','后台管理')

@section('content')
    这里只实现 yield('content')的内容，这里本身并不会被显示出来
    只有结束模板是'@@show'才会显示这里两个@是为了 防止被解释 
@endsection

@section('footerScript')
    @parent
    <script src="{{asset('js/layouts/child.js')}}"></script>
@endsection

{{-- 下面的include比上面的先输出？ 在最上面一行 --}}
@include('layouts/include1',['text'=>'测试文本'])
