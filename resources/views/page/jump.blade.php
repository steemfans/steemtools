<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SteemTools</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.min.css">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-7536831447654223",
                enable_page_level_ads: true
            });
        </script>
    </head>
    <body>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
                @if ($url)
                <div style="text-align: center; margin-top: 20%;">
                    <div style="font-size: 16px;">正在准备跳转至</div>
                    <div style="font-size: 36px; margin-top: 20px;">{{ $sitename }}</div>
                    <div style="margin-top: 30px;"><span id="sec">10</span> 秒</div>
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
        </div>

        <script src="/js/jquery-3.3.1.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>
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
    </body>
</html>
