<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Receipt'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; }
        @media print {
            body { background-color: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-border { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="py-12 flex justify-center items-center min-h-screen">

    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-10 print-border">
        
        <!-- Action bar (Hidden on Print) -->
        <div class="flex justify-between items-center mb-8 no-print border-b border-slate-100 pb-4">
            <button onclick="window.history.back()" class="text-slate-500 hover:text-slate-700 font-bold flex items-center gap-2 transition-colors">
                &larr; Back
            </button>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-bold transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Receipt
            </button>
        </div>

        <!-- Receipt Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl font-extrabold text-indigo-700 tracking-tight flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                    <?php echo htmlspecialchars($settings['college_name']); ?>
                </h1>
                <p class="text-slate-500 text-sm mt-2"><?php echo htmlspecialchars($settings['college_address']); ?></p>
                <p class="text-slate-500 text-sm"><?php echo htmlspecialchars($settings['college_contact']); ?></p>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-black text-slate-200 tracking-widest uppercase mb-2">Receipt</h2>
                <p class="text-slate-700 font-bold">Receipt #: <span class="text-slate-500"><?php echo str_pad($receipt['id'], 6, '0', STR_PAD_LEFT); ?></span></p>
                <p class="text-slate-700 font-bold">Date: <span class="text-slate-500"><?php echo date('F j, Y, g:i a', strtotime($receipt['created_at'])); ?></span></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10">
            <!-- Billed To -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Billed To</h3>
                <p class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($receipt['student_name']); ?></p>
                <p class="text-slate-600 mt-1"><span class="font-semibold">Enrollment:</span> <?php echo htmlspecialchars($receipt['enrollment_number']); ?></p>
                <p class="text-slate-600"><span class="font-semibold">Course:</span> <?php echo htmlspecialchars($receipt['course']); ?></p>
            </div>
            
            <!-- Payment Status -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex flex-col justify-center items-center text-center">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Payment Status</h3>
                <?php if ($receipt['status'] === 'paid'): ?>
                    <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-xl font-black text-lg uppercase tracking-wider">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        PAID IN FULL
                    </div>
                <?php else: ?>
                    <div class="inline-flex items-center gap-2 bg-rose-100 text-rose-800 px-4 py-2 rounded-xl font-black text-lg uppercase tracking-wider">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        PENDING
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Line Items -->
        <table class="w-full text-left border-collapse mb-10">
            <thead>
                <tr class="border-b-2 border-slate-200">
                    <th class="py-4 text-sm font-bold text-slate-500 uppercase tracking-wider">Description</th>
                    <th class="py-4 text-right text-sm font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-100">
                    <td class="py-6">
                        <p class="font-bold text-slate-800 text-lg"><?php echo htmlspecialchars($receipt['fee_name']); ?></p>
                        <p class="text-sm text-slate-500 mt-1">Fee collection for the academic session</p>
                    </td>
                    <td class="py-6 text-right font-black text-slate-800 text-2xl">
                        $<?php echo number_format($receipt['amount'], 2); ?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-6 text-right font-bold text-slate-500 text-lg">Total Amount:</td>
                    <td class="py-6 text-right font-black text-indigo-600 text-3xl">
                        $<?php echo number_format($receipt['amount'], 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="border-t border-slate-200 pt-8 text-center">
            <p class="text-slate-500 font-semibold">Thank you for your payment!</p>
            <p class="text-slate-400 text-sm mt-1">This is a computer generated receipt and does not require a physical signature.</p>
        </div>

    </div>

    <!-- Auto-trigger print dialog -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
