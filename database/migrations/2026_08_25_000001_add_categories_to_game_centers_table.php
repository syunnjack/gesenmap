<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 店舗をカテゴリ（アーケード／プライズ／カプセルトイ／プリクラ／その他）で
     * 引けるようにする。
     *
     * カテゴリは公式が出している機種名かブランド名からしか決めない。
     * 決められなかった店は categories を空のままにして、画面では「未確認」と出す。
     * category_source はその根拠（machines / brand / services / unknown）で、
     * 「公式の機種一覧から」と「ブランド名から」を読者に区別して見せるために持つ。
     */
    public function up(): void
    {
        Schema::table('game_centers', function (Blueprint $table) {
            $table->json('categories')->nullable()->after('games');
            $table->string('category_source', 12)->nullable()->after('categories');
            $table->string('store_type', 20)->nullable()->after('category_source');
        });
    }

    public function down(): void
    {
        Schema::table('game_centers', function (Blueprint $table) {
            $table->dropColumn(['categories', 'category_source', 'store_type']);
        });
    }
};
