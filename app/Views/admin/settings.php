<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'System Settings'); ?></title>
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
    
    <div class="max-w-3xl mx-auto p-8 mt-4">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-2xl font-bold text-slate-800">Global System Settings</h2>
                <p class="text-slate-500 mt-1">Configure your institution's branding and global variables.</p>
            </div>
            
            <form action="/admin/settings" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                
                <?php if (isset($_GET['success'])): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6">
                    <p class="font-bold">Settings updated successfully!</p>
                </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">College / Institution Name</label>
                    <input type="text" name="college_name" value="<?php echo htmlspecialchars($settings['college_name'] ?? ''); ?>" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800">
                    <p class="text-xs text-slate-500 mt-1">This will appear on the top of all generated payment receipts.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Official Address</label>
                    <input type="text" name="college_address" value="<?php echo htmlspecialchars($settings['college_address'] ?? ''); ?>" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Details (Email / Phone)</label>
                    <input type="text" name="college_contact" value="<?php echo htmlspecialchars($settings['college_contact'] ?? ''); ?>" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800">
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="py-3 px-8 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
