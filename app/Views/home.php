<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'College Fee System'); ?></title>
    <!-- Tailwind CSS via CDN for rapid MVC prototyping -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-text {
            background: linear-gradient(135deg, #4F46E5 0%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen relative overflow-hidden flex items-center justify-center">
    
    <!-- Background abstract shapes -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
    <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-5xl p-6">
        <div class="glass-panel rounded-3xl shadow-2xl p-10 md:p-16 text-center transform hover:scale-[1.01] transition-transform duration-300">
            
            <div class="inline-block p-4 rounded-2xl bg-white shadow-sm mb-6">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-800 mb-6 tracking-tight">
                Modern <span class="gradient-text">Fee Management</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-500 mb-10 max-w-2xl mx-auto font-light">
                A robust, enterprise-grade MVC application designed to seamlessly track student payments, generate invoices, and manage college finances.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/login" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-1">
                    Admin Login
                </a>
                <a href="/student" class="px-8 py-4 bg-white hover:bg-slate-50 text-indigo-600 font-semibold rounded-xl border border-slate-200 shadow-sm transition-all duration-200 transform hover:-translate-y-1">
                    Student Portal
                </a>
            </div>

            <div class="mt-16 pt-8 border-t border-slate-200/50 flex flex-wrap justify-center gap-8 text-sm font-semibold text-slate-400">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span> Custom MVC
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span> Tailwind CSS
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span> Secure PDO
                </span>
            </div>
        </div>
    </div>

</body>
</html>
