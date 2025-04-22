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
    // Muestra el formulario de clock-in / clock-out
    public function showForm()
    {
        return view('hr.timeinput.clock');
    }

    public function processClock(Request $request)
{
    $request->validate([
        'barcode' => 'required|string|max:50',
        'note' => 'nullable|string|max:255',
    ]);

    $user = Users::where('Employe_RFID', $request->barcode)->first();

    if (!$user) {
        return back()->with('error', 'Empleado no encontrado con ese código.');
    }

    // Buscar el periodo contable activo según la fecha actual
    $currentDate = now();
    $period = \App\Models\HR\PeriodWeek::where('Period_Week_StartDate', '<=', $currentDate)
        ->where('Period_Week_EndDate', '>=', $currentDate)
        ->first();

    if (!$period) {
        return back()->with('error', 'No existe un período contable activo para la fecha actual.');
    }


    // Buscar el último registro sin hora de salida
    $lastEntry = TimeInput::where('Users_ID', $user->Users_ID)
        ->where('Activity_ID', 7)
        ->where('TimeInput_IsStart', 1)
        ->whereNull('TimeInput_EndTime')
        ->orderByDesc('TimeInput_StartTime')
        ->first();

    if ($lastEntry) {
        // Clock Out
        $now = Carbon::now();
        $start = Carbon::parse($lastEntry->TimeInput_StartTime);
        $minutes = $start->diffInMinutes($now);

        $lastEntry->update([
            'TimeInput_EndTime' => $now,
            'TimeInput_Time' => (int) $minutes, // 👈 asegúrate de castear como entero
            'TimeInput_TimeInHour' => round($minutes / 60, 2), // esto sí puede ser float
            'TimeInput_Comment' => $request->note,
            'Period_Week_Id' => $period->Period_Week_Id,
            'is_Punch_Clock' => 1,
            'TimeInput_IsStart' => 0,
        ]);

        return back()->with('success', 'Salida registrada para ' . $user->Users_Name);
    } else {
        // Clock In
        TimeInput::create([
            'Users_ID' => $user->Users_ID,
            'Activity_ID' => 7,
            'TimeInput_IsStart' => 1,
            'TimeInput_StartTime' => now(),
            'TimeInput_Comment' => $request->note,
            'Period_Week_Id' => $period->Period_Week_Id,
            'is_Punch_Clock' => 1,
        ]);

        return back()->with('success', 'Entrada registrada para ' . $user->Users_Name);
    }
}

public function getClockData()
{
    $entries = TimeInput::with('user')
        ->whereNull('TimeInput_EndTime') // ✅ Solo los que no han salido
        ->where('TimeInput_IsStart', 1)   // ✅ Asegura que es Clock-In
        ->orderByDesc('TimeInput_StartTime')
        ->limit(100)
        ->get()
        ->map(function ($entry) {
            return [
                'id' => $entry->TimeInput_ID,
                'user' => $entry->user->Users_Name ?? 'N/A',
                'start_time' => $entry->TimeInput_StartTime,
                'end_time' => $entry->TimeInput_EndTime,
                'comment' => $entry->TimeInput_Comment,
                'time_minutes' => $entry->TimeInput_Time,
                'time_hours' => $entry->TimeInput_TimeInHour,
                'approved' => $entry->TimeInput_Approved ? 'Yes' : 'No',
            ];
        });

    return response()->json($entries);
}



}
