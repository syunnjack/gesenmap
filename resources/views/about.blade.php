@extends('layouts.app')

@section('title', 'このサイトについて | ゲーセンマップ')
@section('description', 'ゲーセンマップの運営方針、掲載データの成り立ち、口コミ・投票の取り扱いについて説明しています。')

@section('content')
<div class="container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">ゲーセンマップ</a></li>
      <li class="breadcrumb-item active" aria-current="page">このサイトについて</li>
    </ol>
  </nav>

  <h1>このサイトについて</h1>

  <section class="mb-4">
    <h2 class="h5">サイトの目的</h2>
    <p>
      「ゲーセンマップ」は、全国のゲームセンターを地図から探せる、利用者投稿型のポータルサイトです。
      店舗情報（名称・場所・プライズ/プリクラ/カプセルトイの有無）はすべて利用者の皆さんの投稿によって作られています。
      あわせて、実際に行った人の口コミや応援投票も確認できます。
    </p>
  </section>

  <section class="mb-4">
    <h2 class="h5">掲載データについて</h2>
    <p>
      店舗情報は、どなたでもログイン不要でトップページの地図から投稿できます。投稿内容は運営による事前確認を行わず即時公開されます。
      情報の正確性（営業状況・設備の有無など）は投稿時点のものであり、最新の状況と異なる場合があります。ご来店前に念のため店舗へご確認ください。
    </p>
  </section>

  <section class="mb-4">
    <h2 class="h5">口コミ・投票について</h2>
    <p>
      口コミ・応援投票（👍）は、どなたでもログイン不要で投稿できます。同一店舗への重複投票は制限しています。
      口コミはあくまで投稿者個人の感想であり、当サイトが内容の正確性を保証するものではありません。
      不適切な投稿を発見された場合は内容を精査のうえ対応します。
    </p>
  </section>
</div>
@endsection
