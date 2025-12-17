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

    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        cursor: pointer;
        color: #cbd5f5;
        transition: all 0.2s ease;
    }
    .sidebar-item:hover {
        background: #1e293b;
        color: #ffffff;
    }
    .sidebar-item.active {
        background: #4f46e5;
        color: white;
        box-shadow: 0 4px 10px rgba(79,70,229,.3);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn .5s ease-out;
    }

    .menu-icon svg {
        width: 20px;
        height: 20px;
    }
    /* MODE ICON ONLY */
    .icon-only .sidebar-item {
        padding: 0.5rem;
    }

    .icon-only .sidebar-item.active {
        background: transparent;
        box-shadow: none;
    }

    /* wrapper icon */
    .icon-only .menu-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        transition: all .25s ease;
    }

    .icon-only .sidebar-item.active .menu-icon {
        background: #6366f1;
        box-shadow: 0 10px 24px rgba(99,102,241,.45);
    }

    .icon-only .sidebar-item:hover .menu-icon {
        background: #1e293b;
        transform: scale(1.05);
    }

    </style>

</head>

<body class="bg-gray-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition">
<body class="bg-gray-100 dark:bg-slate-900 transition overflow-x-hidden">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 bg-slate-900 dark:bg-slate-950 text-slate-100
            p-6 transition-all duration-300
            shadow-xl">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold sidebar-text">Puskesmas</h2>

            <button onclick="toggleSidebar()"
                class="p-2 rounded text-slate-300
                       hover:bg-slate-700 hover:text-white transition">
                <span id="menuIcon"></span>
            </button>
        </div>

        <ul class="space-y-1 text-sm">
            <li class="sidebar-item active justify-center md:justify-start">
                <!-- ICON (LOGO MENU) -->
                <span class="menu-icon hidden">
                    <i data-feather="home"></i>
                </span>

                <!-- TEXT -->
                <span class="sidebar-text">
                    Dashboard
                </span>
            </li>

            <li class="sidebar-item justify-center md:justify-start">
                <span class="menu-icon hidden">
                    <i data-feather="activity"></i>
                </span>
                <span class="sidebar-text">Kunjungan</span>
            </li>

            <li class="sidebar-item justify-center md:justify-start">
                <span class="menu-icon hidden">
                    <i data-feather="file-text"></i>
                </span>
                <span class="sidebar-text">Laporan</span>
            </li>

        </ul>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Dashboard Puskesmas Taman</h1>
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
        <form method="GET" id="filterForm"
            class="bg-white dark:bg-slate-800
            p-4 rounded-2xl shadow-md
            border border-slate-200 dark:border-slate-700
            mb-4">

            <div class="flex items-center gap-2 mb-3">
                <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-slate-700 dark:text-slate-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold tracking-wide">
                    Filter Data Kunjungan
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div class="relative">
                    <label class="block text-xs mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full pl-10 pr-3 py-2.5 rounded-xl text-sm
                            border border-slate-300 dark:border-slate-600
                            bg-white dark:bg-slate-700
                            focus:ring-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 absolute left-3 top-9 text-slate-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <div class="relative">
                    <label class="block text-xs mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full pl-10 pr-3 py-2.5 rounded-xl text-sm
                            border border-slate-300 dark:border-slate-600
                            bg-white dark:bg-slate-700
                            focus:ring-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 absolute left-3 top-9 text-slate-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <div>
                    <label class="block text-xs mb-1">Poli</label>
                    <select name="poli"
                        class="w-full px-3 py-2.5 rounded-lg
                                text-sm
                                border border-slate-300 dark:border-slate-600
                                bg-white dark:bg-slate-700
                                focus:ring-2 focus:ring-slate-500
                                focus:outline-none transition">
                        <option value="">Semua Poli</option>
                        @foreach($listPoli as $p)
                            <option value="{{ $p }}"
                                {{ request('poli') == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </form>

        <!-- SUMMARY -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            <!-- TOTAL -->
            <div class="bg-white dark:bg-slate-800
                p-5 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700
                hover:shadow-md transition">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide opacity-70">
                            Total Kunjungan
                        </p>
                        <p class="text-2xl font-bold mt-1" id="totalKunjungan"">
                            {{ array_sum($values->toArray()) }}
                        </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5V4H2v16h5m10 0v-4H7v4m10 0H7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- RATA-RATA -->
            <div class="bg-white dark:bg-slate-800
                p-5 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700
                hover:shadow-md transition">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide opacity-70">
                            Rata-rata / Hari
                        </p>
                        <p class="text-2xl font-bold mt-1" id="rataRata">
                            {{ round($values->avg(), 1) }}
                        </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TERTINGGI -->
            <div class="bg-white dark:bg-slate-800
                p-5 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700
                hover:shadow-md transition">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide opacity-70">
                            Kunjungan Tertinggi
                        </p>
                        <p class="text-2xl font-bold mt-1" id="kunjunganTertinggi">
                            {{ $values->max() }}
                        </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-rose-50 dark:bg-rose-900/30">
                        <svg class="w-6 h-6 text-rose-600 dark:text-rose-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- CHART -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow animate-fade-in">
                <h2 class="font-bold mb-2">Kunjungan Harian</h2>
                <div class="h-64">
                    <canvas id="kunjunganChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded shadow animate-fade-in">
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
const datasetsPoli = {!! json_encode($datasetsPoli) !!};
const poliLabels = {!! json_encode($poliLabels) !!};
const poliValues = {!! json_encode($poliValues) !!};
const poliColors = {
    Umum:        '#1e3a8a', // navy utama
    Gigi:        '#2563eb', // blue
    KIA:         '#4f46e5', // indigo
    Lansia:      '#6d28d9', // slate
    TB:          '#0f766e', // teal gelap
    Imunisasi:   '#0284c7', // sky blue
};

// document.querySelectorAll('#filterForm input, #filterForm select')
//     .forEach(el => {
//         el.addEventListener('change', () => {
//             document.getElementById('filterForm').submit();
//         });
//     });
let filterTimeout = null;

function debounceFilter(callback, delay = 500) {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(callback, delay);
}

document
    .querySelectorAll('#filterForm input, #filterForm select')
    .forEach(el => {
        el.addEventListener('input', () => {
            debounceFilter(runFilter);
        });

        el.addEventListener('change', () => {
            debounceFilter(runFilter);
        });
    });

function runFilter() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();

    fetch(`{{ route('dashboard.data') }}?${params}`)
        .then(res => res.json())
        .then(data => {

            // ===== UPDATE LINE CHART =====
            kunjunganChart.data.labels = data.labels;
            kunjunganChart.data.datasets = data.datasetsPoli.map(ds => ({
                ...ds,
                fill: true
            }));
            kunjunganChart.update('active');

            // ===== UPDATE BAR CHART =====
            poliChart.data.labels = data.poliLabels;
            poliChart.data.datasets[0].data = data.poliValues;
            poliChart.data.datasets[0].backgroundColor = data.poliLabels.map(
                poli => poliColors[poli] ?? '#334155'
            );
            poliChart.update('active');

            // ===== UPDATE SUMMARY CARD =====
            document.getElementById('totalKunjungan').innerText = data.summary.total;
            document.getElementById('rataRata').innerText = data.summary.avg;
            document.getElementById('kunjunganTertinggi').innerText = data.summary.max;
        });
}

let kunjunganChart, poliChart;

function lineGradient(ctx, dark, color) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, dark ? color + '66' : color + '55');
    gradient.addColorStop(1, 'transparent');
    return gradient;
}

