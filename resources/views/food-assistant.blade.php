<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Food Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Token CSRF agar request AJAX aman di Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #fff7ed;
            color: #2d2d2d;
        }

        .container {
            width: 90%;
            max-width: 1150px;
            margin: 40px auto;
        }

        .hero {
            background: linear-gradient(135deg, #ff8a3d, #ff5c35);
            color: white;
            padding: 35px;
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(255, 92, 53, 0.25);
            margin-bottom: 30px;
        }

        .hero h1 {
            margin: 0;
            font-size: 36px;
        }

        .hero p {
            margin-top: 10px;
            font-size: 16px;
            max-width: 720px;
            line-height: 1.6;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 22px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            color: #d9480f;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #fed7aa;
            border-radius: 12px;
            outline: none;
            margin-bottom: 15px;
            font-size: 14px;
            background: #fffaf5;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #fb923c;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        button {
            border: none;
            cursor: pointer;
            border-radius: 12px;
            font-weight: bold;
        }

        .btn-submit {
            width: 100%;
            background: #f97316;
            color: white;
            padding: 13px;
            font-size: 15px;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }

        .schedule-list {
            display: grid;
            gap: 15px;
        }

        .schedule-item {
            background: #fff7ed;
            border: 2px solid #fed7aa;
            border-radius: 18px;
            padding: 18px;
        }

        .schedule-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            align-items: flex-start;
        }

        .schedule-info h3 {
            margin: 0 0 8px 0;
            color: #c2410c;
        }

        .schedule-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            background: #ffedd5;
            color: #9a3412;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
        }

        .status-belum {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-proses {
            background: #fef3c7;
            color: #92400e;
        }

        .status-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .schedule-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 9px 12px;
            transition: 0.2s;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: white;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
            padding: 9px 12px;
            transition: 0.2s;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .status-box {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
        }

        .status-box select {
            margin-bottom: 0;
        }

        .btn-status {
            background: #16a34a;
            color: white;
            padding: 12px 14px;
            white-space: nowrap;
        }

        .btn-status:hover {
            background: #15803d;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9a3412;
            background: #fff7ed;
            border-radius: 18px;
            border: 2px dashed #fdba74;
        }

        .empty-state h3 {
            margin-bottom: 5px;
        }

        /* Pop up notifikasi */
        .toast {
            position: fixed;
            top: 25px;
            right: 25px;
            background: #16a34a;
            color: white;
            padding: 14px 18px;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
            font-size: 14px;
            font-weight: bold;
            display: none;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }

        .toast.error {
            background: #dc2626;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Modal edit */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9998;
            padding: 20px;
        }

        .modal {
            width: 100%;
            max-width: 520px;
            background: white;
            border-radius: 22px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .modal h2 {
            margin-top: 0;
            color: #d9480f;
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
            padding: 13px;
            font-size: 15px;
        }

        .btn-save-edit {
            background: #f97316;
            color: white;
            padding: 13px;
            font-size: 15px;
        }

        @media (max-width: 850px) {
            .content {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 28px;
            }

            .schedule-top {
                flex-direction: column;
            }

            .schedule-actions {
                justify-content: flex-start;
            }

            .status-box {
                grid-template-columns: 1fr;
            }

            .toast {
                left: 20px;
                right: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="toast" id="toastBox"></div>

<!-- Modal edit jadwal -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h2>Edit Jadwal Masak</h2>

        <form id="editForm">
            <input type="hidden" id="edit_id">

            <label for="edit_menu">Nama Menu</label>
            <input type="text" id="edit_menu" name="menu">

            <label for="edit_date">Tanggal Masak</label>
            <input type="date" id="edit_date" name="date">

            <label for="edit_time">Jam Masak</label>
            <input type="time" id="edit_time" name="time">

            <label for="edit_category">Kategori</label>
            <select id="edit_category" name="category">
                <option value="">Pilih kategori</option>
                <option value="Sarapan">Sarapan</option>
                <option value="Makan Siang">Makan Siang</option>
                <option value="Makan Malam">Makan Malam</option>
                <option value="Meal Prep">Meal Prep</option>
                <option value="Camilan">Camilan</option>
            </select>

            <label for="edit_note">Catatan</label>
            <textarea id="edit_note" name="note"></textarea>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-save-edit">Simpan Edit</button>
            </div>
        </form>
    </div>
</div>

<div class="container">

    <div class="hero">
        <h1>🍳 Food Assistant</h1>
        <p>
            Aplikasi sederhana untuk mencatat jadwal masak harian agar kegiatan memasak lebih teratur.
            Jadwal bisa ditambah, diedit, dihapus, dan statusnya dapat diubah menggunakan AJAX tanpa reload halaman.
        </p>
    </div>

    <div class="content">

        <!-- Form tambah jadwal -->
        <div class="card">
            <h2>Tambah Jadwal Masak</h2>

            <form id="scheduleForm">
                <label for="menu">Nama Menu</label>
                <input type="text" id="menu" name="menu" placeholder="Contoh: Nasi Goreng Seafood">

                <label for="date">Tanggal Masak</label>
                <input type="date" id="date" name="date">

                <label for="time">Jam Masak</label>
                <input type="time" id="time" name="time">

                <label for="category">Kategori</label>
                <select id="category" name="category">
                    <option value="">Pilih kategori</option>
                    <option value="Sarapan">Sarapan</option>
                    <option value="Makan Siang">Makan Siang</option>
                    <option value="Makan Malam">Makan Malam</option>
                    <option value="Meal Prep">Meal Prep</option>
                    <option value="Camilan">Camilan</option>
                </select>

                <label for="note">Catatan</label>
                <textarea id="note" name="note" placeholder="Contoh: beli sayur dulu, masak untuk 2 porsi"></textarea>

                <button type="submit" class="btn-submit">+ Simpan Jadwal</button>
            </form>
        </div>

        <!-- Daftar jadwal -->
        <div class="card">
            <h2>Daftar Jadwal Masak</h2>

            <div class="schedule-list" id="scheduleList">
                @forelse ($schedules as $schedule)
                    <div class="schedule-item" id="schedule-{{ $schedule['id'] }}">
                        <div class="schedule-top">
                            <div class="schedule-info">
                                <span class="badge">{{ $schedule['category'] }}</span>

                                <h3>{{ $schedule['menu'] }}</h3>

                                <p>📅 {{ $schedule['date'] }} | ⏰ {{ $schedule['time'] }}</p>

                                <p>📝 {{ $schedule['note'] ?: 'Tidak ada catatan' }}</p>

                                @php
                                    $status = $schedule['status'] ?? 'Belum dimasak';

                                    if ($status == 'Selesai') {
                                        $statusClass = 'status-selesai';
                                    } elseif ($status == 'Sedang dimasak') {
                                        $statusClass = 'status-proses';
                                    } else {
                                        $statusClass = 'status-belum';
                                    }
                                @endphp

                                <span class="status-badge {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>

                            <div class="schedule-actions">
                                <button
                                    class="btn-edit"
                                    onclick="openEditModal(
                                        {{ $schedule['id'] }},
                                        '{{ addslashes($schedule['menu']) }}',
                                        '{{ $schedule['date'] }}',
                                        '{{ $schedule['time'] }}',
                                        '{{ $schedule['category'] }}',
                                        '{{ addslashes($schedule['note'] ?? '') }}'
                                    )">
                                    Edit
                                </button>

                                <button class="btn-delete" onclick="deleteSchedule({{ $schedule['id'] }})">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <div class="status-box">
                            <select id="status-{{ $schedule['id'] }}">
                                <option value="Belum dimasak" {{ $status == 'Belum dimasak' ? 'selected' : '' }}>Belum dimasak</option>
                                <option value="Sedang dimasak" {{ $status == 'Sedang dimasak' ? 'selected' : '' }}>Sedang dimasak</option>
                                <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>

                            <button class="btn-status" onclick="updateStatus({{ $schedule['id'] }})">
                                Ubah Status
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" id="emptyState">
                        <h3>Belum ada jadwal masak</h3>
                        <p>Tambahkan jadwal pertama kamu lewat form di samping.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    const form = document.getElementById('scheduleForm');
    const editForm = document.getElementById('editForm');
    const scheduleList = document.getElementById('scheduleList');
    const editModal = document.getElementById('editModal');
    const toastBox = document.getElementById('toastBox');

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("{{ route('food.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }

            return response.json();
        })
        .then(result => {
            const emptyState = document.getElementById('emptyState');

            if (emptyState) {
                emptyState.remove();
            }

            const item = createScheduleItem(result.data);
            scheduleList.insertAdjacentHTML('beforeend', item);

            form.reset();

            showToast(result.message);
        })
        .catch(async error => {
            let message = 'Data belum lengkap. Pastikan semua kolom wajib sudah diisi.';

            if (error.json) {
                const err = await error.json();

                if (err.message) {
                    message = err.message;
                }
            }

            showToast(message, true);
        });
    });

    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('edit_id').value;
        const formData = new FormData(editForm);

        formData.append('_method', 'PUT');

        fetch(`/food-schedule/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }

            return response.json();
        })
        .then(result => {
            const oldItem = document.getElementById(`schedule-${id}`);

            if (oldItem) {
                oldItem.outerHTML = createScheduleItem(result.data);
            }

            closeEditModal();

            showToast(result.message);
        })
        .catch(async error => {
            let message = 'Gagal mengedit jadwal. Pastikan data sudah lengkap.';

            if (error.json) {
                const err = await error.json();

                if (err.message) {
                    message = err.message;
                }
            }

            showToast(message, true);
        });
    });

    function createScheduleItem(schedule) {
        const note = schedule.note ? schedule.note : 'Tidak ada catatan';
        const status = schedule.status ? schedule.status : 'Belum dimasak';
        const statusClass = getStatusClass(status);

        return `
            <div class="schedule-item" id="schedule-${schedule.id}">
                <div class="schedule-top">
                    <div class="schedule-info">
                        <span class="badge">${escapeHtml(schedule.category)}</span>

                        <h3>${escapeHtml(schedule.menu)}</h3>

                        <p>📅 ${escapeHtml(schedule.date)} | ⏰ ${escapeHtml(schedule.time)}</p>

                        <p>📝 ${escapeHtml(note)}</p>

                        <span class="status-badge ${statusClass}">
                            ${escapeHtml(status)}
                        </span>
                    </div>

                    <div class="schedule-actions">
                        <button
                            class="btn-edit"
                            onclick="openEditModal(
                                ${schedule.id},
                                '${escapeForJs(schedule.menu)}',
                                '${escapeForJs(schedule.date)}',
                                '${escapeForJs(schedule.time)}',
                                '${escapeForJs(schedule.category)}',
                                '${escapeForJs(schedule.note ?? '')}'
                            )">
                            Edit
                        </button>

                        <button class="btn-delete" onclick="deleteSchedule(${schedule.id})">
                            Hapus
                        </button>
                    </div>
                </div>

                <div class="status-box">
                    <select id="status-${schedule.id}">
                        <option value="Belum dimasak" ${status === 'Belum dimasak' ? 'selected' : ''}>Belum dimasak</option>
                        <option value="Sedang dimasak" ${status === 'Sedang dimasak' ? 'selected' : ''}>Sedang dimasak</option>
                        <option value="Selesai" ${status === 'Selesai' ? 'selected' : ''}>Selesai</option>
                    </select>

                    <button class="btn-status" onclick="updateStatus(${schedule.id})">
                        Ubah Status
                    </button>
                </div>
            </div>
        `;
    }

    function openEditModal(id, menu, date, time, category, note) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_menu').value = menu;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_time').value = time;
        document.getElementById('edit_category').value = category;
        document.getElementById('edit_note').value = note;

        editModal.style.display = 'flex';
    }

    function closeEditModal() {
        editModal.style.display = 'none';
    }

    function updateStatus(id) {
        const selectedStatus = document.getElementById(`status-${id}`).value;
        const formData = new FormData();

        formData.append('status', selectedStatus);
        formData.append('_method', 'PATCH');

        fetch(`/food-schedule/${id}/status`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            const oldItem = document.getElementById(`schedule-${id}`);

            if (oldItem) {
                oldItem.outerHTML = createScheduleItem(result.data);
            }

            showToast(result.message);
        })
        .catch(() => {
            showToast('Gagal mengubah status jadwal.', true);
        });
    }

    function deleteSchedule(id) {
        fetch(`/food-schedule/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(result => {
            const item = document.getElementById(`schedule-${id}`);

            if (item) {
                item.remove();
            }

            if (scheduleList.children.length === 0) {
                scheduleList.innerHTML = `
                    <div class="empty-state" id="emptyState">
                        <h3>Belum ada jadwal masak</h3>
                        <p>Tambahkan jadwal pertama kamu lewat form di samping.</p>
                    </div>
                `;
            }

            showToast(result.message);
        })
        .catch(() => {
            showToast('Gagal menghapus jadwal.', true);
        });
    }

    function getStatusClass(status) {
        if (status === 'Selesai') {
            return 'status-selesai';
        }

        if (status === 'Sedang dimasak') {
            return 'status-proses';
        }

        return 'status-belum';
    }

    function showToast(message, isError = false) {
        toastBox.textContent = message;
        toastBox.classList.toggle('error', isError);
        toastBox.style.display = 'block';

        setTimeout(() => {
            toastBox.style.display = 'none';
        }, 2500);
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) {
            return '';
        }

        return String(text)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function escapeForJs(text) {
        if (text === null || text === undefined) {
            return '';
        }

        return String(text)
            .replaceAll('\\', '\\\\')
            .replaceAll("'", "\\'")
            .replaceAll('"', '&quot;')
            .replaceAll('\n', ' ')
            .replaceAll('\r', ' ');
    }
</script>

</body>
</html>
