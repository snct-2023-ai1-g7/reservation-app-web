<?php
namespace App\Services;

use App\Exceptions\ReservationException;
use App\Models\Reserve;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;

class TagService
{
    public function checkUsage(string $uid) : array 
    {
        $tag = Tag::where('uid', $uid)->first();
        $user = User::where('user_id', $tag->user_id)->first();

        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        // 終了時刻は現在時刻からHourを1足して分秒を0に
                        ->where('end', '>=', $now->addHour()->setMinute(00)->setSecond(00)->format('H:i:s'))
                        ->first();

        // 誰も予約してない時
        if(is_null($currentRsv->room_number)) {
            $checkTag = Tag::where('status', 'using')->first();
            if(is_null($checkTag)) {
                $tag->status = 'using';
                $tag->save();
                return ['message' => 'Entered.'];
            } else {
                if($checkTag->uid === $uid) {
                    $tag->status = 'left';
                    $tag->save();
                    return ['message' => 'Left.'];
                } else {
                    return ['message' => 'Not allowed.'];
                }
            }
        } 
        // 誰かが予約している時 
        else {
            if($currentRsv->room_number === $user->room_number) {
                return ['message' => 'Ok.'];
            } else {
                return ['message' => 'Not allowed.'];
            }
        }
    }
}