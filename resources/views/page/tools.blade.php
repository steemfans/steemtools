@extends('layouts.app')

@section('title', '营销小工具')

@section('body')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div style="text-align: center;"><h3>论坛营销小工具</h3></div>
        <div class="list-group" style="margin:0 20px;">
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">论坛帖子赞数统计小工具</h5>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">3CNY/月</span></p>
                <small>点击去支付</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">自动回复小工具</h5>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">5CNY/月</span></p>
                <small>点击去支付</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">重要消息提醒服务</h5>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">10CNY/月</span></p>
                <small>点击去支付</small>
            </a>
        </div>
        <nav aria-label="Page navigation example" style="margin:0 20px;">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">上一页</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">下一页</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('customjs')
<script>
$(function(){
})
</script>
@endsection
