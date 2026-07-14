@extends('layouts.app')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'ゲーセンマップ',
    'url' => url('/'),
    'description' => '全国のゲームセンターを地図から探せる、利用者投稿型のポータルサイト。',
    'inLanguage' => 'ja',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if ($locations->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'ゲームセンター一覧',
    'itemListElement' => $locations->values()->map(function ($loc, $i) {
        $entry = [
            '@type' => 'LocalBusiness',
            'name' => $loc->name,
        ];
        if ($loc->reviews->count() > 0) {
            $entry['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($loc->reviews->avg('rating'), 1),
                'reviewCount' => $loc->reviews->count(),
            ];
        }
        return ['@type' => 'ListItem', 'position' => $i + 1, 'item' => $entry];
    })->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endpush

@section('content')
<div class="container">
  <h1>🎮 みんなで作るゲームセンターマップ</h1>
  <p class="text-muted">
    ゲーセンマップは、利用者の投稿でできているゲームセンター情報サイトです。
    地図をクリックして新しいお店を登録したり、プライズ・プリクラ・カプセルトイの有無で絞り込んだり、
    実際に行った人の口コミ・応援投票を確認できます。
  </p>

  @if(session('submit_success'))
    <div class="alert alert-success">登録ありがとうございます！</div>
  @endif
  @if(session('vote_message'))
    <div class="alert alert-info">{{ session('vote_message') }}</div>
  @endif

  <form method="GET" action="{{ route('home') }}" class="mb-3">
    <span class="small text-muted">絞り込み:</span>
    <label class="me-2"><input type="checkbox" name="prize" value="1" {{ request('prize') ? 'checked' : '' }}> 🎁 プライズ</label>
    <label class="me-2"><input type="checkbox" name="purikura" value="1" {{ request('purikura') ? 'checked' : '' }}> 📸 プリクラ</label>
    <label class="me-2"><input type="checkbox" name="capsule" value="1" {{ request('capsule') ? 'checked' : '' }}> 🧸 カプセルトイ</label>
    <button type="submit" class="btn btn-sm btn-outline-primary">絞り込む</button>
  </form>

  <div id="map" style="height:400px;" class="mb-4"></div>

  <details class="mb-4">
    <summary>📍 新しいゲームセンターを登録する（地図をクリックして位置を指定してください）</summary>
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
      <label class="me-2"><input type="checkbox" name="has_prize"> 🎁 プライズあり</label>
      <label class="me-2"><input type="checkbox" name="has_purikura"> 📸 プリクラあり</label>
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

  <h2 class="h5">ゲームセンター一覧（{{ $locations->count() }}件）</h2>
  @forelse($locations as $loc)
    <div class="card border mb-3">
      <strong>{{ $loc->name }}</strong>
      @if($loc->description)
        <p class="mb-1">{{ $loc->description }}</p>
      @endif
      <p class="mb-1">
        @if($loc->has_prize) <span class="badge bg-info text-dark">🎁 プライズ</span> @endif
        @if($loc->has_purikura) <span class="badge bg-info text-dark">📸 プリクラ</span> @endif
        @if($loc->has_capsule) <span class="badge bg-info text-dark">🧸 カプセルトイ</span> @endif
      </p>

      <form method="POST" action="{{ route('game-centers.vote', $loc) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success">👍 推す</button>
      </form>
      <span class="small text-muted">応援投票: {{ $loc->votes_count }}件</span>

      <div class="mt-2">
        @if($loc->reviews->isEmpty())
          <p class="text-muted small mb-1">まだ口コミがありません。最初の口コミを投稿してみませんか？</p>
        @else
          <p class="fw-bold small mb-1">口コミ {{ $loc->reviews->count() }}件（平均★{{ round($loc->reviews->avg('rating'), 1) }}）</p>
          @foreach($loc->reviews as $review)
            <div class="border rounded p-2 mb-2 small">
              <div>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                <strong>{{ $review->nickname }}</strong>
                <span class="text-muted">{{ $review->created_at->format('Y-m-d') }}</span>
              </div>
              <div>{{ $review->comment }}</div>
            </div>
          @endforeach
        @endif

        <details class="mt-1">
          <summary class="small">口コミを投稿する</summary>
          <form method="POST" action="{{ route('reviews.store', $loc) }}" class="mt-2">
            @csrf
            <div style="position:absolute;left:-9999px;" aria-hidden="true">
              <label>ウェブサイト <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>
            <div class="mb-2">
              <label class="form-label small">ニックネーム（任意）</label>
              <input type="text" name="nickname" class="form-control form-control-sm" maxlength="30">
            </div>
            <div class="mb-2">
              <label class="form-label small">評価</label>
              <select name="rating" class="form-select form-select-sm" required>
                <option value="">選択してください</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★☆</option>
                <option value="3">★★★☆☆</option>
                <option value="2">★★☆☆☆</option>
                <option value="1">★☆☆☆☆</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label small">口コミ</label>
              <textarea name="comment" class="form-control form-control-sm" rows="3" minlength="5" maxlength="1000" required></textarea>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-primary">投稿する</button>
          </form>
        </details>
      </div>
    </div>
  @empty
    <p class="text-muted">まだゲームセンターが登録されていません。最初の1件を登録してみませんか？</p>
  @endforelse
</div>
@endsection

@section('scripts')
<script>
  var map = L.map('map').setView([35.6812, 139.7671], 11); // 東京駅付近
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

  @foreach($locations as $loc)
    L.marker([{{ $loc->lat }}, {{ $loc->lng }}])
      .addTo(map)
      .bindPopup(`
        <b>{{ $loc->name }}</b><br>
        @if($loc->has_prize) 🎁 プライズあり<br>@endif
        @if($loc->has_purikura) 📸 プリクラあり<br>@endif
        @if($loc->has_capsule) 🧸 カプセルトイあり<br>@endif
      `);
  @endforeach
</script>
@endsection
