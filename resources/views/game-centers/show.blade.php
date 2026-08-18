@extends('layouts.app')

@php
    $hoursSummary = $gameCenter->hours_summary;
@endphp

@section('title', $gameCenter->name . '｜' . ($gameCenter->city ?: $gameCenter->prefecture) . 'のゲームセンター | ゲーセンマップ')
@section('description', $gameCenter->name . '（' . $gameCenter->address . '）の営業時間・設置しているゲームの種類を、公式サイトの情報をもとにまとめています。行った人の口コミも読めます。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $gameCenter->name,
    'address' => $gameCenter->address,
    'telephone' => $gameCenter->tel,
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => $gameCenter->lat,
        'longitude' => $gameCenter->lng,
    ],
    'url' => route('game-centers.show', $gameCenter->slug),
    'aggregateRating' => $gameCenter->reviews->isNotEmpty() ? [
        '@type' => 'AggregateRating',
        'ratingValue' => round($gameCenter->reviews->avg('rating'), 1),
        'reviewCount' => $gameCenter->reviews->count(),
    ] : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'ゲーセンマップ', 'item' => url('/')],
        $gameCenter->prefecture_slug ? [
            '@type' => 'ListItem', 'position' => 2, 'name' => $gameCenter->prefecture,
            'item' => route('areas.show', $gameCenter->prefecture_slug),
        ] : null,
        ['@type' => 'ListItem', 'position' => 3, 'name' => $gameCenter->name,
         'item' => route('game-centers.show', $gameCenter->slug)],
    ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <nav aria-label="パンくず" class="small mb-3">
    <a href="{{ url('/') }}">トップ</a>
    @if($gameCenter->prefecture_slug)
      <span class="text-muted mx-1">/</span>
      <a href="{{ route('areas.show', $gameCenter->prefecture_slug) }}">{{ $gameCenter->prefecture }}</a>
    @endif
    <span class="text-muted mx-1">/</span><span class="text-muted">{{ $gameCenter->name }}</span>
  </nav>

  <h1 class="h3">{{ $gameCenter->name }}</h1>
  @if($gameCenter->chain)
    <p class="text-muted small mb-3">{{ $gameCenter->chain }}の店舗</p>
  @endif

  <div class="card border mb-3">
    <dl class="row mb-0">
      @if($gameCenter->address)
        <dt class="col-4 col-sm-3 small text-muted">住所</dt>
        <dd class="col-8 col-sm-9">
          @if($gameCenter->postal_code)〒{{ $gameCenter->postal_code }} @endif{{ $gameCenter->address }}
        </dd>
      @endif
      @if($hoursSummary)
        <dt class="col-4 col-sm-3 small text-muted">営業時間</dt>
        <dd class="col-8 col-sm-9">{{ $hoursSummary }}</dd>
      @elseif($gameCenter->hours)
        <dt class="col-4 col-sm-3 small text-muted">営業時間</dt>
        <dd class="col-8 col-sm-9">
          @foreach($gameCenter->hours as $row)
            <div class="small">{{ is_array($row['days'] ?? null) ? implode('・', $row['days']) : ($row['days'] ?? '') }}
              {{ $row['opens'] ?? '' }}〜{{ $row['closes'] ?? '' }}</div>
          @endforeach
        </dd>
      @endif
      @if($gameCenter->tel)
        <dt class="col-4 col-sm-3 small text-muted">電話</dt>
        <dd class="col-8 col-sm-9">{{ $gameCenter->tel }}</dd>
      @endif
    </dl>
  </div>

  @if($gameCenter->games)
    <h2 class="h5">設置しているゲームの種類</h2>
    <p class="d-flex flex-wrap gap-2">
      @foreach($gameCenter->games as $game)
        <span class="badge bg-light text-dark border">{{ $game }}</span>
      @endforeach
    </p>
  @endif

  @if($gameCenter->features)
    <h2 class="h5">お店の特徴</h2>
    <p class="d-flex flex-wrap gap-2">
      @foreach($gameCenter->features as $feature)
        <span class="badge bg-light text-dark border">{{ $feature }}</span>
      @endforeach
    </p>
  @endif

  @if($gameCenter->description)
    <p>{{ $gameCenter->description }}</p>
  @endif

  <div id="map" style="height:320px;" class="my-3"></div>

  @if($gameCenter->source_url)
    <p class="text-muted small">
      出典：<a href="{{ $gameCenter->source_url }}" target="_blank" rel="nofollow noopener">{{ $gameCenter->source_label }}</a>
      （{{ optional($gameCenter->confirmed_on)->format('Y年n月j日') }}時点）。
      営業時間や設置機種は変わることがあります。出かける前に公式ページで最新の情報をご確認ください。
    </p>
  @else
    <p class="text-muted small">この店舗は利用者の投稿です。内容は投稿時点のもので、当サイトでは確認していません。</p>
  @endif

  <div class="my-3">
    <a href="https://www.google.com/maps/search/?api=1&query={{ $gameCenter->lat }},{{ $gameCenter->lng }}"
       target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">地図アプリで開く</a>
    <form method="POST" action="{{ route('game-centers.vote', $gameCenter) }}" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-sm btn-outline-success">👍 推す（{{ $gameCenter->votes_count }}）</button>
    </form>
  </div>

  <h2 class="h5">口コミ</h2>
  @if($gameCenter->reviews->isEmpty())
    <p class="text-muted small">まだ口コミがありません。行ったことのある方は、最初の1件を書いてみませんか。</p>
  @else
    <p class="small fw-bold">{{ $gameCenter->reviews->count() }}件（平均★{{ round($gameCenter->reviews->avg('rating'), 1) }}）</p>
    @foreach($gameCenter->reviews as $review)
      <div class="border rounded p-2 mb-2 small">
        <div>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
          <strong>{{ $review->nickname }}</strong>
          <span class="text-muted">{{ $review->created_at->format('Y-m-d') }}</span>
        </div>
        <div>{{ $review->comment }}</div>
      </div>
    @endforeach
  @endif

  <details class="mb-4">
    <summary class="small">口コミを投稿する</summary>
    <form method="POST" action="{{ route('reviews.store', $gameCenter) }}" class="mt-2">
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

  @if($nearby->isNotEmpty())
    <h2 class="h5">近くのゲームセンター</h2>
    <p class="d-flex flex-wrap gap-2">
      @foreach($nearby as $other)
        <a href="{{ $other->slug ? route('game-centers.show', $other->slug) : url('/') }}"
           class="btn btn-sm btn-outline-secondary">{{ $other->name }}</a>
      @endforeach
    </p>
  @endif

  @if($gameCenter->prefecture_slug)
    <p><a href="{{ route('areas.show', $gameCenter->prefecture_slug) }}">{{ $gameCenter->prefecture }}のゲームセンター一覧へ →</a></p>
  @endif
</div>
@endsection

@section('scripts')
<script>
  var map = L.map('map').setView([{{ $gameCenter->lat }}, {{ $gameCenter->lng }}], 16);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  L.marker([{{ $gameCenter->lat }}, {{ $gameCenter->lng }}]).addTo(map)
    .bindPopup(@json($gameCenter->name));
</script>
@endsection
