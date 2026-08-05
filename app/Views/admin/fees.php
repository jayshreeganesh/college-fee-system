<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Manage Fee Categories'); ?></title>
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
    
    <div class="max-w-5xl mx-auto p-8 mt-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Add New Fee Form -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-indigo-50/50">
                    <h2 class="text-xl font-bold text-indigo-900">Add New Fee</h2>
                </div>
                <form action="/admin/fees/add" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Fee Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" placeholder="e.g. Lab Fee">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Default Amount ($)</label>
                        <input type="number" step="0.01" name="amount" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" placeholder="150.00">
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all">
                        Create Fee Type
                    </button>
                </form>
            </div>
        </div>

        <!-- List Fees -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-800">Active Fee Categories</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($fees as $fee): ?>
                            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-white hover:border-indigo-100 hover:shadow-md transition-all group">
                                <div>
                                    <h3 class="font-bold text-lg text-slate-800"><?php echo htmlspecialchars($fee['name']); ?></h3>
                                    <p class="text-sm font-semibold text-slate-500 mt-1">ID: #<?php echo $fee['id']; ?></p>
                                </div>
                                <div class="text-right flex items-center gap-6">
                                    <span class="text-2xl font-extrabold text-indigo-600">$<?php echo number_format($fee['amount'], 2); ?></span>
                                    <a href="/admin/fees/delete?id=<?php echo $fee['id']; ?>" class="text-rose-400 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity p-2" title="Delete Fee" onclick="return confirm('Are you sure? This will only work if no students have paid this fee yet.');">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($fees)): ?>
                            <p class="text-center text-slate-500 py-8">No fee categories found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
