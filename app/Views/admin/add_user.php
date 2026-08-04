<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Create User'); ?></title>
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
            <a href="/admin/users" class="text-sm font-semibold hover:text-slate-300 transition-colors">&larr; Back to Users</a>
        </div>
    </nav>
    
    <div class="max-w-2xl mx-auto p-8 mt-4">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-2xl font-bold text-slate-800">Create New System User</h2>
                <p class="text-slate-500 mt-1">Assign secure credentials and roles to new staff members.</p>
            </div>
            
            <form action="/admin/users/add" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                    <input type="text" name="username" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" placeholder="e.g. hr_manager">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Temporary Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" placeholder="Enter a secure password">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Account Role</label>
                    <select name="role" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                        <option value="recruiter">Recruiter (Read-Only Access)</option>
                        <option value="super_admin">Super Admin (Full Access)</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all duration-200">
                        Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
