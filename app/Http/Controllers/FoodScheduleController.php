<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoodScheduleController extends Controller
{
    public function index()
    {
        $schedules = session()->get('food_schedules', []);

        return view('food-assistant', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required',
            'category' => 'required|string|max:50',
            'note' => 'nullable|string|max:255',
        ]);

        $schedules = session()->get('food_schedules', []);

        $newSchedule = [
            'id' => time(),
            'menu' => $request->menu,
            'date' => $request->date,
            'time' => $request->time,
            'category' => $request->category,
            'note' => $request->note,
            'status' => 'Belum dimasak',
        ];

        $schedules[] = $newSchedule;

        session()->put('food_schedules', $schedules);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal masak berhasil ditambahkan!',
            'data' => $newSchedule,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required',
            'category' => 'required|string|max:50',
            'note' => 'nullable|string|max:255',
        ]);

        $schedules = session()->get('food_schedules', []);
        $updatedSchedule = null;

        foreach ($schedules as &$schedule) {
            if ($schedule['id'] == $id) {
                $schedule['menu'] = $request->menu;
                $schedule['date'] = $request->date;
                $schedule['time'] = $request->time;
                $schedule['category'] = $request->category;
                $schedule['note'] = $request->note;

                if (!isset($schedule['status'])) {
                    $schedule['status'] = 'Belum dimasak';
                }

                $updatedSchedule = $schedule;
                break;
            }
        }

        session()->put('food_schedules', $schedules);

        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal berhasil diedit!',
            'data' => $updatedSchedule,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Belum dimasak,Sedang dimasak,Selesai',
        ]);

        $schedules = session()->get('food_schedules', []);
        $updatedSchedule = null;

        foreach ($schedules as &$schedule) {
            if ($schedule['id'] == $id) {
                $schedule['status'] = $request->status;
                $updatedSchedule = $schedule;
                break;
            }
        }

        session()->put('food_schedules', $schedules);

        return response()->json([
            'success' => true,
            'message' => 'Status jadwal berhasil diubah!',
            'data' => $updatedSchedule,
        ]);
    }

    public function destroy($id)
    {
        $schedules = session()->get('food_schedules', []);

        $schedules = array_filter($schedules, function ($schedule) use ($id) {
            return $schedule['id'] != $id;
        });

        session()->put('food_schedules', array_values($schedules));

        return response()->json([
            'success' => true,
            'message' => 'Jadwal masak berhasil dihapus!',
        ]);
    }
}
