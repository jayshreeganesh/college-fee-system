<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin Login'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Admin Login</h2>
            <p class="text-slate-500 mt-2">Manage College Fees & Transactions</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-center font-semibold text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="Enter username" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all duration-200">
                Sign In
            </button>
        </form>
        <div class="mt-8 text-center">
            <a href="/" class="text-indigo-600 font-semibold hover:underline">&larr; Back to Home</a>
        </div>
    </div>
</body>
</html>
