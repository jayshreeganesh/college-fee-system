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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 transition-colors duration-300 min-h-screen">
    <nav class="bg-slate-900 dark:bg-slate-950 text-white shadow-md p-4 sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                Admin Dashboard
            </h1>
            <div class="flex items-center gap-6">
                <button onclick="toggleDarkMode()" class="p-2 rounded-full hover:bg-slate-800 transition-colors" title="Toggle Dark Mode">
                    <svg id="moon-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="sun-icon" class="w-5 h-5 hidden text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
                <span class="text-sm font-semibold">Welcome, <span class="text-indigo-400"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'Admin'); ?></span></span>
                <a href="/logout" class="bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg transition-colors text-sm font-semibold">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto p-8 mt-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white">Overview</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Real-time statistics for the College Fee System</p>
            </div>
            <div class="flex gap-4">
                <?php if (($_SESSION['admin_role'] ?? '') === 'super_admin'): ?>
                <a href="/admin/student/add" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Add Student
                </a>
                <a href="/admin/student/import" class="flex items-center gap-2 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Students
                </a>
                <a href="/admin/payment" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Payment
                </a>
                <a href="/admin/fees" class="flex items-center gap-2 px-6 py-3 bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Manage Fees
                </a>
                <a href="/admin/users" class="flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manage Users
                </a>
                <a href="/admin/settings" class="flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                <a href="/admin/audit-logs" class="flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Audit Logs
                </a>
                <?php endif; ?>
                <a href="/admin/reports" class="flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Reports
                </a>
                <a href="/admin/export" class="flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Data
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Students</h3>
                <p class="text-4xl font-extrabold text-slate-800 mt-2"><?php echo htmlspecialchars($studentCount); ?></p>
            </div>
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-8 rounded-3xl shadow-md text-white flex flex-col justify-center">
                <h3 class="text-sm font-bold opacity-80 uppercase tracking-wider">Total Collected</h3>
                <p class="text-4xl font-extrabold mt-2">$<?php echo number_format($totalCollected, 2); ?></p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pending Dues</h3>
                <p class="text-4xl font-extrabold text-rose-500 mt-2">$<?php echo number_format($totalPending, 2); ?></p>
            </div>
        </div>
            
            <div class="mt-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800 mb-4">Revenue by Fee Category</h3>
                <div id="revenueChart" class="w-full h-80"></div>
            </div>

            <div class="mt-8 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Student</th>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Category</th>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Amount</th>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Status</th>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Date</th>
                        <th class="px-8 py-4 text-sm font-bold text-slate-400 uppercase tracking-wider bg-white">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($recentTransactions as $tx): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-4 font-semibold text-slate-700"><?php echo htmlspecialchars($tx['student_name']); ?></td>
                        <td class="px-8 py-4 text-slate-500"><?php echo htmlspecialchars($tx['fee_name']); ?></td>
                        <td class="px-8 py-4 font-bold text-slate-800">$<?php echo number_format($tx['amount'], 2); ?></td>
                        <td class="px-8 py-4">
                            <?php if ($tx['status'] === 'paid'): ?>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">Paid</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full uppercase tracking-wider">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-4 text-slate-400 text-sm"><?php echo htmlspecialchars(date('M j, Y', strtotime($tx['created_at']))); ?></td>
                        <td class="px-8 py-4">
                            <?php if ($tx['status'] === 'paid'): ?>
                                <a href="/receipt?id=<?php echo $tx['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs" target="_blank">Print Receipt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($recentTransactions)): ?>
                <div class="px-8 py-12 text-center text-slate-500">No transactions found.</div>
            <?php endif; ?>
        </div>
        
        <!-- System Operations -->
        <div class="mt-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <h3 class="text-xl font-bold text-slate-800 mb-4">System Operations</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Backup -->
                <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl flex flex-col items-center text-center">
                    <h4 class="text-lg font-bold text-indigo-800 mb-2">Backup Database</h4>
                    <p class="text-sm text-indigo-600 mb-4 flex-1">Download a secure copy of the app.sqlite database file.</p>
                    <a href="/admin/backup" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-colors">Download Backup</a>
                </div>
                
                <!-- Restore -->
                <div class="bg-rose-50 border border-rose-100 p-6 rounded-2xl flex flex-col items-center text-center <?php echo (($_SESSION['admin_role'] ?? '') !== 'super_admin') ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''; ?>">
                    <h4 class="text-lg font-bold text-rose-800 mb-2">Restore Database</h4>
                    <p class="text-sm text-rose-600 mb-4 flex-1">Upload a previous .sqlite backup to overwrite the live database.</p>
                    <form action="/admin/restore" method="POST" enctype="multipart/form-data" class="w-full flex flex-col gap-2">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                        <input type="file" name="database_file" accept=".sqlite" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200" required>
                        <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shadow-sm transition-colors">Upload & Restore</button>
                    </form>
                    <?php if (($_SESSION['admin_role'] ?? '') !== 'super_admin'): ?>
                        <p class="text-xs text-rose-800 mt-2 font-bold">Super Admin Only</p>
                    <?php endif; ?>
                </div>
                
                <!-- Export Source Code -->
                <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl flex flex-col items-center text-center">
                    <h4 class="text-lg font-bold text-emerald-800 mb-2">Export Project</h4>
                    <p class="text-sm text-emerald-600 mb-4 flex-1">Download the entire PHP source code as a ZIP archive.</p>
                    <a href="/admin/export-project" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm transition-colors">Export Source Code</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: <?php echo $chartSeries; ?>,
                labels: <?php echo $chartLabels; ?>,
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'Outfit, sans-serif'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Revenue',
                                    formatter: function (w) {
                                        return '$' + w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0).toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                },
                colors: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
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
