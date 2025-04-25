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
     * Handle the clock-in / clock-out process.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
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

        $currentDate = now();
        $period = PeriodWeek::where('Period_Week_StartDate', '<=', $currentDate)
            ->where('Period_Week_EndDate', '>=', $currentDate)
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
            $start = Carbon::parse($entry->TimeInput_StartTime);
            $now = Carbon::now();
            $minutes = (int) $start->diffInMinutes($now);

            $entry->update([
                'TimeInput_EndTime'    => now(),
                'TimeInput_Comment'    => ($entry->TimeInput_Comment ?? '') . ' | Automatically closed before new clock-in.',
                'TimeInput_Time'       => $minutes,
                'TimeInput_TimeInHour' => round($minutes / 60, 2),
                'TimeInput_IsStart'    => 0,
            ]);
        }

        if ($openEntries->count() > 0) {
            //return back()->with('error', 'Previous open sessions were automatically closed. Please scan again to clock-in.');
            return response()->json([
                'success' => false,
                'message' => 'Clock-out registered for ' . $user->Users_Name
            ]);
        }

        // 2. Create a new clock-in entry
        TimeInput::create([
            'Users_ID'            => $user->Users_ID,
            'Activity_ID'         => 7,
            'TimeInput_IsStart'   => 1,
            'TimeInput_StartTime' => now(),
            'TimeInput_Comment'   => $request->note,
            'Period_Week_Id'      => $period->Period_Week_Id,
            'is_Punch_Clock'      => 1,
        ]);

        //return back()->with('success', 'Clock-in registered for ' . $user->Users_Name);
        return response()->json([
            'success' => true,
            'message' => 'Clock-in registered for ' . $user->Users_Name
        ]);
    }

    /**
     * Return current active clock-in entries (without weekly totals).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClockData_old()
    {
        $entries = TimeInput::with('user')
            ->whereNull('TimeInput_EndTime')
            ->where('TimeInput_IsStart', 1)
            ->orderByDesc('TimeInput_StartTime')
            ->limit(100)
            ->get()
            ->map(function ($entry) {
                return [
                    'id'           => $entry->TimeInput_ID,
                    'user'         => $entry->user->Users_Name ?? 'N/A',
                    'start_time'   => $entry->TimeInput_StartTime,
                    'end_time'     => $entry->TimeInput_EndTime,
                    'comment'      => $entry->TimeInput_Comment,
                    'time_minutes' => $entry->TimeInput_Time,
                    'time_hours'   => $entry->TimeInput_TimeInHour,
                    'approved'     => $entry->TimeInput_Approved ? 'Yes' : 'No',
                ];
            });

        return response()->json($entries);
    }

    /**
     * Return current open clock-in entries with total hours for the current week.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClockData()
    {
        $week = PeriodWeek::where('Period_Week_StartDate', '<=', now())
            ->where('Period_Week_EndDate', '>=', now())
            ->first();

        $entries = TimeInput::with('user')
            ->where('TimeInput_IsStart', 1)
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
                    'start_time'   => $entry->TimeInput_StartTime,
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

?>
