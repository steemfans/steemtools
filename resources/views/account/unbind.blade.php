@extends('layouts.app')

@section('title', '解绑')

@section('customcss')
<style>
</style>
@endsection

@section('body')
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div style="text-align: center; margin-top: 20%;">
            <div><img style="width: 20%;" src="{{ $wx_userinfo['avatar'] }}"></div>
            <h4>你好, {{ $wx_userinfo['name'] }}</h4>
            <div style="margin: 20px 0;">
                <p style="color: red;">{{ $username }} 的授权码 SteemTools 已删除</p>
                <p style="color: red;">你还需要前往 SteemConnect 取消授权</p>
            </div>
            <a href="{{ $unbind_url }}" class="btn btn-primary">去 SteemConnect 解绑</a>
        </div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){

})
</script>
@endsection
