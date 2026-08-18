@extends('layouts.app')

@section('title', '都道府県から探す｜全国のゲームセンター' . number_format($total) . '店 | ゲーセンマップ')
@section('description', '全国' . number_format($total) . '店のゲームセンターを都道府県別にまとめています。住所・営業時間・設置しているゲームの種類は各チェーンの公式サイトで確認したものです。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'ゲーセンマップ', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => '都道府県から探す', 'item' => route('areas.index')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a><span class="text-muted mx-1">/</span><span class="text-muted">都道府県から探す</span>
  </nav>

  <h1 class="h3">都道府県から探す</h1>
  <p class="text-muted">全国{{ number_format($total) }}店を掲載しています。都道府県を選ぶと、市区町村別の一覧と地図を見られます。</p>

  <div class="row row-cols-2 row-cols-md-4 g-2">
    @foreach($prefectures as $area)
      <div class="col">
        <a href="{{ route('areas.show', $area['slug']) }}" class="btn btn-outline-secondary w-100 text-start">
          {{ $area['prefecture'] }} <span class="text-muted small">{{ $area['total'] }}店</span>
        </a>
      </div>
    @endforeach
  </div>
</div>
@endsection
