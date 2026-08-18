<!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="google-site-verification" content="e3Ao6OPxcgvKlByZF5HGL1rCzK6ckw_lrKv9Uejcogc" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ゲーセンマップ | みんなで作るゲームセンター口コミ地図')</title>
    <meta name="description" content="@yield('description', '全国のゲームセンターを地図から探せる、利用者投稿型のポータルサイトです。プライズ・プリクラ・カプセルトイの有無や、実際に行った人の口コミ・応援投票を確認できます。')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:site_name" content="ゲーセンマップ">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'ゲーセンマップ | みんなで作るゲームセンター口コミ地図')">
    <meta property="og:description" content="@yield('description', '全国のゲームセンターを地図から探せる、利用者投稿型のポータルサイトです。プライズ・プリクラ・カプセルトイの有無や、実際に行った人の口コミ・応援投票を確認できます。')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="ja_JP">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title', 'ゲーセンマップ | みんなで作るゲームセンター口コミ地図')">
    <meta name="twitter:description" content="@yield('description', '全国のゲームセンターを地図から探せる、利用者投稿型のポータルサイトです。プライズ・プリクラ・カプセルトイの有無や、実際に行った人の口コミ・応援投票を確認できます。')">

    <link rel="icon" href="/favicon.ico" sizes="any">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
      .card { padding: 12px; }
    </style>

    @stack('structured-data')
  @if(config('services.ga4.id'))
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.id') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('services.ga4.id') }}');
  </script>
  @endif
</head>
<body>
    <nav class="navbar navbar-dark bg-dark text-white p-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="h4 mb-0 text-white text-decoration-none">ゲーセンマップ</a>
            <a href="{{ route('areas.index') }}" class="text-white small text-decoration-none">都道府県から探す</a>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer class="container text-center text-muted small py-4 mt-4 border-top">
        <a href="{{ route('areas.index') }}" class="text-muted me-3">都道府県から探す</a>
        <a href="{{ route('about') }}" class="text-muted">このサイトについて</a>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @yield('scripts')
</body>
</html>
