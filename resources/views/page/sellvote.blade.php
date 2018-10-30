@extends('layouts.app')

@section('title', '卖赞商家列表')

@section('body')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div style="text-align: center;"><h3>卖赞商家列表</h3></div>
        <div class="list-group" style="margin:0 20px;">
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">卖家1</h5>
                    <small>信誉: <span class="badge badge-info">62 级</span></small>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">100Vests/1CNY</span></p>
                <small>点击去支付买赞</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">卖家2</h5>
                    <small>信誉: <span class="badge badge-info">45 级</span></small>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">105Vests/1CNY</span></p>
                <small>点击去支付买赞</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">卖家3</h5>
                    <small>信誉: <span class="badge badge-info">37 级</span></small>
                </div>
                <p class="mb-1">价格：<span class="badge badge-success">130Vests/1CNY</span></p>
                <small>点击去支付买赞</small>
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
