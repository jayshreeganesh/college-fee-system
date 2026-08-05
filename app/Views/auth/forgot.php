<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Forgot Password'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Account Recovery</h1>
            <p class="text-slate-500 mt-2">Reset your password securely</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
            
            <?php if (!empty($success)): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-bold">Recovery Email Sent!</h4>
                        <p class="text-sm mt-1">If an account exists with that identifier, a secure reset link has been dispatched to your registered email address.</p>
                    </div>
                </div>
            <?php else: ?>
                <form action="/forgot-password" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Username or Enrollment Number</label>
                        <input type="text" name="identifier" required class="w-full px-5 py-4 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="e.g. admin or STU-001">
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                        Send Secure Reset Link
                    </button>
                </form>
            <?php endif; ?>

        </div>
        
        <div class="text-center mt-8 space-y-2">
            <a href="/login" class="block text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">Back to Admin Login</a>
            <a href="/student/login" class="block text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">Back to Student Login</a>
        </div>
    </div>
</body>
</html>
