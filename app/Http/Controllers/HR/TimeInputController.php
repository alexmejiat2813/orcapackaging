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

    $currentDate = now();
    $period = PeriodWeek::where('Period_Week_StartDate', '<=', $currentDate)
        ->where('Period_Week_EndDate', '>=', $currentDate)
        ->first();

    if (!$period) {
        return back()->with('error', 'No hay un período contable activo para hoy.');
    }

    // 🔁 1. Cerrar TODAS las sesiones abiertas sin salida
    $openEntries = TimeInput::where('Users_ID', $user->Users_ID)
        ->whereNull('TimeInput_EndTime')
        ->where('TimeInput_IsStart', 1)
        ->get();

    foreach ($openEntries as $entry) {
        $start = Carbon::parse($entry->TimeInput_StartTime);
        $now = Carbon::now();
        $minutes = (int) $start->diffInMinutes($now);

        $entry->update([
            'TimeInput_EndTime' => now(),
            'TimeInput_Comment' => ($entry->TimeInput_Comment ?? '') . ' | Cerrado automáticamente antes de nueva entrada',
            'TimeInput_Time' => $minutes,
            'TimeInput_TimeInHour' => round($minutes / 60, 2),
            'TimeInput_IsStart' => 0,
        ]);
    }

    // 🔁 2. Si hubo entradas abiertas, se cerraron → el usuario debe volver a marcar
    if ($openEntries->count() > 0) {
        return back()->with('error', 'Tenías sesiones abiertas. Fueron cerradas automáticamente. Por favor vuelve a escanear para registrar tu entrada.');
    }

    // 🆕 3. Registrar nueva entrada si no hay entradas abiertas
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





public function getClockData_old()
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
            'id' => $entry->TimeInput_ID,
            'user' => $entry->user->Users_Name ?? 'N/A',
            'start_time' => $entry->TimeInput_StartTime,
            'end_time' => $entry->TimeInput_EndTime,
            'comment' => $entry->TimeInput_Comment,
            'time_minutes' => $entry->TimeInput_Time,
            'time_hours' => $entry->TimeInput_TimeInHour,
            'approved' => $entry->TimeInput_Approved ? 'Yes' : 'No',
            'weekly_hours' => sprintf('%02d:%02d', $hours, $minutes),
        ];
    });

return response()->json($entries);

}



}
