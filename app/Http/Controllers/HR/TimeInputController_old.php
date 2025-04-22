<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\TimeInput;
use App\Models\HR\User;

class TimeInputController extends Controller
{
    public function json()
    {
        $data = TimeInput::with('user')
        ->where('Activity_ID', 7)
        ->where('TimeInput_IsStart', 1)
        ->orderByDesc('TimeInput_StartTime')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->TimeInput_ID,
                'user' => $item->user->Users_Name ?? 'N/A',
                'start_time' => $item->TimeInput_StartTime,
                'end_time' => $item->TimeInput_EndTime,
                'comment' => $item->TimeInput_Comment,
                'time_minutes' => $item->TimeInput_Time,
                'time_hours' => $item->TimeInput_TimeInHour,
                'approved' => $item->TimeInput_Approved ? 'Yes' : 'No',
            ];
        });

        return response()->json($data);
    }
}


?>
