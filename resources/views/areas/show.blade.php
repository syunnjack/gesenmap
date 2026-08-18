@extends('layouts.app')

@section('title', $prefecture . 'のゲームセンター' . number_format($shops->count()) . '店｜ゲーセンマップ')
@section('description', $prefecture . 'のゲームセンター' . number_format($shops->count()) . '店を市区町村別にまとめました。住所・営業時間・設置しているゲームの種類は各チェーンの公式サイトで確認したものです。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'ゲーセンマップ', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => '都道府県から探す', 'item' => route('areas.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $prefecture, 'item' => route('areas.show', $prefectureSlug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a><span class="text-muted mx-1">/</span>
    <a href="{{ route('areas.index') }}">都道府県から探す</a><span class="text-muted mx-1">/</span>
    <span class="text-muted">{{ $prefecture }}</span>
  </nav>

  <h1 class="h3">{{ $prefecture }}のゲームセンター</h1>
  <p class="text-muted">
    {{ $prefecture }}に{{ number_format($shops->count()) }}店を掲載しています。
    店名をクリックすると、住所・営業時間・設置しているゲームの種類と口コミを見られます。
  </p>

  <div id="map" style="height:380px;" class="mb-4"></div>

  @foreach($byCity as $city => $cityShops)
    <h2 class="h5 mt-4">{{ $city }}（{{ $cityShops->count() }}店）</h2>
    <div class="row row-cols-1 row-cols-md-2 g-3">
      @foreach($cityShops as $shop)
        <div class="col">
          <div class="card border h-100">
            <strong>
              @if($shop->slug)
                <a href="{{ route('game-centers.show', $shop->slug) }}">{{ $shop->name }}</a>
              @else
                {{ $shop->name }}
              @endif
            </strong>
            @if($shop->address)<p class="small text-muted mb-1">{{ $shop->address }}</p>@endif
            @if($shop->hours_summary)<p class="small mb-1">営業時間 {{ $shop->hours_summary }}</p>@endif
            <p class="mb-0">
              @if($shop->has_prize) <span class="badge bg-info text-dark">🎁 クレーンゲーム</span> @endif
              @if($shop->has_purikura) <span class="badge bg-info text-dark">📸 プリントシール</span> @endif
              @if($shop->has_capsule) <span class="badge bg-info text-dark">🧸 カプセルトイ</span> @endif
            </p>
          </div>
        </div>
      @endforeach
    </div>
  @endforeach

  <h2 class="h5 mt-5">ほかの都道府県</h2>
  <p class="d-flex flex-wrap gap-2">
    @foreach($prefectures as $area)
      @if($area['slug'] !== $prefectureSlug)
        <a href="{{ route('areas.show', $area['slug']) }}" class="btn btn-sm btn-outline-secondary">
          {{ $area['prefecture'] }} <span class="text-muted">{{ $area['total'] }}</span>
        </a>
      @endif
    @endforeach
  </p>

  <p class="text-muted small">
    住所・営業時間・設置しているゲームの種類は、各チェーンの公式サイトで確認したものです。
    変更されている場合があるため、出かける前に各店舗ページの出典先をご確認ください。
  </p>
</div>
@endsection

@section('scripts')
@php
    $mapShops = $shops->map(fn ($shop) => [
        'name' => $shop->name,
        'lat' => $shop->lat,
        'lng' => $shop->lng,
        'url' => $shop->slug ? route('game-centers.show', $shop->slug) : null,
    ])->values();
@endphp
<script>
  var shops = @json($mapShops);

  var map = L.map('map');
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  var markers = shops.map(function (shop) {
    var title = shop.url ? '<a href="' + shop.url + '">' + shop.name + '</a>' : shop.name;
    return L.marker([shop.lat, shop.lng]).addTo(map).bindPopup('<b>' + title + '</b>');
  });

  if (markers.length) {
    map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
  } else {
    map.setView([35.6812, 139.7671], 10);
  }
</script>
@endsection
