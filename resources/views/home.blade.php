@extends('layouts.app')

@section('content')
<div class="container">
  <h2>🎮 ゲームセンター一覧</h2>
  @foreach($locations as $loc)
    <div class="card" style="margin-bottom:12px;">
      <strong>{{ $loc->name }}</strong><br>
      {{ $loc->description }}<br>
      評価：{{ str_repeat('★', $loc->rating ?? 0) }}<br>
      @if($loc->has_prize) 🎁 @endif @if($loc->has_purikura) 📸 @endif @if($loc->has_capsule) 🧸 @endif<br>
      <button onclick="vote({{ $loc->id }})">👍 推す</button>
      <span id="vote-count-{{ $loc->id }}">投票数: {{ $voteCounts[$loc->id] ?? 0 }}</span>
    </div>
  @endforeach

  <h3>📍 地図</h3>
  <div id="map" style="height:400px;"></div>
  <script>
    var map = L.map('map').setView([35.17, 136.88], 11);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    @foreach($locations as $loc)
      L.marker([{{ $loc->lat }}, {{ $loc->lng }}]).addTo(map).bindPopup("{{ $loc->name }}");
    @endforeach
  </script>

  <h3>📢 X速報</h3>
  <div>
    <ul>
      <li>🏬 新着店舗 → namco公式</li>
      <li>🎁 プライズ → UFOくじ</li>
      <li>🕹️ レトロ系 → 全日本アミューズメント施設業協会</li>
    </ul>
  </div>
</div>
@endsection