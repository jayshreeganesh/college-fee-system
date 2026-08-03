<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Student Portal'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-indigo-600 text-white shadow-md p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Student Portal</h1>
            <a href="/logout" class="hover:text-indigo-200 transition-colors font-semibold">Logout</a>
        </div>
    </nav>
    <div class="max-w-6xl mx-auto p-8 mt-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h2 class="text-3xl font-bold text-slate-800 mb-6">Welcome back, Student!</h2>
            <p class="text-slate-500 mb-8">Here you can view your fee status, download invoices, and check transaction history.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl">
                    <h3 class="text-lg font-bold text-blue-800">Total Paid</h3>
                    <p class="text-3xl font-extrabold text-blue-600 mt-2">$0.00</p>
                </div>
                <div class="bg-red-50 border border-red-100 p-6 rounded-2xl">
                    <h3 class="text-lg font-bold text-red-800">Pending Dues</h3>
                    <p class="text-3xl font-extrabold text-red-600 mt-2">$0.00</p>
                </div>
                <div class="bg-green-50 border border-green-100 p-6 rounded-2xl">
                    <h3 class="text-lg font-bold text-green-800">Last Transaction</h3>
                    <p class="text-xl font-bold text-green-600 mt-2">None</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
