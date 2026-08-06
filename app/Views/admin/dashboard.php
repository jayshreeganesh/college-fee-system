<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Custom scrollbar for inner elements */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #334155;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 transition-colors duration-300 min-h-screen lg:h-screen lg:overflow-hidden flex flex-col">
    <!-- Navbar (Fixed height) -->
    <nav class="bg-slate-900 dark:bg-slate-950 text-white shadow-md p-4 shrink-0 transition-colors duration-300 z-50">
        <div class="max-w-[1920px] mx-auto flex justify-between items-center px-4">
            <h1 class="text-xl md:text-2xl font-bold tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                <span class="hidden sm:inline">Admin Dashboard</span>
            </h1>
            <div class="flex items-center gap-4 md:gap-6">
                <button onclick="toggleDarkMode()" class="p-2 rounded-full hover:bg-slate-800 transition-colors" title="Toggle Dark Mode">
                    <svg id="moon-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="sun-icon" class="w-5 h-5 hidden text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
                <span class="text-sm font-semibold">Welcome, <span class="text-indigo-400"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'Admin'); ?></span></span>
                <a href="/logout" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg transition-colors text-sm font-semibold">Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Main 100vh App Layout -->
    <div class="flex-1 lg:overflow-hidden p-4 md:p-6 max-w-[1920px] mx-auto w-full flex flex-col lg:flex-row gap-6">
        
        <!-- Left Sidebar: Navigation & Operations (Scrollable on Desktop) -->
        <div class="w-full lg:w-[280px] shrink-0 flex flex-col gap-6 lg:overflow-y-auto custom-scrollbar lg:pr-2 lg:pb-6">
            
            <!-- Quick Navigation Menu -->
            <div class="bg-white dark:bg-slate-800 dark:border-slate-700 rounded-3xl shadow-sm border border-slate-100 p-4 flex flex-col gap-2">
                <h3 class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 px-2">Navigation</h3>
                
                <?php if (($_SESSION['admin_role'] ?? '') === 'super_admin'): ?>
                <a href="/admin/student/add" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-blue-50 dark:hover:bg-blue-900/30 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Add Student
                </a>
                <a href="/admin/student/import" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Students
                </a>
                <a href="/admin/payment" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Payment
                </a>
                <a href="/admin/fees" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-fuchsia-50 dark:hover:bg-fuchsia-900/30 text-slate-700 dark:text-slate-300 hover:text-fuchsia-600 dark:hover:text-fuchsia-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Manage Fees
                </a>
                <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-amber-50 dark:hover:bg-amber-900/30 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manage Users
                </a>
                <a href="/admin/emails" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-amber-50 dark:hover:bg-amber-900/30 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email Logs
                </a>
                <a href="/admin/settings" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                <a href="/admin/audit-logs" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-red-50 dark:hover:bg-red-900/30 text-slate-700 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Audit Logs
                </a>
                <?php endif; ?>
                
                <a href="/admin/reports" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-purple-50 dark:hover:bg-purple-900/30 text-slate-700 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Reports
                </a>
                <a href="/admin/export" class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 font-bold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Data
                </a>
            </div>

            <!-- Database Operations -->
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 p-4 rounded-3xl flex flex-col items-center text-center">
                <h4 class="text-sm font-bold text-indigo-800 dark:text-indigo-300 mb-2">System Operations</h4>
                <a href="/admin/backup" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors mb-2">Download SQLite DB</a>
                <a href="/admin/seed-demo-data" onclick="return confirm('This will instantly generate 15 demo students and transactions. Proceed?')" class="w-full py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200 dark:hover:bg-indigo-700 text-sm font-bold rounded-lg transition-colors mb-2">Seed Demo Data</a>
                <a href="/admin/export-project" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">Export Project ZIP</a>
            </div>
            
            <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 p-4 rounded-3xl flex flex-col items-center text-center <?php echo (($_SESSION['admin_role'] ?? '') !== 'super_admin') ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''; ?>">
                <h4 class="text-sm font-bold text-rose-800 dark:text-rose-300 mb-2">Restore Database</h4>
                <form action="/admin/restore" method="POST" enctype="multipart/form-data" class="w-full flex flex-col gap-2">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="file" name="database_file" accept=".sqlite" class="w-full overflow-hidden text-xs text-slate-500 file:mr-4 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200" required>
                    <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">Restore DB</button>
                </form>
            </div>
        </div>

        <!-- Middle Column: Metrics & Analytics (Scrollable on Desktop) -->
        <div class="w-full lg:flex-1 flex flex-col gap-6 lg:overflow-y-auto custom-scrollbar lg:pr-2 lg:pb-6 min-w-0 lg:min-w-[400px]">
            
            <!-- Welcome Header -->
            <div class="shrink-0">
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Overview</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Real-time statistics for the College Fee System</p>
            </div>

            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 shrink-0">
                <div class="bg-white dark:bg-slate-800 dark:border-slate-700 p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Students</h3>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-1"><?php echo htmlspecialchars($studentCount); ?></p>
                </div>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 rounded-3xl shadow-md text-white flex flex-col justify-center">
                    <h3 class="text-xs font-bold opacity-80 uppercase tracking-wider">Total Collected</h3>
                    <p class="text-3xl font-extrabold mt-1">$<?php echo number_format($totalCollected, 2); ?></p>
                </div>
                <div class="bg-white dark:bg-slate-800 dark:border-slate-700 p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Dues</h3>
                    <p class="text-3xl font-extrabold text-rose-500 mt-1">$<?php echo number_format($totalPending, 2); ?></p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white dark:bg-slate-800 dark:border-slate-700 p-6 rounded-3xl shadow-sm border border-slate-100 flex-1 flex flex-col min-h-[400px]">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2 shrink-0">Revenue by Fee Category</h3>
                <div id="revenueChart" class="w-full flex-1 dark:opacity-90 min-h-[320px] pb-6"></div>
            </div>
        </div>

        <!-- Right Column: Transactions List (Fixed Height Desktop, Auto Mobile) -->
        <div class="w-full lg:w-[450px] shrink-0 bg-white dark:bg-slate-800 dark:border-slate-700 rounded-3xl shadow-sm border border-slate-100 flex flex-col lg:overflow-hidden mb-6 lg:mb-0">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 shrink-0 rounded-t-3xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Recent Transactions</h3>
            </div>
            
            <!-- List Container (Scrollable on Desktop) -->
            <div class="lg:flex-1 lg:overflow-y-auto custom-scrollbar p-2">
                <?php if (empty($recentTransactions)): ?>
                    <div class="p-8 text-center text-slate-500 dark:text-slate-400">No transactions found.</div>
                <?php else: ?>
                    <div class="flex flex-col gap-2">
                        <?php foreach ($recentTransactions as $tx): ?>
                        <div class="p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-600">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-slate-800 dark:text-white"><?php echo htmlspecialchars($tx['student_name']); ?></span>
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">$<?php echo number_format($tx['amount'], 2); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 dark:text-slate-400 truncate pr-2"><?php echo htmlspecialchars($tx['fee_name']); ?></span>
                                <?php if ($tx['status'] === 'paid'): ?>
                                    <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold rounded uppercase text-[10px] tracking-wider shrink-0">Paid</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold rounded uppercase text-[10px] tracking-wider shrink-0">Pending</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs">
                                <span class="text-slate-400 dark:text-slate-500"><?php echo htmlspecialchars(date('M j, Y', strtotime($tx['created_at']))); ?></span>
                                <?php if ($tx['status'] === 'paid'): ?>
                                    <a href="/receipt?id=<?php echo $tx['id']; ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-bold" target="_blank">Receipt &rarr;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: <?php echo $chartSeries; ?>,
                labels: <?php echo $chartLabels; ?>,
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    background: 'transparent'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    color: (document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#64748b')
                                },
                                value: {
                                    color: (document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b')
                                },
                                total: {
                                    show: true,
                                    label: 'Revenue',
                                    color: (document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#64748b'),
                                    formatter: function (w) {
                                        return '$' + w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0).toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                },
                theme: {
                    mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                },
                colors: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                dataLabels: { enabled: false },
                legend: { 
                    position: 'bottom',
                    offsetY: 8,
                    itemMargin: { horizontal: 10, vertical: 5 }
                }
            };

            var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();

            // Observe dark mode changes to update chart theme
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            theme: { mode: isDark ? 'dark' : 'light' }
                        });
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
    <script>
        // Dark Mode Logic
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcons(isDark);
        }

        function updateIcons(isDark) {
            if(isDark) {
                document.getElementById('moon-icon').classList.add('hidden');
                document.getElementById('sun-icon').classList.remove('hidden');
            } else {
                document.getElementById('sun-icon').classList.add('hidden');
                document.getElementById('moon-icon').classList.remove('hidden');
            }
        }

        // Check Local Storage
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            updateIcons(true);
        } else {
            updateIcons(false);
        }
    </script>
</body>
</html>
