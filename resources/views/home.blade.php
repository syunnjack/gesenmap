@extends('layouts.app')

@section('title', '全国のゲームセンター' . number_format($total) . '店｜ゲーセンマップ')
@section('description', '全国のゲームセンター' . number_format($total) . '店を地図と都道府県から探せます。住所・営業時間・設置しているゲームの種類は各チェーンの公式サイトで確認したものを掲載し、口コミは利用者の投稿です。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'ゲーセンマップ',
    'url' => url('/'),
    'description' => '全国のゲームセンターを地図と都道府県から探せる情報サイト。',
    'inLanguage' => 'ja',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <h1>🎮 全国のゲームセンターマップ</h1>
  <p class="text-muted">
    全国{{ number_format($total) }}店のゲームセンターを、地図と都道府県から探せます。
    住所・営業時間・設置しているゲームの種類は各チェーンの公式サイトで確認したもので、
    口コミと応援投票は利用者の投稿です。
  </p>

  @if(session('submit_success'))
    <div class="alert alert-success">登録ありがとうございます！</div>
  @endif
  @if(session('vote_message'))
    <div class="alert alert-info">{{ session('vote_message') }}</div>
  @endif

  <form method="GET" action="{{ route('home') }}" class="mb-3">
    <span class="small text-muted">絞り込み:</span>
    <label class="me-2"><input type="checkbox" name="prize" value="1" {{ request('prize') ? 'checked' : '' }}> 🎁 クレーンゲーム</label>
    <label class="me-2"><input type="checkbox" name="purikura" value="1" {{ request('purikura') ? 'checked' : '' }}> 📸 プリントシール</label>
    <label class="me-2"><input type="checkbox" name="capsule" value="1" {{ request('capsule') ? 'checked' : '' }}> 🧸 カプセルトイ</label>
    <button type="submit" class="btn btn-sm btn-outline-primary">絞り込む</button>
    <span class="small text-muted ms-2">該当 {{ number_format($locations->count()) }}店</span>
  </form>

  <div id="map" style="height:420px;" class="mb-4"></div>

  @if($prefectures->isNotEmpty())
    <h2 class="h5">都道府県から探す</h2>
    <p class="d-flex flex-wrap gap-2 mb-4">
      @foreach($prefectures as $area)
        <a href="{{ route('areas.show', $area['slug']) }}" class="btn btn-sm btn-outline-secondary">
          {{ $area['prefecture'] }} <span class="text-muted">{{ $area['total'] }}</span>
        </a>
      @endforeach
    </p>
  @endif

  <details class="mb-4">
    <summary>📍 載っていないお店を登録する（地図をクリックして位置を指定してください）</summary>
    <form method="POST" action="{{ route('game-centers.store') }}" class="mt-2">
      @csrf
      <div class="mb-2">
        <label class="form-label small">名称</label>
        <input type="text" name="name" class="form-control form-control-sm" required>
      </div>
      <div class="mb-2">
        <label class="form-label small">説明</label>
        <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
      </div>
      <label class="me-2"><input type="checkbox" name="has_prize"> 🎁 クレーンゲームあり</label>
      <label class="me-2"><input type="checkbox" name="has_purikura"> 📸 プリントシールあり</label>
      <label class="me-2"><input type="checkbox" name="has_capsule"> 🧸 カプセルトイあり</label><br>
      <div class="row mt-2">
        <div class="col-6">
          <label class="form-label small">緯度</label>
          <input type="text" id="lat" name="lat" class="form-control form-control-sm" readonly required>
        </div>
        <div class="col-6">
          <label class="form-label small">経度</label>
          <input type="text" id="lng" name="lng" class="form-control form-control-sm" readonly required>
        </div>
      </div>
      @if ($errors->any())
        <p class="text-danger small mt-2">{{ $errors->first() }}</p>
      @endif
      <button type="submit" class="btn btn-sm btn-primary mt-2">登録する</button>
    </form>
  </details>

  <h2 class="h5">掲載中のゲームセンター（{{ number_format($locations->count()) }}件）</h2>
  <p class="text-muted small">店名をクリックすると、住所・営業時間・設置しているゲームの種類と、口コミを見られます。</p>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
    @forelse($locations->take(120) as $loc)
      <div class="col">
        <div class="card border h-100">
          <strong>
            @if($loc->slug)
              <a href="{{ route('game-centers.show', $loc->slug) }}">{{ $loc->name }}</a>
            @else
              {{ $loc->name }}
            @endif
          </strong>
          @if($loc->address)
            <p class="small text-muted mb-1">{{ $loc->address }}</p>
          @elseif($loc->description)
            <p class="small mb-1">{{ $loc->description }}</p>
          @endif
          <p class="mb-1">
            @if($loc->has_prize) <span class="badge bg-info text-dark">🎁 クレーンゲーム</span> @endif
            @if($loc->has_purikura) <span class="badge bg-info text-dark">📸 プリントシール</span> @endif
            @if($loc->has_capsule) <span class="badge bg-info text-dark">🧸 カプセルトイ</span> @endif
          </p>
          <p class="small text-muted mb-0">応援投票 {{ $loc->votes_count }}件／口コミ {{ $loc->reviews_count }}件</p>
        </div>
      </div>
    @empty
      <p class="text-muted">条件に合うお店が見つかりませんでした。絞り込みを外してみてください。</p>
    @endforelse
  </div>

  @if($locations->count() > 120)
    <p class="mt-3">
      ここには{{ number_format($locations->count()) }}件のうち120件を表示しています。
      <a href="{{ route('areas.index') }}">都道府県から探す</a>と、地域ごとの一覧を見られます。
    </p>
  @endif
</div>
@endsection

@section('scripts')
@php
    $mapShops = $locations->map(fn ($loc) => [
        'name' => $loc->name,
        'lat' => $loc->lat,
        'lng' => $loc->lng,
        'url' => $loc->slug ? route('game-centers.show', $loc->slug) : null,
    ])->values();
@endphp
<script>
  var map = L.map('map').setView([35.6812, 139.7671], 10);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  var marker;
  map.on('click', function (e) {
    var lat = e.latlng.lat.toFixed(7);
    var lng = e.latlng.lng.toFixed(7);
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
    if (marker) {
      marker.setLatLng(e.latlng);
    } else {
      marker = L.marker(e.latlng).addTo(map);
    }
  });

  var shops = @json($mapShops);

  shops.forEach(function (shop) {
    var title = shop.url
      ? '<a href="' + shop.url + '">' + shop.name + '</a>'
      : shop.name;
    L.marker([shop.lat, shop.lng]).addTo(map).bindPopup('<b>' + title + '</b>');
  });
</script>
@endsection
