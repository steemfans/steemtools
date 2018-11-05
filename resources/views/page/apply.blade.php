@extends('layouts.app')

@section('title', '申请加入')

@section('body')
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <h4 style="text-align: center; margin: 20px 0;">申请加入应用和工具列表</h4>
        <div style="margin: 30px 0;">
            <form method="post" action="{{ route('page_apply') }}">
                @csrf
                <div class="form-group">
                    <label for="sitename">应用或工具的名称</label>
                    <input type="text" class="form-control" id="sitename" name="sitename" placeholder="例如: Steem编辑器" value="{{ $input['sitename'] }}">
                </div>
                <div class="form-group">
                    <label for="keyword">关键词</label>
                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="例如: steemeditor" value="{{ $input['keyword'] }}">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="例如: https://steemeditor.com" value="{{ $input['url'] }}">
                </div>
                <div class="form-group">
                    <label for="descp">作者信息</label>
                    <input type="text" class="form-control" id="descp" name="descp" placeholder="例如: @ety001" value="{{ $input['descp'] }}">
                </div>
                <button type="submit" class="btn btn-primary">提交你的应用或工具</button>
            </form>
        </div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){
})
</script>
@endsection
