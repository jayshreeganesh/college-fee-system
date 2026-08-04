<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'System Setup'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="px-8 py-8 border-b border-slate-100 bg-indigo-600 text-white text-center">
                <svg class="mx-auto h-12 w-12 mb-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <h2 class="text-3xl font-extrabold tracking-tight">System Setup</h2>
                <p class="text-indigo-200 mt-2">Initialize your database & create the Super Admin account</p>
            </div>
            
            <div class="p-8">
                <!-- Checklist -->
                <div class="mb-8 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h3 class="font-bold text-slate-700 mb-4">System Requirements</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center text-emerald-600 font-semibold">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            PHP Version: <?php echo phpversion(); ?>
                        </li>
                        <li class="flex items-center text-emerald-600 font-semibold">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            SQLite PDO Extension Enabled
                        </li>
                    </ul>
                </div>

                <form action="/setup" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Super Admin Username</label>
                        <input type="text" name="username" required class="w-full px-5 py-4 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="e.g. admin">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Super Admin Password</label>
                        <input type="password" name="password" required class="w-full px-5 py-4 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="Enter a secure password">
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                        Install System & Create Lock
                    </button>
                    
                    <p class="text-xs text-center text-slate-500 mt-4">
                        Upon successful installation, a <code class="bg-slate-100 px-1 py-0.5 rounded text-rose-600 font-mono">setup.lock</code> file will be permanently generated to secure your system.
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