function tooltipOptions(textColor) {
    return {
        backgroundColor: '#020617',
        titleColor: '#e5e7eb',
        bodyColor: '#e5e7eb',
        borderWidth: 1,
        borderColor: '#334155',
        padding: 10,
        callbacks: {
            label: ctx => ` ${ctx.dataset.label}: ${ctx.raw} pasien`
        }
    }
}

function initCharts() {
    if (kunjunganChart || poliChart) return;

    const dark = document.documentElement.classList.contains('dark');
    const textColor = dark ? '#e5e7eb' : '#0f172a';
    const gridColor = dark ? '#334155' : '#e5e7eb';

    kunjunganChart = new Chart(kunjunganChartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasetsPoli.map(ds => {
                const ctx = kunjunganChartCanvas.getContext('2d');
                return {
                    ...ds,
                    fill: true,
                    backgroundColor: lineGradient(ctx, dark, ds.borderColor),
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }
            })
        },
        options: {
            responsive: true,
            animation: {
                duration: 600,
                easing: 'easeInOutCubic'
            },
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: textColor,
                        boxWidth: 10,
                        font: { size: window.innerWidth < 640 ? 10 : 12 }
                    }
                },
                tooltip: tooltipOptions(textColor)
            },
            scales: {
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                }
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
                backgroundColor: poliLabels.map(
                    poli => poliColors[poli] ?? '#334155'
                ),
                borderRadius: 8,
                barThickness: 60
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 600,
                easing: 'easeInOutCubic'
            },
            transitions: {
                active: {
                    animation: {
                        duration: 400
                    }
                }
            },
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipOptions(textColor)
            },
            scales: {
                x: {
                    ticks: {
                        color: textColor,
                        font: { size: window.innerWidth < 640 ? 10 : 12 }
                    },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: {
                        color: textColor,
                        font: { size: 11 }
                    },
                    grid: { color: gridColor }
                }
            }
        }
    });
}

