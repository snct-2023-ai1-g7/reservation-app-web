<?php

namespace App\Services;
use App\Models\Reserve;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class UserService
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

    public function getUsageStatus(User $user) : array 
    {
        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        ->where('end', '>=', $now->format('H:i:s'))
                        ->first();
        
        if(is_null($currentRsv->room_number)) {
            $tags = Tag::where('status', 'using');
            if(count($tags) == 0) {
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

    public function removeStatus() 
    {
        $now = Carbon::now();
        $currentRsv = Reserve::where('start', '<=', $now->format('H:i:s'))
                        ->where('end', '>=', $now->format('H:i:s'))
                        ->first();

        if(is_null($currentRsv->room_number)) {
            $tags = Tag::where('status', 'using');
            if(count($tags) > 0) {
                $tags->first()->update(['status', 'not using']);
            }
        }
    }
}