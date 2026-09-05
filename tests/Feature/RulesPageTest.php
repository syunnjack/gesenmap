<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * 公式のルールのページ。
 *
 * 数字が本文から消えたり書き換わったりすると、法令の話として成り立たなくなる。
 * よく間違われる「800円」との違いも含めて、要点が出ていることを見る。
 */
class RulesPageTest extends TestCase
{
    public function test_ページが開く(): void
    {
        $this->get('/rules')->assertOk()->assertSee('公式のルール');
    }

    public function test_景品の上限は1000円で800円との違いも書いてある(): void
    {
        $response = $this->get('/rules');
        $response->assertSee('おおむね1,000円以下');
        $response->assertSee('ぱちんこ屋', false);
    }

    public function test_18歳未満の時間と条例の関係が書いてある(): void
    {
        $response = $this->get('/rules');
        $response->assertSee('午後10時から翌日の午前6時まで');
        $response->assertSee('条例');
    }

    public function test_出典へのリンクがある(): void
    {
        $response = $this->get('/rules');
        $response->assertSee('laws.e-gov.go.jp/law/323AC0000000122', false);
        $response->assertSee('npa.go.jp', false);
    }
}
