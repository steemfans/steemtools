@extends('layouts.app')

@section('title', '更多应用和工具')

@section('body')
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <h4 style="text-align: center; margin: 20px 0;">应用和工具</h4>
        <div class="list-group">
            @foreach($pages as $p)
            <a href="{{ route('page_jump', ['website' => $p->keyword]) }}" class="list-group-item list-group-item-action">
                {{ $p->sitename }}
                <span class="badge">>></span>
            </a>
            @endforeach
        </div>

        <div style="text-align: center; font-size: 16px; margin: 20px 0;">
            <a href="{{ route('page_apply') }}">申请加入</a>
        </div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){
})
</script>
@endsection
