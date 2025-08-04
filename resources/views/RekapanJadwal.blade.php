<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapan Jadwal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ===== UTAMA ===== */
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            max-width: 1600px;
        }

        /* ===== INDIKATOR WARNA KARTU ===== */
        .status-done { border-left: 5px solid #198754; }
        .status-pending { border-left: 5px solid #ffc107; }
        .status-cancel { border-left: 5px solid #dc3545; }
        .status-ongoing { border-left: 5px solid #0dcaf0; }
        .priority-high { border-right: 5px solid #dc3545; }
        .priority-medium { border-right: 5px solid #ffc107; }
        .priority-low { border-right: 5px solid #198754; }

        /* ===== DESAIN KARTU JADWAL BARU ===== */
        .schedule-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        .schedule-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .schedule-card .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1.25rem;
        }
        .schedule-card .card-title {
            font-weight: 600;
            color: #343a40;
        }
        .schedule-card .card-body {
            padding: 1.25rem;
        }
        .schedule-card .time-info {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .schedule-card .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 0.75rem 1.25rem;
        }

        /* ===== NOTIFIKASI ===== */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid #fff;
        }
        .dropdown-menu {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border: none;
        }
        
        /* ===== FILTER ACCORDION ===== */
        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0d6efd;
        }
        .accordion-button:focus {
            box-shadow: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">

        <div class="row mb-4 align-items-center">
            <div class="col">
                <div class="d-flex align-items-center">
                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <h2 class="mb-0 text-dark">Rekapan Jadwal</h2>
                </div>
            </div>
            <div class="col-auto">
                <div class="dropdown">
                    <button class="btn btn-primary position-relative rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell me-1"></i> Notifikasi
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" style="width: 350px;">
                        <li class="px-3 py-2"><h6 class="mb-0">Peringatan Jadwal Berdekatan</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        @forelse($conflictNotifications as $notification)
                            <li class="px-3 py-2"><p class="mb-1 small"><i class="fas fa-exclamation-triangle text-warning me-2"></i>{{ $notification->message }}</p></li>
                        @empty
                            <li class="px-3 py-4 text-center text-muted">Tidak ada jadwal yang berdekatan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div id="notification-placeholder" class="mb-3"></div>

        <div class="accordion mb-4" id="filterAccordion">
            <div class="accordion-item border-0 shadow-sm">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-filter me-2"></i> Filter & Opsi Tampilan
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#filterAccordion">
                    <div class="accordion-body">
                        <form method="GET" action="{{ route('jadwal.rekapan') }}">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan...">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <select class="form-select" name="date_filter">
                                        <option value="semua" {{ request('date_filter', 'semua') == 'semua' ? 'selected' : '' }}>Semua Tanggal</option>
                                        <option value="hari-ini" {{ request('date_filter') == 'hari-ini' ? 'selected' : '' }}>Hari Ini</option>
                                        <option value="minggu-ini" {{ request('date_filter') == 'minggu-ini' ? 'selected' : '' }}>Minggu Ini</option>
                                        <option value="bulan-ini" {{ request('date_filter') == 'bulan-ini' ? 'selected' : '' }}>Bulan Ini</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <select class="form-select" name="priority_filter">
                                        <option value="semua" {{ request('priority_filter', 'semua') == 'semua' ? 'selected' : '' }}>Semua Prioritas</option>
                                        <option value="high" {{ request('priority_filter') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="medium" {{ request('priority_filter') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                        <option value="low" {{ request('priority_filter') == 'low' ? 'selected' : '' }}>Rendah</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <select class="form-select" name="status_filter">
                                        <option value="semua" {{ request('status_filter', 'semua') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="ongoing" {{ request('status_filter') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                                        <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Tertunda</option>
                                        <option value="done" {{ request('status_filter') == 'done' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancel" {{ request('status_filter') == 'cancel' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </div>
                                <div class="col-md-12 col-lg-2">
                                    <select class="form-select" name="sort_by">
                                        <option value="date" {{ request('sort_by', 'date') == 'date' ? 'selected' : '' }}>Urutkan: Tanggal</option>
                                        <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Urutkan: Prioritas</option>
                                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Urutkan: Status</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Terapkan</button><a href="{{ route('jadwal.rekapan') }}" class="btn btn-light border ms-2"><i class="fas fa-sync-alt"></i> Reset</a></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($jadwals as $jadwal)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card schedule-card status-{{ strtolower($jadwal->status) }} priority-{{ strtolower($jadwal->priority ?? 'medium') }} h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fs-6">{{ $jadwal->activity_name }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="openStatusModal({{ $jadwal->id }}, '{{ addslashes($jadwal->activity_name) }}')"><i class="fas fa-flag fa-fw me-2"></i>Ubah Status</a></li>
                                    <li><a class="dropdown-item btn-edit" href="#" data-id="{{ $jadwal->id }}"><i class="fas fa-edit fa-fw me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); openDeleteModal({{ $jadwal->id }}, '{{ addslashes($jadwal->activity_name) }}')"><i class="fas fa-trash-alt fa-fw me-2"></i>Hapus</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 time-info">
                                <i class="fas fa-calendar-alt fa-fw me-2 text-primary"></i>
                                <span>{{ \Carbon\Carbon::parse($jadwal->date)->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center time-info">
                                <i class="fas fa-clock fa-fw me-2 text-primary"></i>
                                <span>{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} WIB</span>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            @switch(strtolower($jadwal->status))
                                @case('done') <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Selesai</span> @break
                                @case('pending') <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Tertunda</span> @break
                                @case('cancel') <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Batal</span> @break
                                @case('ongoing') <span class="badge rounded-pill bg-info-subtle text-info-emphasis">Berlangsung</span> @break
                            @endswitch
                            @switch(strtolower($jadwal->priority ?? ''))
                                @case('high') <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Prioritas Tinggi</span> @break
                                @case('medium') <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Prioritas Sedang</span> @break
                                @case('low') <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Prioritas Rendah</span> @break
                            @endswitch
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center bg-white p-5 rounded-3 shadow-sm">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                        <h4 class="mb-2">Belum Ada Jadwal</h4>
                        <p class="text-muted">Tidak ada jadwal yang sesuai dengan filter Anda saat ini. Coba <a href="{{ route('jadwal.rekapan') }}">reset filter</a>.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($jadwals->hasPages())
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $jadwals->links() }}
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="formEdit"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" id="edit-id" name="id"><div class="mb-3"><label class="form-label">Nama Kegiatan</label><input type="text" id="edit-activityName" name="activity_name" class="form-control" required></div><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Tanggal</label><input type="date" id="edit-activityDate" name="date" class="form-control" required></div><div class="col-md-6 mb-3"><label class="form-label">Waktu Mulai</label><input type="time" id="edit-startTime" name="start_time" class="form-control"></div></div><div class="row"><div class="col-12 mb-3"><label class="form-label">Prioritas</label><select id="edit-priority" name="priority" class="form-select"><option value="low">🟢 Rendah</option><option value="medium">🟡 Sedang</option><option value="high">🔴 Tinggi</option></select></div></div><div class="mb-3"><label class="form-label">Status</label><select id="edit-status" name="status" class="form-select"><option value="ongoing">🔄 Berlangsung</option><option value="pending">⏳ Tertunda</option><option value="done">✅ Selesai</option><option value="cancel">❌ Batal</option></select></div><div class="mb-3"><label class="form-label">Deskripsi</label><textarea id="edit-description" name="description" class="form-control" rows="3"></textarea></div><div class="mb-3"><label class="form-label">Lokasi</label><input type="text" id="edit-location" name="location" class="form-control"></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success">Simpan</button></div></div></form></div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">📌 Ubah Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p id="statusScheduleName" class="fw-bold"></p><form id="statusForm" method="POST">@csrf @method('PUT')<div class="row g-2"><div class="col-6"><button type="button" class="btn btn-info w-100" onclick="changeStatus('ongoing')">🔄 Berlangsung</button></div><div class="col-6"><button type="button" class="btn btn-warning w-100" onclick="changeStatus('pending')">⏳ Tertunda</button></div><div class="col-6"><button type="button" class="btn btn-success w-100" onclick="changeStatus('done')">✅ Selesai</button></div><div class="col-6"><button type="button" class="btn btn-danger w-100" onclick="changeStatus('cancel')">❌ Batal</button></div></div><input type="hidden" name="status" id="statusInput"></form></div></div></div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">🗑️ Hapus Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p>Anda yakin ingin menghapus jadwal ini?</p><p id="deleteScheduleName" class="fw-bold text-danger"></p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button></div></div></div>
    </div>

    <form id="realDeleteForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        let currentScheduleId = null;
        let deleteModalInstance, statusModalInstance, editModalInstance;
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModalEl = document.getElementById('deleteModal'); if (deleteModalEl) deleteModalInstance = new bootstrap.Modal(deleteModalEl);
            const statusModalEl = document.getElementById('statusModal'); if (statusModalEl) statusModalInstance = new bootstrap.Modal(statusModalEl);
            const editModalEl = document.getElementById('modalEdit'); if (editModalEl) editModalInstance = new bootstrap.Modal(editModalEl);
        });
        function backToMainMenu() { window.location.href = "{{ route('welcome') }}"; }
        function openStatusModal(scheduleId, scheduleName) { document.getElementById('statusScheduleName').textContent = `Kegiatan: ${scheduleName}`; document.getElementById('statusForm').action = `/jadwal/${scheduleId}/status`; if (statusModalInstance) statusModalInstance.show(); }
        function changeStatus(newStatus) { document.getElementById('statusInput').value = newStatus; document.getElementById('statusForm').submit(); }
        function openDeleteModal(scheduleId, scheduleName) { currentScheduleId = scheduleId; document.getElementById('deleteScheduleName').textContent = scheduleName; if (deleteModalInstance) deleteModalInstance.show(); }
        function confirmDelete() { if (currentScheduleId) { const form = document.getElementById('realDeleteForm'); form.action = `/jadwal/${currentScheduleId}`; form.submit(); } }
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.get(`/jadwal/${id}/edit`, function(data) {
                $('#edit-id').val(data.id);
                $('#edit-activityName').val(data.activity_name);
                $('#edit-activityDate').val(data.date);
                $('#edit-startTime').val(data.start_time);
                $('#edit-priority').val(data.priority || 'medium');
                $('#edit-status').val(data.status || 'pending');
                $('#edit-description').val(data.description || '');
                $('#edit-location').val(data.location || '');
                if (editModalInstance) editModalInstance.show();
            }).fail(() => alert('Gagal memuat data.'));
        });
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: `/jadwal/${$('#edit-id').val()}`, type: 'PUT', data: $(this).serialize() + '&_token={{ csrf_token() }}',
                success: function(response) {
                    if (editModalInstance) editModalInstance.hide();
                    if (response.warning) {
                        const alertHtml = `<div class="alert alert-warning alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-triangle me-2"></i><strong>Peringatan!</strong> ${response.warning}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                        $('#notification-placeholder').html(alertHtml);
                        //setTimeout(() => location.reload(), 3500);
                        location.reload(); // tanpa delay

                    } else {
                        location.reload();
                    }
                },
                error: function() { alert('Gagal menyimpan data.'); }
            });
        });
    </script>
</body>
</html>