function updateChartTheme() {
    const dark = document.documentElement.classList.contains('dark');
    const textColor = dark ? '#e5e7eb' : '#0f172a';
    const gridColor = dark ? '#334155' : '#e5e7eb';

    // LINE CHART
    kunjunganChart.options.scales.x.ticks.color = textColor;
    kunjunganChart.options.scales.y.ticks.color = textColor;
    kunjunganChart.options.scales.x.grid.color = gridColor;
    kunjunganChart.options.scales.y.grid.color = gridColor;
    kunjunganChart.options.plugins.legend.labels.color = textColor;

    kunjunganChart.data.datasets.forEach(ds => {
        const ctx = kunjunganChartCanvas.getContext('2d');
        ds.backgroundColor = lineGradient(ctx, dark, ds.borderColor);
    });

    kunjunganChart.update('none');

    // BAR CHART
    poliChart.options.scales.x.ticks.color = textColor;
    poliChart.options.scales.y.ticks.color = textColor;
    poliChart.options.scales.x.grid.color = gridColor;
    poliChart.options.scales.y.grid.color = gridColor;

    poliChart.update('none');
}

function toggleSidebar() {
    sidebar.classList.toggle('w-64');
    sidebar.classList.toggle('w-20');

    sidebar.classList.toggle('icon-only');

    // Toggle text
    document.querySelectorAll('.sidebar-text')
        .forEach(el => el.classList.toggle('hidden'));

    // Toggle icon
    document.querySelectorAll('.menu-icon')
        .forEach(el => el.classList.toggle('hidden'));

    // Rata tengah
    document.querySelectorAll('.sidebar-item')
        .forEach(el => el.classList.toggle('justify-center'));
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

    updateChartTheme();
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

    // render icon tombol sidebar
    document.getElementById('menuIcon').innerHTML =
        feather.icons.menu.toSvg({ width: 20, height: 20 });
    
    feather.replace();

    // 🔥 INIT CHART PERTAMA KALI
    initCharts();

    const savedTheme = localStorage.getItem('theme') === 'dark';
    setTheme(savedTheme);
});

</script>

</body>
</html>
