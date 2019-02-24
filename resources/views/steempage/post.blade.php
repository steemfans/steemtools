@extends('layouts.app')

@section('title', isset($title) ? $title : '未找到')

@section('customcss')
<style>
    body {
        background-color: #fefefe !important;
    }
    .post {
        font-family: 'STXihei, Hiragino Sans GB, Droid Sans';
    }
    .post-content img {
        width: 100% !important;
    }
    .post-title {
        font-size: 22px;
        line-height: 1.4;
        margin-bottom: 14px;
    }
    .post-sub-title {
        margin-bottom: 14px;
    }
    .post-sub-title .author {
    }
    .post-sub-title .time {
        padding-left: 18px;
        color: rgba(0,0,0,0.4);
    }
    .post-content {
        font-size: 18px;
        margin-bottom: 14px;
    }
    .post-reply-box {
        margin-bottom: 14px;
    }
</style>
@endsection

@section('body')
    @if (isset($title))
    <div class="post col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div class="post-title">{{ $title }}</div>
        <div class="post-sub-title">
            <span class="author">{{ $author }}</span>
            <span class="time">{{ $created }}</span>
        </div>
        <div class="post-content">{!! $body !!}</div>
        <div class="post-reply-box">
        </div>
    </div>
    @else
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        未找到相应文章
    </div>
    @endif
@endsection

@section('customjs')
<script>
$(function(){
})
</script>
@endsection
