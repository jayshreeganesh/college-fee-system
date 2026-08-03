<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="/admin/payment" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Payment
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

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
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
    </div>
</body>
</html>
