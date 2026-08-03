<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Student Login'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-xl border border-indigo-50">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-indigo-900">Student Portal</h2>
            <p class="text-slate-500 mt-2">Sign in to check your fee status</p>
        </div>
        <form action="#" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Enrollment Number</label>
                <input type="text" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="e.g. CS2026-001">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                <input type="password" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="••••••••">
            </div>
            <button type="button" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all duration-200">
                Log In
            </button>
        </form>
        <div class="mt-8 text-center">
            <a href="/" class="text-indigo-600 font-semibold hover:underline">&larr; Back to Home</a>
        </div>
    </div>
</body>
</html>
