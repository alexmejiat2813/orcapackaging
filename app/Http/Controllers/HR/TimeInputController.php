<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\TimeInput;
use App\Models\HR\Users;
use App\Models\HR\PeriodWeek;
use Carbon\Carbon;

class TimeInputController extends Controller
{
    /**
     * Display the clock-in / clock-out form.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('hr.timeinput.clock');
    }

    /**
     * Get the current local time in Montreal timezone.
     */
    private function nowLocal()
    {
        return now()->setTimezone('America/Toronto');
    }

    /**
     * Handle the clock-in / clock-out process.
     */
    public function processClock(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:50',
            'note'    => 'nullable|string|max:255',
        ]);

        $user = Users::where('Employe_RFID', $request->barcode)->first();

        if (!$user) {
            return back()->with('error', 'Employee not found with the provided barcode.');
        }

        $now = $this->nowLocal();

        $period = PeriodWeek::where('Period_Week_StartDate', '<=', $now)
            ->where('Period_Week_EndDate', '>=', $now)
            ->first();

        if (!$period) {
            return back()->with('error', 'No active accounting period found for today.');
        }

        // 1. Close all previous open sessions
        $openEntries = TimeInput::where('Users_ID', $user->Users_ID)
            ->whereNull('TimeInput_EndTime')
            ->where('TimeInput_IsStart', 1)
            ->get();

        foreach ($openEntries as $entry) {
            $start = Carbon::parse($entry->TimeInput_StartTime)->setTimezone('America/Toronto');
            $minutes = max(0, $start->diffInMinutes($now));

            $entry->update([
                'TimeInput_EndTime'    => $now->toDateTimeString(),
                'TimeInput_Comment'    => ($entry->TimeInput_Comment ?? '') . ' Automatically closed before new clock-in.',
                'TimeInput_Time'       => (int) round($minutes),
                'TimeInput_Approved'   => 0,
                'TimeInput_TimeInHour' => round($minutes / 60, 2),
                'TimeInput_IsStart'    => 0,
            ]);
        }

        if ($openEntries->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Clock-out registered for ' . $user->Users_Name
            ]);
        }

        $lastEntry = TimeInput::where('Users_ID', $user->Users_ID)
            ->whereNotNull('TimeInput_EndTime')
            ->orderByDesc('TimeInput_EndTime')
            ->first();

        $lastEndTime = $lastEntry ? Carbon::parse($lastEntry->TimeInput_EndTime)->setTimezone('America/Toronto') : null;
        $delayMinutes = $lastEndTime ? (int) $lastEndTime->diffInMinutes($now) : null;

        // 2. Create a new clock-in entry
        TimeInput::create([
            'Users_ID'                    => $user->Users_ID,
            'Activity_ID'                 => 7,
            'TimeInput_IsStart'          => 1,
            'TimeInput_StartTime'        => $now->toDateTimeString(),
            'TimeInput_TimeStamp'        => $now->toDateTimeString(),
            'TimeInput_Comment'          => $request->note,
            'Period_Week_Id'             => $period->Period_Week_Id,
            'is_Punch_Clock'             => 1,
            'TimeInput_Last_EndTime'     => $lastEndTime ? $lastEndTime->toDateTimeString() : null,
            'TimeInput_Last_Delay_EndTime' => $delayMinutes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clock-in registered for ' . $user->Users_Name
        ]);
    }

    /**
     * Return current active clock-in entries with total hours for the current week.
     */
    public function getClockData()
    {
        $today = $this->nowLocal()->toDateString();

        $week = PeriodWeek::whereDate('Period_Week_StartDate', '<=', $today)
            ->whereDate('Period_Week_EndDate', '>=', $today)
            ->first();

        if (!$week) {
            return response()->json([
                'success' => false,
                'message' => 'No active week found for today.'
            ], 404);
        }

        $entries = TimeInput::with('user')
            ->where('TimeInput_IsStart', 1)
            ->where('Activity_ID', 7)
            ->whereNull('TimeInput_EndTime')
            ->where('Period_Week_Id', $week->Period_Week_Id)
            ->orderByDesc('TimeInput_StartTime')
            ->get()
            ->map(function ($entry) use ($week) {
                $weeklyTotal = TimeInput::where('Users_ID', $entry->Users_ID)
                    ->where('Period_Week_Id', $week->Period_Week_Id)
                    ->sum('TimeInput_TimeInHour');

                $hours = floor($weeklyTotal);
                $minutes = round(($weeklyTotal - $hours) * 60);

                return [
                    'id'           => $entry->TimeInput_ID,
                    'user'         => $entry->user->Users_Name ?? 'N/A',
                    'start_time'   => Carbon::parse($entry->TimeInput_StartTime)->setTimezone('America/Toronto')->toDateTimeString(),
                    'end_time'     => $entry->TimeInput_EndTime,
                    'comment'      => $entry->TimeInput_Comment,
                    'time_minutes' => $entry->TimeInput_Time,
                    'time_hours'   => $entry->TimeInput_TimeInHour,
                    'approved'     => $entry->TimeInput_Approved ? 'Yes' : 'No',
                    'weekly_hours' => sprintf('%02d:%02d', $hours, $minutes),
                ];
            });

        return response()->json($entries);
    }
}
