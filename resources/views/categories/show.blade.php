@extends('layouts.app')

@section('title', $category['name'] . 'のある店舗' . number_format($total) . '店｜ゲーセンマップ')
@section('description', $category['summary'] . '全国' . number_format($total) . '店を都道府県別にまとめました。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'ゲーセンマップ', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => '遊びの種類から探す', 'item' => route('categories.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $category['name'], 'item' => route('categories.show', $categorySlug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a><span class="text-muted mx-1">/</span>
    <a href="{{ route('categories.index') }}">遊びの種類から探す</a><span class="text-muted mx-1">/</span>
    <span class="text-muted">{{ $category['name'] }}</span>
  </nav>

  <h1 class="h3">{{ $category['name'] }}のある店舗</h1>
  <p class="text-muted">{{ $category['summary'] }}全国{{ number_format($total) }}店を掲載しています。</p>

  <h2 class="h5 mt-4">都道府県から選ぶ</h2>
  <div class="row row-cols-2 row-cols-md-4 g-2">
    @foreach($byPrefecture as $prefecture => $count)
      @php($prefectureSlug = \App\Models\GameCenter::slugForPrefecture($prefecture))
      @if($prefectureSlug)
        <div class="col">
          <a class="d-block border rounded p-2 text-decoration-none"
             href="{{ route('areas.category', [$prefectureSlug, $categorySlug]) }}">
            {{ $prefecture }} <span class="text-muted small">{{ number_format($count) }}店</span>
          </a>
        </div>
      @endif
    @endforeach
  </div>

  <p class="text-muted small mt-4">
    分類は各チェーンの公式サイトが公表している機種やブランドに基づいています。
    公表されていない店舗はどのカテゴリにも入れていません。
  </p>
</div>
@endsection
