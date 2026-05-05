<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .chart-container { position: relative; height: 300px; width: 100%; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased">
    <div class="min-h-full flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <div class="bg-indigo-600 p-1.5 rounded-lg">
                            <i data-lucide="bar-chart-3" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">SalesAnalytics</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
                        <div class="hidden sm:flex items-center gap-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>{{ date('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Page Header -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Dashboard Overview</h1>
                        <p class="mt-1 text-sm text-slate-500">Monitor your sales performance and environmental impact insights.</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-3">
                        <a href="{{ route('sales.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export Data
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Total Records</p>
                            <i data-lucide="database" class="w-5 h-5 text-indigo-500"></i>
                        </div>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ count($sales) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Average Sales</p>
                            <i data-lucide="trending-up" class="w-5 h-5 text-emerald-500"></i>
                        </div>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ round($sales->avg('penjualan')) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Avg Rainfall</p>
                            <i data-lucide="cloud-rain" class="w-5 h-5 text-blue-500"></i>
                        </div>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ round($sales->avg('curah_hujan'), 1) }}mm</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">Active Events</p>
                            <i data-lucide="calendar-check" class="w-5 h-5 text-amber-500"></i>
                        </div>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $sales->where('kegiatan', 1)->count() }}</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sales Trend -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-slate-900">Sales Trend</h2>
                            <span class="text-xs font-medium px-2 py-1 bg-slate-100 text-slate-600 rounded">Daily performance</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <!-- Event Impact -->
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900 mb-6">Event Impact</h2>
                            <div class="chart-container" style="height: 150px;">
                                <canvas id="eventChart"></canvas>
                            </div>
                        </div>
                        <!-- Rain Impact -->
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900 mb-6">Rainfall Impact</h2>
                            <div class="chart-container" style="height: 150px;">
                                <canvas id="rainChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Raw Dataset</h2>
                        <div class="flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs text-slate-500">Sorted by Date</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="salesTable">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors group" onclick="sortTable(0)">
                                        <div class="flex items-center gap-1">
                                            Day
                                            <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-300 group-hover:text-slate-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors group" onclick="sortTable(1)">
                                        <div class="flex items-center gap-1">
                                            Date
                                            <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-300 group-hover:text-slate-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors group" onclick="sortTable(2)">
                                        <div class="flex items-center gap-1">
                                            Event Status
                                            <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-300 group-hover:text-slate-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors group" onclick="sortTable(3)">
                                        <div class="flex items-center gap-1">
                                            Rainfall
                                            <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-300 group-hover:text-slate-500"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider cursor-pointer hover:bg-slate-100 transition-colors group" onclick="sortTable(4)">
                                        <div class="flex items-center gap-1">
                                            Sales
                                            <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-300 group-hover:text-slate-500"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="tableBody">
                                @foreach ($sales as $sale)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $sale->hari }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $sale->tanggal }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($sale->kegiatan == 1)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $sale->curah_hujan }}mm</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $sale->penjualan }} pcs</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-6">
            <div class="px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} SalesAnalytics. Professional Data Visualization.
            </div>
        </footer>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Table Sorting Logic
        let sortDirections = [true, true, true, true, true];
        function sortTable(columnIndex) {
            const table = document.getElementById("salesTable");
            const body = document.getElementById("tableBody");
            const rows = Array.from(body.rows);
            const isNumeric = [0, 1, 3, 4].includes(columnIndex);
            
            const sortedRows = rows.sort((a, b) => {
                let valA = a.cells[columnIndex].innerText;
                let valB = b.cells[columnIndex].innerText;
                
                if (isNumeric) {
                    valA = parseFloat(valA.replace(/[^\d.-]/g, '')) || 0;
                    valB = parseFloat(valB.replace(/[^\d.-]/g, '')) || 0;
                    return sortDirections[columnIndex] ? valA - valB : valB - valA;
                }
                
                return sortDirections[columnIndex] 
                    ? valA.localeCompare(valB) 
                    : valB.localeCompare(valA);
            });
            
            sortDirections[columnIndex] = !sortDirections[columnIndex];
            
            // Re-render rows
            while (body.firstChild) {
                body.removeChild(body.firstChild);
            }
            body.append(...sortedRows);
        }

        // Chart defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#64748b';
        Chart.defaults.scale.grid.color = '#f1f5f9';
        Chart.defaults.plugins.tooltip.backgroundColor = '#1e293b';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;

        // 1. Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($trendDates) !!},
                datasets: [{
                    label: 'Sales (pcs)',
                    data: {!! json_encode($trendSales) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true,
                        ticks: { stepSize: 100 }
                    }
                }
            }
        });

        // 2. Event Impact
        new Chart(document.getElementById('eventChart'), {
            type: 'bar',
            data: {
                labels: ['With Event', 'Without Event'],
                datasets: [{
                    data: [{{ $salesWithEvent }}, {{ $salesWithoutEvent }}],
                    backgroundColor: ['#10b981', '#94a3b8'],
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { display: false } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 3. Rain Impact
        new Chart(document.getElementById('rainChart'), {
            type: 'bar',
            data: {
                labels: ['High Rain', 'Low Rain'],
                datasets: [{
                    data: [{{ $salesHighRain }}, {{ $salesLowRain }}],
                    backgroundColor: ['#3b82f6', '#f59e0b'],
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { display: false } },
                    y: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>
