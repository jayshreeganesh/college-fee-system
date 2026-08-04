<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Advanced Reports'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-slate-900 text-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Advanced Reports</h1>
            <a href="/admin" class="text-sm font-semibold hover:text-slate-300 transition-colors">&larr; Back to Dashboard</a>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto p-8 mt-4">
        
        <!-- Filters -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8">
            <form action="/admin/reports" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Filter by Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="paid" <?php echo ($statusFilter ?? '') === 'paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="pending" <?php echo ($statusFilter ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Filter by Course</label>
                    <input type="text" name="course" value="<?php echo htmlspecialchars($courseFilter ?? ''); ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g. Computer Science">
                </div>
                <div>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Filtered Transactions (<?php echo count($transactions); ?> found)</h3>
                <a href="/admin/export" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition-all text-sm">
                    Export All to ZIP
                </a>
            </div>
            
            <?php if (empty($transactions)): ?>
                <div class="p-8 text-center text-slate-500">No transactions match your filters.</div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-sm text-slate-500 bg-slate-50">
                            <th class="p-4 font-semibold">Date</th>
                            <th class="p-4 font-semibold">Student</th>
                            <th class="p-4 font-semibold">Course</th>
                            <th class="p-4 font-semibold">Fee Type</th>
                            <th class="p-4 font-semibold">Amount</th>
                            <th class="p-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 text-slate-600"><?php echo htmlspecialchars(date('M d, Y', strtotime($tx['created_at']))); ?></td>
                            <td class="p-4 font-semibold text-slate-800"><?php echo htmlspecialchars($tx['student_name']); ?></td>
                            <td class="p-4 text-slate-600"><?php echo htmlspecialchars($tx['course']); ?></td>
                            <td class="p-4 text-slate-600"><?php echo htmlspecialchars($tx['fee_name']); ?></td>
                            <td class="p-4 font-bold text-slate-800">$<?php echo number_format($tx['amount'], 2); ?></td>
                            <td class="p-4">
                                <?php if ($tx['status'] === 'paid'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Paid</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
