<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function read(Request $request){
        if($request->ajax()){
            $notification = Notification::where('noti_id', $request->id)->first();
            $notification->read_at = Carbon::now();
            $notification->save();
        }
    }
}
