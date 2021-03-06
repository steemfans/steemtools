@extends('layouts.app')

@section('title', '回复详情')

@section('customcss')
<link rel="stylesheet" href="/css/markdown/sspai.css">
<style>
    body {
        background-color: #fefefe !important;
        word-break: break-all;
    }
    .post-content img {
        width: 100% !important;
    }
    .post-title {
        font-size: 22px;
        line-height: 1.4;
        margin-bottom: 14px;
    }
    .post-origin {
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
    .markdown-body {
        box-sizing: border-box;
        width: 100%;
        margin: 0 auto 14px;
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
        <div class="post-origin">
            <a href="{{ $parent_link }}">查看原文 &gt;&gt;</a>
        </div>
        <div class="post-sub-title">
            <span class="author">{{ $author }}</span>
            <span class="time"></span>
        </div>
        <div class="markdown-body">{!! $body !!}</div>
        <div class="post-reply-box">
        </div>
    </div>
    @else
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        未找到相应内容
    </div>
    @endif
@endsection

@section('customjs')
<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
$(function(){
    const date = new Date(Number('{{ $created }}'));
    const Y = date.getFullYear() + '-';
    const M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    const D = (date.getDate() < 10 ? '0'+(date.getDate()) : date.getDate()) + ' ';
    const h = (date.getHours() < 10 ? '0'+(date.getHours()) : date.getHours()) + ':';
    const m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes()) + ':';
    const s = date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds();
    $('.time').html(Y+M+D+h+m+s);

    @if (isset($wx_config))
    // wx jssdk
    wx.config({!! $wx_config !!});
    wx.ready(function () {
        wx.updateAppMessageShareData({
            title: '{{ $share_info["title"] }}',
            desc: '{{ $share_info["desc"] }}',
            link: '{{ $share_info["link"] }}',
            imgUrl: '{{ $share_info["img_url"] }}',
            success: function () {
            }
        });
        wx.updateTimelineShareData({
            title: '{{ $share_info["title"] }}',
            link: '{{ $share_info["link"] }}',
            imgUrl: '{{ $share_info["img_url"] }}',
            success: function () {
            }
        });
    });
    @endif
});
</script>
@endsection
