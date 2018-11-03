@extends('layouts.app')

@section('title', '账号列表')

@section('customcss')
<style>
    .btn-custom {
        margin-left: 20px;
    }
    .alert {
        margin-top: 10px;
    }
</style>
@endsection

@section('body')
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        @if (session('status1'))
            <div class="alert alert-success">
                {{ session('status1') }}
            </div>
        @endif
        @if (session('status0'))
            <div class="alert alert-danger">
                {{ session('status0') }}
            </div>
        @endif
        <div style="text-align: center; margin-top: 15%;">
            <div><img style="width: 20%;" src="{{ $wx_userinfo['avatar'] }}"></div>
            <h4>你好, {{ $wx_userinfo['name'] }}</h4>
            @if ($wxusers->count()==0)
                <div style="margin: 20px 0;">
                    <p>你还没有绑定Steem账号</p>
                    <p>点击下面按钮开始绑定</p>
                </div>
            @else
                <div style="margin: 20px 0;">
                    <p>以下是你已绑定的Steem账号</p>
                </div>
                <div style="width: 90%; margin: 0 auto; text-align: left;">
                    <ul class="list-group">
                        @foreach ($wxusers as $u)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-xs-12">
                                    <span>{{ $u['username'] }}</span>
                                </div>
                                <div class="col-xs-12">
                                    <a href="{{ route('account_unbind', ['username' => $u['username']]) }}" class="pull-right btn-custom">解除绑定</a>
                                    <a href="{{ route('account_config', ['username' => $u['username']]) }}" class="pull-right btn-custom">配置</a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <a href="{{ $auth_url }}" class="btn btn-primary">绑定新的Steem账号</a>
        </div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){

})
</script>
@endsection
