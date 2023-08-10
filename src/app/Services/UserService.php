<?php

namespace App\Services;
use App\Models\Reserve;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;

class UserService
{
    public function getUsageStatus(User $user) : array 
    {
        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        // 終了時刻は現在時刻からHourを1足して分秒を0に
                        ->where('end', '>=', $now->addHour()->setMinute(00)->setSecond(00)->format('H:i:s'))
                        ->first();
        if(is_null($currentRsv->room_number)) {
            $tags = Tag::where('status', 'using')->first();
            if(is_null($tags)) {
                return ['message' => 'Available.'];
            } else {
                return ['message' => 'Unavailable.'];
            }
        } else {
            if($currentRsv->room_number === $user->room_number) {
                return ['message' => 'Your reservation time.'];
            } else {
                return ['message' => 'An another rooms reservation time.'];
            }
        }
    }
}