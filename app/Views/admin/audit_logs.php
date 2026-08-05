<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Audit Logs'); ?></title>
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
            <a href="/admin" class="text-sm font-semibold hover:text-slate-300 transition-colors">&larr; Back to Dashboard</a>
        </div>
    </nav>
    
    <div class="max-w-6xl mx-auto p-8 mt-4">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Security Audit Logs</h2>
                    <p class="text-slate-500 mt-1">Track actions performed by administrative staff.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-sm text-slate-500 bg-slate-50">
                            <th class="p-6 font-bold">Timestamp</th>
                            <th class="p-6 font-bold">Admin User</th>
                            <th class="p-6 font-bold">Action Performed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6 text-sm text-slate-500 font-semibold"><?php echo date('M d, Y h:i:s A', strtotime($log['created_at'])); ?></td>
                            <td class="p-6 font-bold text-slate-800">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs">
                                        <?php echo strtoupper(substr($log['admin_username'], 0, 1)); ?>
                                    </span>
                                    <?php echo htmlspecialchars($log['admin_username']); ?>
                                </span>
                            </td>
                            <td class="p-6 text-slate-700 font-medium">
                                <?php echo htmlspecialchars($log['action']); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="3" class="p-12 text-center text-slate-500">No audit logs found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
