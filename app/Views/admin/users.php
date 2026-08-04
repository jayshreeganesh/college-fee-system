<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Manage Users'); ?></title>
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
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Manage Users</h2>
                    <p class="text-slate-500 mt-1">View and manage administrators and recruiters.</p>
                </div>
                <a href="/admin/users/add" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-1 text-sm">
                    + Create New User
                </a>
            </div>
            
            <div class="overflow-x-auto p-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-sm text-slate-500 bg-slate-50">
                            <th class="p-4 font-bold">ID</th>
                            <th class="p-4 font-bold">Username</th>
                            <th class="p-4 font-bold">Role</th>
                            <th class="p-4 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-semibold text-slate-500">#<?php echo $admin['id']; ?></td>
                            <td class="p-4 font-bold text-slate-800"><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td class="p-4">
                                <?php if ($admin['role'] === 'super_admin'): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">Super Admin</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Recruiter (Read-Only)</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-emerald-600 font-bold text-sm">Active</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
