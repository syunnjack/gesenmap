<!DOCTYPE html>
<html>
<head>
    <title>ゲームセンターを追加</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>ゲームセンターを追加</h2>
{{-- 絞り込みフォーム --}}
    <form method="GET" action="{{ url('/') }}">
        <label><input type="checkbox" name="prize" {{ request('prize') ? 'checked' : '' }}> プライズ</label>
        <label><input type="checkbox" name="purikura" {{ request('purikura') ? 'checked' : '' }}> プリクラ</label>
        <label><input type="checkbox" name="capsule" {{ request('capsule') ? 'checked' : '' }}> カプセルトイ</label>
        <button type="submit">絞り込み</button>
    </form>


    <div id="map"></div>
{{-- 投稿フォーム --}}

    <form method="POST" action="{{ url('/store') }}">
        @csrf
        <label>名称：<input type="text" name="name" required></label><br>
        <label>説明：<textarea name="description" required></textarea></label><br>
        <label>プライズあり：<input type="checkbox" name="has_prize"></label><br>
        <label>プリクラあり：<input type="checkbox" name="has_purikura"></label><br>
        <label>カプセルトイあり：<input type="checkbox" name="has_capsule"></label><br>
        <label>緯度：<input type="text" id="lat" name="lat" readonly></label><br>
        <label>経度：<input type="text" id="lng" name="lng" readonly></label><br>
        <button type="submit">登録する</button>
    </form>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([35.1709, 136.8815], 14); // 名古屋駅付近

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        var marker;

        map.on('click', function(e) {
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
        {{-- 投稿済み施設をピン表示 --}}

        @foreach ($locations as $location)
            L.marker([{{ $location->lat }}, {{ $location->lng }}])
              .addTo(map)
              .bindPopup(`
                <b>{{ $location->name }}</b><br>
                {{ $location->description }}<br>
                @if($location->has_prize) 🎁 プライズあり<br>@endif
                @if($location->has_purikura) 📸 プリクラあり<br>@endif
                @if($location->has_capsule) 🧸 カプセルトイあり<br>@endif
              `);
        @endforeach
    </script>
</body>
</html>