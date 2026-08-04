<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Import Students'); ?></title>
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
                <h2 class="text-2xl font-bold text-slate-800">Bulk Import Students</h2>
                <p class="text-slate-500 mt-1">Upload a CSV/Excel file to quickly register hundreds of students</p>
            </div>
            
            <div class="p-8">
                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <h4 class="font-bold text-blue-800 mb-2">Required CSV Format</h4>
                    <p class="text-sm text-blue-600">Your CSV file must have exactly these 5 columns in this order (with a header row):</p>
                    <code class="block mt-2 text-xs bg-white p-2 rounded border border-blue-200">Enrollment Number, Name, Email, Course, Default Password</code>
                </div>

                <form action="/admin/student/import" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-10 text-center hover:bg-slate-50 transition-colors">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select CSV File</label>
                        <input type="file" name="csv_file" accept=".csv" class="mx-auto block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all duration-200">
                        Upload & Process Students
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
