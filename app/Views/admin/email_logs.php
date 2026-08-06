<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Email Logs'); ?></title>
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
    
    <div class="max-w-5xl mx-auto p-8 mt-4">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-2xl font-bold text-slate-800">Email Simulation Logs</h2>
                <p class="text-slate-500 mt-1">Review the automated system emails triggered by payment events.</p>
            </div>
            
            <div class="overflow-x-auto p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-sm text-slate-500 bg-slate-50">
                            <th class="p-4 font-bold">Recipient</th>
                            <th class="p-4 font-bold">Subject / Body</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 font-semibold">No emails have been simulated yet.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 font-bold text-indigo-600"><?php echo htmlspecialchars($log['recipient_email']); ?></td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800"><?php echo htmlspecialchars($log['subject']); ?></div>
                                    <div class="text-xs text-slate-500 mt-1 bg-slate-50 p-2 rounded border border-slate-100"><?php echo $log['body']; ?></div>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                        <?php echo htmlspecialchars($log['status']); ?>
                                    </span>
                                </td>
                                <td class="p-4 text-slate-500 text-sm font-semibold">
                                    <?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
