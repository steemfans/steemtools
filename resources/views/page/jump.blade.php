@extends('layouts.app')

@section('title', 'Landing')

@section('body')
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        @if ($url)
        <div style="text-align: center; margin-top: 20%;">
            <div style="font-size: 16px;">正在准备跳转至</div>
            <div style="font-size: 36px; margin-top: 20px;">{{ $sitename }}</div>
            <div style="margin-top: 30px;"><span id="sec">3</span> 秒</div>
            <div style="margin-top: 120px;">开发者</div>
            <div>{{ $text }}</div>
        </div>
        @else
        <div style="text-align: center; margin-top: 20%;">
            <div style="font-size: 16px;">你要访问的页面不存在</div>
            <div style="margin-top: 18px;"><a href="/">返回</a></div>
        </div>
        @endif
        <div></div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){
    var url = '{{ $url }}';
    if (url) {
        var sec = parseInt($('#sec').html());
        var interval = setInterval(function(){
            if (sec == 1) {
                clearInterval(interval);
                window.location = url;
                return;
            }
            $('#sec').html(--sec);
        }, 1000);
    }
})
</script>
@endsection
