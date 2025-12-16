<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Puskesmas</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 bg-slate-900 dark:bg-slate-950 text-slate-100
               p-6 transition-all duration-300">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold sidebar-text">Puskesmas</h2>

            <button onclick="toggleSidebar()"
                class="p-2 rounded text-slate-300
                       hover:bg-slate-700 hover:text-white transition">
                <span id="menuIcon"></span>
            </button>
        </div>

        <ul class="space-y-3 text-sm">
            <li class="bg-slate-800 dark:bg-slate-700 px-3 py-2 rounded sidebar-text">
                Dashboard
            </li>
            <li class="opacity-80 hover:opacity-100 sidebar-text">Kunjungan</li>
            <li class="opacity-80 hover:opacity-100 sidebar-text">Laporan</li>
        </ul>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Dashboard Puskesmas</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Ringkasan kunjungan pasien
                </p>
            </div>

            <!-- TOGGLE THEME -->
            <div class="flex items-center gap-3">
                <span id="modeText" class="text-sm text-slate-600 dark:text-slate-300">
                    Light Mode
                </span>

                <button id="themeToggle"
                    class="flex items-center justify-center
                        w-10 h-10 rounded-full
                        bg-slate-200 dark:bg-slate-700
                        hover:bg-slate-300 dark:hover:bg-slate-600
                        transition">
                    <span id="modeIcon"></span>
                </button>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET"
            class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow mb-6">

            <h3 class="text-sm font-semibold mb-4">Filter Data Kunjungan</h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                <div>
                    <label class="block text-xs mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full px-3 py-2 rounded border
                               bg-white dark:bg-slate-700
                               border-slate-300 dark:border-slate-600">
                </div>

                <div>
                    <label class="block text-xs mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 rounded border
                               bg-white dark:bg-slate-700
                               border-slate-300 dark:border-slate-600">
                </div>

                <div>
                    <label class="block text-xs mb-1">Poli</label>
                    <select name="poli"
                        class="w-full px-3 py-2 rounded border
                               bg-white dark:bg-slate-700
                               border-slate-300 dark:border-slate-600">
                        <option value="">Semua Poli</option>
                        @foreach($listPoli as $p)
                            <option value="{{ $p }}"
                                {{ request('poli') == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="bg-slate-900 dark:bg-slate-700
                           text-white px-4 py-2 rounded font-semibold
                           hover:opacity-90 transition">
                    Terapkan
                </button>
            </div>
        </form>

        <!-- SUMMARY -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">

            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow">
                <p class="text-sm opacity-70">Total Kunjungan</p>
                <p class="text-xl font-bold">
                    {{ array_sum($values->toArray()) }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow">
                <p class="text-sm opacity-70">Rata-rata / Hari</p>
                <p class="text-xl font-bold">
                    {{ round($values->avg(), 1) }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow">
                <p class="text-sm opacity-70">Tertinggi</p>
                <p class="text-xl font-bold">
                    {{ $values->max() }}
                </p>
            </div>
        </div>

        <!-- CHART -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow">
                <h2 class="font-bold mb-2">Kunjungan Harian</h2>
                <div class="h-64">
                    <canvas id="kunjunganChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow">
                <h2 class="font-bold mb-2">Kunjungan per Poli</h2>
                <div class="h-64">
                    <canvas id="poliChart"></canvas>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
const labels = {!! json_encode($labels) !!};
const values = {!! json_encode($values) !!};
const poliLabels = {!! json_encode($poliLabels) !!};
const poliValues = {!! json_encode($poliValues) !!};

let kunjunganChart, poliChart;

function initCharts() {
    if (kunjunganChart) kunjunganChart.destroy();
    if (poliChart) poliChart.destroy();

    const dark = document.documentElement.classList.contains('dark');
    const textColor = dark ? '#e5e7eb' : '#0f172a';
    const gridColor = dark ? '#334155' : '#e5e7eb';

    kunjunganChart = new Chart(kunjunganChartCanvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: values,
                borderColor: textColor,
                backgroundColor: dark
                    ? 'rgba(148,163,184,.2)'
                    : 'rgba(15,23,42,.15)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor }},
                y: { ticks: { color: textColor }, grid: { color: gridColor }}
            }
        }
    });

    poliChart = new Chart(poliChartCanvas, {
        type: 'bar',
        data: {
            labels: poliLabels,
            datasets: [{
                label: 'Total Kunjungan',
                data: poliValues,
                backgroundColor: dark ? '#94a3b8' : '#1e293b'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor }},
                y: { ticks: { color: textColor }, grid: { color: gridColor }}
            }
        }
    });
}

/* SIDEBAR */
function toggleSidebar() {
    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('w-20');
    document.querySelectorAll('.sidebar-text')
        .forEach(el => el.classList.toggle('hidden'));
}

/* THEME TOGGLE */
const toggleBtn = document.getElementById('themeToggle');
const modeText = document.getElementById('modeText');
const modeIcon = document.getElementById('modeIcon');

function setTheme(isDark) {
    document.documentElement.classList.toggle('dark', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    modeText.innerText = isDark ? 'Dark Mode' : 'Light Mode';

    // 🔥 Render SVG langsung
    modeIcon.innerHTML = feather.icons[isDark ? 'moon' : 'sun'].toSvg({
        width: 20,
        height: 20
    });

    initCharts();
}

// Toggle dark/light mode
toggleBtn.addEventListener('click', () => {
    const isDark = document.documentElement.classList.contains('dark');
    setTheme(!isDark);
});

// Saat halaman load
window.addEventListener('DOMContentLoaded', () => {
    window.kunjunganChartCanvas = document.getElementById('kunjunganChart');
    window.poliChartCanvas = document.getElementById('poliChart');

    // 🔥 render icon menu sidebar
    document.getElementById('menuIcon').innerHTML =
        feather.icons.menu.toSvg({ width: 20, height: 20 });

    const savedTheme = localStorage.getItem('theme') === 'dark';
    setTheme(savedTheme);
});

</script>

</body>
</html>
