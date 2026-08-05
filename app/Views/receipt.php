<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Payment Receipt'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; }
        @media print {
            body { background-color: #ffffff; }
            .no-print { display: none !important; }
            .print-border { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="min-h-screen py-10 px-4 flex justify-center">
    
    <div class="max-w-2xl w-full">
        <!-- Action Bar (Hidden when printing) -->
        <div class="mb-6 flex justify-between items-center no-print">
            <button onclick="window.history.back()" class="text-slate-500 hover:text-slate-800 font-bold flex items-center gap-2 transition-colors">
                &larr; Go Back
            </button>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                Print / Save PDF
            </button>
        </div>

        <!-- Receipt Document -->
        <div class="bg-white p-10 rounded-2xl shadow-lg border border-slate-200 print-border">
            
            <div class="flex justify-between items-start border-b-2 border-slate-100 pb-8 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-indigo-700 tracking-tight">TECH UNIVERSITY</h1>
                    <p class="text-sm text-slate-500 mt-1">123 Innovation Drive, Tech City, TX 75001</p>
                    <p class="text-sm text-slate-500">contact@techuniversity.edu | (555) 123-4567</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-slate-800">OFFICIAL RECEIPT</h2>
                    <p class="text-sm font-semibold text-slate-500 mt-1">Receipt #: <span class="text-slate-800"><?php echo str_pad($receipt['id'], 6, '0', STR_PAD_LEFT); ?></span></p>
                    <p class="text-sm font-semibold text-slate-500">Date: <span class="text-slate-800"><?php echo date('M d, Y g:i A', strtotime($receipt['created_at'])); ?></span></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Billed To Student</h3>
                    <p class="text-lg font-bold text-slate-800"><?php echo htmlspecialchars($receipt['student_name']); ?></p>
                    <p class="text-sm text-slate-600 mt-1">Enrollment No: <span class="font-semibold"><?php echo htmlspecialchars($receipt['enrollment_number']); ?></span></p>
                    <p class="text-sm text-slate-600">Course: <span class="font-semibold"><?php echo htmlspecialchars($receipt['course']); ?></span></p>
                </div>
                <div class="text-right">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Payment Status</h3>
                    <?php if ($receipt['status'] === 'paid'): ?>
                        <span class="inline-block px-4 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">PAID</span>
                    <?php else: ?>
                        <span class="inline-block px-4 py-1 rounded-full text-sm font-bold bg-rose-100 text-rose-800 border border-rose-200">PENDING</span>
                    <?php endif; ?>
                </div>
            </div>

            <table class="w-full text-left border-collapse mb-8">
                <thead>
                    <tr class="border-y-2 border-slate-100 text-sm text-slate-500 bg-slate-50">
                        <th class="py-3 px-4 font-bold uppercase tracking-wider">Description (Fee Type)</th>
                        <th class="py-3 px-4 font-bold uppercase tracking-wider text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-4 px-4 font-semibold text-slate-700"><?php echo htmlspecialchars($receipt['fee_name']); ?></td>
                        <td class="py-4 px-4 font-bold text-slate-800 text-right">$<?php echo number_format($receipt['amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-end">
                <div class="w-1/2">
                    <div class="flex justify-between items-center border-t-2 border-slate-800 pt-4">
                        <span class="text-lg font-bold text-slate-800">Total Paid</span>
                        <span class="text-2xl font-extrabold text-indigo-600">$<?php echo number_format($receipt['amount'], 2); ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center text-sm text-slate-400 border-t border-slate-100 pt-8">
                <p>This is a computer-generated document. No signature is required.</p>
                <p class="mt-1">Thank you for your payment!</p>
            </div>
            
        </div>
    </div>

</body>
</html>
