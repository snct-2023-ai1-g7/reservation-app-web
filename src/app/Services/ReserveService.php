<?php
namespace App\Services;

use App\Exceptions\ReservationException;
use App\Models\Reserve;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;

class ReserveService
{
    // 予約
    public function reserve(DateTime $start, DateTime $end, int $room_number)
    {
        $reserve = Reserve::where('start', $start->format("H:i:s"))
                    ->where('end', $end->format("H:i:s"))
                    ->first();

        if(is_null($reserve->room_number)) {
            $reserve->update(['room_number' => $room_number]);
        } else {
            throw new ReservationException("An another room has already reserved.");
        }
    }

    // 予約一覧を取得
    public function getReserves()
    {
        // 現在時刻を取得
        $now = Carbon::now();
        $reserves = Reserve::where('start', '>=', $now->format("H:i:s"))->get();
        return $reserves;
    }

    // 予約を削除
    public function removeReserve(DateTime $start, DateTime $end, int $room_number)
    {
        $reserve = Reserve::where('start', $start->format("H:i:s"))
                    ->where('end', $end->format("H:i:s"))
                    ->first();

        if($reserve->room_number === $room_number) {
            $reserve->update(['room_number' => null]);
        } else {
            throw new ReservationException("Not allowd to change an another room's reservation.");
        }
    }
}