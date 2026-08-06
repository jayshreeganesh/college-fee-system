<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Secure Checkout'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        <!-- Order Summary Side -->
        <div class="w-full md:w-1/3 bg-slate-900 p-8 text-white flex flex-col justify-between">
            <div>
                <a href="/student" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2 mb-12">
                    &larr; Back to Portal
                </a>
                
                <h2 class="text-xl font-bold text-slate-300 mb-2">Payment Summary</h2>
                <h3 class="text-3xl font-extrabold mb-6"><?php echo htmlspecialchars($transaction['fee_name']); ?></h3>
                
                <div class="border-t border-slate-700 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-400">Subtotal</span>
                        <span class="font-bold">$<?php echo number_format($transaction['amount'], 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center text-xl">
                        <span class="font-bold">Total Due</span>
                        <span class="font-extrabold text-emerald-400">$<?php echo number_format($transaction['amount'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-sm text-slate-500 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                SSL Secured Transaction
            </div>
        </div>
        
        <!-- Payment Form Side -->
        <div class="w-full md:w-2/3 p-8 md:p-12">
            <div class="mb-8 flex justify-between items-center">
                <h2 class="text-2xl font-extrabold text-slate-800">Checkout</h2>
                <div class="flex gap-2">
                    <!-- Fake Card Icons -->
                    <div class="w-10 h-6 bg-slate-200 rounded text-[10px] font-bold text-slate-500 flex items-center justify-center">VISA</div>
                    <div class="w-10 h-6 bg-slate-200 rounded text-[10px] font-bold text-slate-500 flex items-center justify-center">MC</div>
                </div>
            </div>
            
            <form action="/student/pay/process" method="POST" class="space-y-6" onsubmit="return simulatePayment(this);">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Cardholder Name</label>
                    <input type="text" required value="<?php echo htmlspecialchars($_SESSION['student_name'] ?? ''); ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="John Doe">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Card Number</label>
                    <input type="text" required maxlength="19" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800 tracking-widest" placeholder="4242 4242 4242 4242">
                </div>
                
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Expiry Date</label>
                        <input type="text" required maxlength="5" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="MM/YY">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">CVC</label>
                        <input type="text" required maxlength="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-slate-800" placeholder="123">
                    </div>
                </div>
                
                <button type="submit" id="payBtn" class="w-full mt-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Pay $<?php echo number_format($transaction['amount'], 2); ?>
                </button>
            </form>
            
        </div>
    </div>
    
    <script>
        function simulatePayment(form) {
            const btn = document.getElementById('payBtn');
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            setTimeout(() => {
                form.submit();
            }, 1500);
            return false;
        }
    </script>
</body>
</html>
