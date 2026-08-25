@extends('layouts.app')

@section('title', $prefecture . 'で' . $category['name'] . 'がある店' . number_format($shops->count()) . '店｜ゲーセンマップ')
@section('description', $prefecture . 'で' . $category['name'] . 'を置いている店舗' . number_format($shops->count()) . '店を市区町村別にまとめました。分類は各チェーンの公式サイトが公表している内容に基づいています。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'ゲーセンマップ', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => '都道府県から探す', 'item' => route('areas.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $prefecture, 'item' => route('areas.show', $prefectureSlug)],
        ['@type' => 'ListItem', 'position' => 4, 'name' => $category['name'], 'item' => route('areas.category', [$prefectureSlug, $categorySlug])],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a><span class="text-muted mx-1">/</span>
    <a href="{{ route('areas.index') }}">都道府県から探す</a><span class="text-muted mx-1">/</span>
    <a href="{{ route('areas.show', $prefectureSlug) }}">{{ $prefecture }}</a><span class="text-muted mx-1">/</span>
    <span class="text-muted">{{ $category['name'] }}</span>
  </nav>

  <h1 class="h3">{{ $prefecture }}で{{ $category['name'] }}がある店</h1>
  <p class="text-muted">
    {{ number_format($shops->count()) }}店を掲載しています。{{ $category['summary'] }}
  </p>

  <p class="small mb-4">
    <a href="{{ route('areas.show', $prefectureSlug) }}">{{ $prefecture }}の全店舗を見る</a>
    <span class="text-muted mx-1">/</span>
    <a href="{{ route('categories.show', $categorySlug) }}">全国の{{ $category['name'] }}を見る</a>
  </p>

  @foreach($byCity as $city => $cityShops)
    <h2 class="h5 mt-4">{{ $city }}（{{ $cityShops->count() }}店）</h2>
    <ul class="list-unstyled">
      @foreach($cityShops as $shop)
        <li class="border-bottom py-2">
          @if($shop->slug)
            <a href="{{ route('game-centers.show', $shop->slug) }}">{{ $shop->name }}</a>
          @else
            {{ $shop->name }}
          @endif
          @if($shop->chain)<span class="text-muted small ms-2">{{ $shop->chain }}</span>@endif
          @if($shop->address)<div class="text-muted small">{{ $shop->address }}</div>@endif
        </li>
      @endforeach
    </ul>
  @endforeach

  <p class="text-muted small mt-4">
    分類は各チェーンの公式サイトが公表している機種やブランドに基づいています。
    最新の設置状況は各店舗の公式ページでご確認ください。
  </p>
</div>
@endsection
