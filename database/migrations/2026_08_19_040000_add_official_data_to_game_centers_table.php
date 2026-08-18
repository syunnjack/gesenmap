<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 公式サイトで確認できる店舗情報を持てるようにする。
     *
     * これまでは利用者の投稿しか入らず、投稿が集まるまでサイトに何も出ていなかった。
     * チェーン公式サイトが公表している住所・営業時間・設置ゲームは出典が明確なので、
     * 編集部が用意するデータとして持ち、投稿と区別して表示する。
     */
    public function up(): void
    {
        Schema::table('game_centers', function (Blueprint $table) {
            $table->string('slug', 80)->nullable()->unique()->after('name');
            $table->string('chain', 40)->nullable()->after('slug');
            $table->string('prefecture', 10)->nullable()->index()->after('chain');
            $table->string('city', 40)->nullable()->after('prefecture');
            $table->string('address')->nullable()->after('city');
            $table->string('postal_code', 10)->nullable()->after('address');
            $table->string('tel', 30)->nullable()->after('postal_code');
            $table->json('hours')->nullable()->after('tel');
            $table->json('games')->nullable()->after('hours');   // 設置しているゲームの種類
            $table->json('features')->nullable()->after('games'); // お店の特徴（Wi-Fiなど）
            $table->string('source_url')->nullable()->after('features');
            $table->string('source_label')->nullable()->after('source_url');
            $table->date('confirmed_on')->nullable()->after('source_label'); // 出典を確認した日
        });
    }

    public function down(): void
    {
        Schema::table('game_centers', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'chain', 'prefecture', 'city', 'address', 'postal_code',
                'tel', 'hours', 'games', 'features', 'source_url', 'source_label', 'confirmed_on',
            ]);
        });
    }
};
