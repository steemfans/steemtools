<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <title>SteemTools - @yield('title')</title>
        <link rel="apple-touch-icon" sizes="57x57" href="/fav/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/fav/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/fav/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/fav/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/fav/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/fav/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/fav/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/fav/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/fav/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/fav/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/fav/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/fav/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/fav/favicon-16x16.png">
        <link rel="manifest" href="/fav/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/fav/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.min.css">
        @yield('customcss')
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-7536831447654223",
                enable_page_level_ads: true
            });
        </script>
    </head>
    <body>
        <div class="row" style="width: 100%; margin:0;">
            @yield('body')
        <div>
        <script src="/js/jquery-3.3.1.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        @yield('customjs')
    </body>
</html>
