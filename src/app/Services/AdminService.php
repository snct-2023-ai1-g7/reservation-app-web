<?php

namespace App\Services;
use App\Models\Reserve;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class AdminService
{
    public function getUsers()
    {
        return User::all();
    }

    public function regenerateUsersPassword(string $user_id) : string
    {
        $newPassword = '';
        try {
            $target_user = User::where('user_id', $user_id)->first();
            $newPassword = $target_user->regeneratePassword();
        } catch (QueryException $e) {
            throw $e;
        }

        return $newPassword;
    }

    public function getUsageStatus() : array 
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
            return ['message' => 'A rooms reservation time.'];
        }
    }

    // usingにするやつとleftにするやつで分割
    public function updateToUsing(int $room_number) : array {
        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        ->where('end', '>=', $now->format('H:i:s'))
                        ->first(); 
        $user = User::where('room_number', $room_number)->first(); 
        if(is_null($user)) {
            return ['message' => 'Room ' . (string)$room_number . ' does not exist.'];
        } 

        if(is_null($currentRsv->room_number)) {
            $tags = Tag::where('status', 'using')->get();
            if(count($tags) == 0) {
                $tag_room = Tag::where('user_id', $user->user_id)->first();
                $tag_room->status = 'using';
                $tag_room->save();
                return ['message' => 'Updated that room ' . (string)$room_number . ' is using.'];
            } else {
                return ['message' => 'An another room is using.'];
            }
        } else {
            return ['message' => 'An another room has already reserved.'];
        }
    }

    public function updateToLeft(int $room_number) : array {
        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        ->where('end', '>=', $now->format('H:i:s'))
                        ->first();
        $user = User::where('room_number', $room_number)->first(); 
        if(is_null($user)) {
            return ['message' => 'Room ' . (string)$room_number . ' does not exist.'];
        }

        if(is_null($currentRsv->room_number)) {
            $tags = Tag::where('status', 'using')->get();
            if(count($tags) > 0) {
                $tag_room = $tags->where('user_id', $user->user_id)->first();
                if(!is_null($tag_room)) {
                    $tag_room->status = 'left';
                    $tag_room->save();
                    return ['message' => 'Updated that room ' . (string)$room_number . ' left.'];
                } else {
                    return ['message' => 'Room ' . (string)$room_number . ' was not using.']; 
                }
            } else {
                return ['message' => 'No room was using.'];
            }
        } else {
            return ['message' => 'An another room has already reserved.'];
        }
    }
}