<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-slate-900 text-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Admin Dashboard</h1>
            <a href="/logout" class="text-sm font-semibold hover:text-slate-300 transition-colors">Logout / Home</a>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto p-8 mt-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Overview</h2>
                <p class="text-slate-500 mt-1">Real-time statistics for the College Fee System</p>
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
</body>
</html>
