<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use Carbon\Carbon;

class JadwalController extends Controller
{
    // Tampilkan halaman input jadwal
    public function create()
    {
        return view('InputDataPrioritas');
    }

    // Simpan data jadwal ke database
// GANTI DENGAN VERSI BARU INI
// Ganti dengan fungsi ini
public function store(Request $request)
{
    $request->validate([
        'activityName' => 'required|string|max:255',
        'date' => 'required|date',
        'startTime' => 'required',
        'priority' => 'required|in:low,medium,high',
    ]);

    // Panggil fungsi tanpa end_time
    $conflictingSchedule = $this->findNearbySchedule(
        $request->date,
        $request->startTime
    );

    $warningMessage = null;
    if ($conflictingSchedule) {
        $warningMessage = "Peringatan: Jadwal baru ini berdekatan dengan '" . $conflictingSchedule->activity_name . "' pada jam " . Carbon::parse($conflictingSchedule->start_time)->format('H:i') . ".";
    }

    Jadwal::create([
        'activity_name' => $request->activityName,
        'date' => $request->date,
        'start_time' => $request->startTime,
        // end_time sudah dihapus dari sini
        'location' => $request->location,
        'description' => $request->description,
        'priority' => $request->priority,
    ]);

    $response = ['success' => true, 'message' => 'Jadwal berhasil disimpan!'];
    if ($warningMessage) {
        $response['warning'] = $warningMessage;
    }

    return response()->json($response);
}

    // Tampilkan rekapan
public function index()
{
    $jadwals = Jadwal::orderBy('date', 'asc')->get();
    return view('rekapan_jadwal', compact('jadwals'));
}

public function rekapan(Request $request)
{
    // Logika otomatisasi status dan filter tetap sama
    Jadwal::where('date', '<', Carbon::today())
          ->whereIn('status', ['ongoing', 'pending'])
          ->update(['status' => 'done']);

    $query = Jadwal::query();
    // (Kode filter Anda tetap di sini, tidak ada yang berubah)
    if ($request->filled('search')) { $query->where('activity_name', 'like', '%' . $request->search . '%'); }
    if ($request->filled('date_filter') && $request->date_filter !== 'semua') {
        switch ($request->date_filter) {
            case 'hari-ini': $query->whereDate('date', Carbon::today()); break;
            case 'minggu-ini': $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); break;
            case 'bulan-ini': $query->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year); break;
        }
    }
    if ($request->filled('priority_filter') && $request->priority_filter !== 'semua') { $query->where('priority', $request->priority_filter); }
    if ($request->filled('status_filter') && $request->status_filter !== 'semua') { $query->where('status', $request->status_filter); }
    
    // (Kode sorting Anda tetap di sini, tidak ada yang berubah)
    $sortBy = $request->input('sort_by', 'date');
    if ($sortBy === 'date') {
        $query->orderByRaw("FIELD(status, 'ongoing', 'pending', 'done', 'cancel')")->orderBy('date', 'asc')->orderBy('start_time', 'asc');
    } else {
        switch ($sortBy) {
            case 'priority': $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')"); break;
            case 'status': $query->orderByRaw("FIELD(status, 'ongoing', 'pending', 'done', 'cancel')"); break;
        }
    }

    $jadwals = $query->paginate(9)->withQueryString(); 

    // --- LOGIKA BARU UNTUK DETEKSI KONFLIK LIVE ---
    $activeSchedules = Jadwal::whereIn('status', ['ongoing', 'pending'])->orderBy('date')->orderBy('start_time')->get();
    $conflictNotifications = [];
    $reportedConflicts = []; // Untuk memastikan pasangan konflik tidak dilaporkan dua kali

    foreach ($activeSchedules as $schedule1) {
        foreach ($activeSchedules as $schedule2) {
            // Hindari membandingkan jadwal dengan dirinya sendiri atau pasangan yang sudah dilaporkan
            if ($schedule1->id >= $schedule2->id) continue;
            
            // Hanya periksa jadwal pada hari yang sama
            if ($schedule1->date == $schedule2->date) {
                // Hitung selisih waktu dalam menit
                $time1 = Carbon::parse($schedule1->start_time);
                $time2 = Carbon::parse($schedule2->start_time);
                $timeDiff = abs($time1->diffInMinutes($time2));

                if ($timeDiff < 90) { // Jika selisih kurang dari 90 menit
                    $message = "Jadwal '{$schedule1->activity_name}' ({$time1->format('H:i')}) berdekatan dengan '{$schedule2->activity_name}' ({$time2->format('H:i')}).";
                    // Tambahkan ke array notifikasi
                    $conflictNotifications[] = (object)['message' => $message, 'created_at' => now()];
                }
            }
        }
    }
    $unreadNotificationsCount = count($conflictNotifications);
    // --- AKHIR LOGIKA BARU ---

    // Kirim variabel baru ke view, ganti 'notifications' dengan 'conflictNotifications'
    return view('RekapanJadwal', compact('jadwals', 'conflictNotifications', 'unreadNotificationsCount'));
}
// Ganti dengan fungsi ini
private function findNearbySchedule($date, $startTime, $exceptId = null)
{
    if (!$startTime) {
        return null;
    }

    // Cek jadwal lain dalam rentang 90 menit dari waktu mulai
    $buffer = 90; 
    $checkTime = Carbon::parse($startTime);

    $query = Jadwal::where('date', $date)
                   ->where('status', '!=', 'cancel')
                   ->whereTime('start_time', '>', $checkTime->copy()->subMinutes($buffer)->toTimeString())
                   ->whereTime('start_time', '<', $checkTime->copy()->addMinutes($buffer)->toTimeString());

    if ($exceptId) {
        $query->where('id', '!=', $exceptId);
    }

    return $query->first();
}
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:ongoing,pending,done,cancel' // <- penting
    ]);

    $jadwal = Jadwal::findOrFail($id);
    $jadwal->status = $request->status;
    $jadwal->save();

    return redirect()->back()->with('success', 'Status berhasil diperbarui');
}



    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            
            return redirect()->route('jadwal.rekapan')
                ->with('success', 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('jadwal.rekapan')
                ->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
public function edit($id)
{
    $jadwal = Jadwal::findOrFail($id);
    return response()->json($jadwal);
}

// File: app/Http/Controllers/JadwalController.php

// SESUDAH
// Ganti dengan fungsi ini
public function update(Request $request, $id)
{
    $request->validate([
        'activity_name' => 'required|string|max:255',
        'date' => 'required|date',
    ]);

    // Panggil fungsi tanpa end_time
    $conflictingSchedule = $this->findNearbySchedule(
        $request->date,
        $request->start_time,
        $id
    );
    
    $warningMessage = null;
    if ($conflictingSchedule) {
        $warningMessage = "Peringatan: Jadwal ini berdekatan dengan '" . $conflictingSchedule->activity_name . "' pada jam " . Carbon::parse($conflictingSchedule->start_time)->format('H:i') . ".";
    }

    $jadwal = Jadwal::findOrFail($id);
    // Simpan hanya field yang ada di database
    $jadwal->update($request->except('_token', 'end_time'));

    $response = ['message' => 'Jadwal berhasil diperbarui!'];
    if ($warningMessage) {
        $response['warning'] = $warningMessage;
    }

    return response()->json($response);
}


}
