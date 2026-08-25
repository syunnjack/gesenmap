@extends('layouts.app')

@section('title', '遊びの種類から探す｜ゲーセンマップ')
@section('description', 'アーケードゲーム・プライズ（クレーンゲーム）・カプセルトイ・プリクラなど、遊びの種類から全国のゲームセンターを探せます。分類は各チェーンの公式サイトが公表している機種やブランドに基づいています。')

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a><span class="text-muted mx-1">/</span>
    <span class="text-muted">遊びの種類から探す</span>
  </nav>

  <h1 class="h3">遊びの種類から探す</h1>
  <p class="text-muted">
    店舗を遊びの種類で分けています。分類は<strong>各チェーンの公式サイトが公表している機種やブランド</strong>に基づくもので、
    公表されていない店舗は「未確認」として、どのカテゴリにも入れていません。
  </p>

  <div class="row row-cols-1 row-cols-md-2 g-3">
    @foreach($categories as $slug => $category)
      <div class="col">
        <div class="card border h-100 p-3">
          <h2 class="h5 mb-1">
            <a href="{{ route('categories.show', $slug) }}">{{ $category['name'] }}</a>
          </h2>
          <p class="text-muted small mb-2">{{ $category['summary'] }}</p>
          <p class="mb-0"><strong>{{ number_format($counts[$slug] ?? 0) }}</strong> 店</p>
        </div>
      </div>
    @endforeach
  </div>

  @if($unknown > 0)
    <p class="text-muted small mt-4">
      このほかに、置いている機種が公式サイトで公表されていない店舗が {{ number_format($unknown) }} 店あります。
      推測で分類はせず、店舗ページに「未確認」と表示しています。
    </p>
  @endif
</div>
@endsection
