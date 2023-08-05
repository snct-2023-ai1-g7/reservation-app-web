<?php
// Tweetクラスに直接依存しないためのクラス（テストの観点で良い）

namespace App\Services;

use App\Models\Reserve;
use Carbon\Carbon;

class ReserveService
{
    public function getReserves()
    {
        // 現在時刻を取得
        $now = Carbon::now();
        $reserves = Reserve::where('start', '>=', $now->format("H:M"))->get();
        return $reserves;
    }
}