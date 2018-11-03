@extends('layouts.app')

@section('title', '账号配置')

@section('customcss')
<style>
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
        <div style="text-align: center; margin-top: 20%;">
            <div><img style="width: 20%;" src="{{ $wx_userinfo['avatar'] }}"></div>
            <h4>你好, {{ $wx_userinfo['name'] }}</h4>
            <div style="margin: 20px 0;">
                <p>Steem 账号 <span style="color: green;">{{ $username }}</span> 的配置如下</p>
                <form method="post" action="{{ route('account_config', ['username' => $username]) }}">
                    @csrf
                    <div style="text-align: left; width: 65%; margin: 20px auto;">
                        <div class="form-check">
                            @if (isset($settings['replies']) && $settings['replies'] == 1)
                                <input type="checkbox" checked="checked" class="form-check-input" id="replies" name="settings[replies]">
                            @else
                                <input type="checkbox" class="form-check-input" id="replies" name="settings[replies]">
                            @endif
                            <label class="" for="replies">回复提醒</label>
                        </div>
                        <div class="form-check">
                            @if (isset($settings['transfer']) && $settings['transfer'] == 1)
                                <input type="checkbox" checked="checked" class="form-check-input" id="transfer" name="settings[transfer]">
                            @else
                                <input type="checkbox" class="form-check-input" id="transfer" name="settings[transfer]">
                            @endif
                            <label class="" for="transfer">收款提醒</label>
                        </div>
                        <div class="form-check">
                            @if (isset($settings['delegate_vesting_shares']) && $settings['delegate_vesting_shares'] == 1)
                                <input type="checkbox" checked="checked" class="form-check-input" id="delegate_vesting_shares" name="settings[delegate_vesting_shares]">
                            @else
                                <input type="checkbox" class="form-check-input" id="delegate_vesting_shares" name="settings[delegate_vesting_shares]">
                            @endif
                            <label class="" for="delegate_vesting_shares">代理SP提醒</label>
                        </div>
                        <div class="form-check">
                            @if (isset($settings['account_witness_vote']) && $settings['account_witness_vote'] == 1)
                                <input type="checkbox" checked="checked" class="form-check-input" id="account_witness_vote" name="settings[account_witness_vote]">
                            @else
                                <input type="checkbox" class="form-check-input" id="account_witness_vote" name="settings[account_witness_vote]">
                            @endif
                            <label class="" for="account_witness_vote">见证人得票提醒</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">保存配置</button>
                    <a href="{{ route('account_selector') }}" class="btn btn-warning">返回</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
<script>
$(function(){

})
</script>
@endsection
