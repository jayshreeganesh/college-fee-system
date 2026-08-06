<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Student Portal'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    
    <nav class="bg-indigo-600 text-white shadow-md p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Student Portal</h1>
            <a href="/logout" class="text-sm font-semibold hover:text-indigo-200 transition-colors">Logout &rarr;</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-8 mt-4">
        
        <!-- Welcome Banner -->
        <div class="bg-white rounded-3xl p-8 mb-8 shadow-sm border border-slate-100 flex justify-between items-center bg-gradient-to-r from-indigo-50 to-white">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Welcome, <?php echo htmlspecialchars($studentData['name']); ?>!</h2>
                <p class="text-slate-500 mt-2 text-lg">Enrollment: <span class="font-bold text-slate-700"><?php echo htmlspecialchars($studentData['enrollment_number']); ?></span> | Course: <span class="font-bold text-slate-700"><?php echo htmlspecialchars($studentData['course']); ?></span></p>
            </div>
            <div class="hidden md:block">
                <svg class="h-20 w-20 text-indigo-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
            </div>
        </div>

        <?php if(isset($_GET['payment']) && $_GET['payment'] === 'success'): ?>
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-emerald-800 font-bold">Payment Processed Successfully! Your receipt is now available.</p>
            </div>
            <a href="/student" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</a>
        </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Metrics & Chart -->
            <div class="lg:col-span-1 space-y-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Total Paid</h3>
                        <p class="text-2xl font-extrabold text-emerald-500">$<?php echo number_format($totalPaid, 2); ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Pending Dues</h3>
                        <p class="text-2xl font-extrabold text-rose-500">$<?php echo number_format($totalPending, 2); ?></p>
                    </div>
                </div>

                <!-- ApexChart Container -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Fee Overview</h3>
                    <div id="studentChart" class="w-full h-64"></div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Your Transaction History</h3>
                </div>
                
                <?php if (empty($transactions)): ?>
                    <div class="p-12 text-center text-slate-500 flex-1 flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        No transactions found.
                    </div>
                <?php else: ?>
                <div class="overflow-y-auto flex-1 p-8" style="max-height: 500px;">
                    <div class="space-y-4">
                        <?php foreach ($transactions as $tx): ?>
                            <div class="flex items-center justify-between p-4 rounded-2xl border <?php echo $tx['status'] === 'paid' ? 'border-emerald-100 bg-emerald-50/30' : 'border-rose-100 bg-rose-50/30'; ?> hover:shadow-md transition-shadow">
                                <div>
                                    <h4 class="font-bold text-slate-800"><?php echo htmlspecialchars($tx['fee_name']); ?></h4>
                                    <p class="text-xs text-slate-500 mt-1"><?php echo date('M d, Y', strtotime($tx['created_at'])); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-extrabold <?php echo $tx['status'] === 'paid' ? 'text-emerald-600' : 'text-rose-600'; ?>">$<?php echo number_format($tx['amount'], 2); ?></p>
                                    <?php if ($tx['status'] === 'paid'): ?>
                                        <a href="/receipt?id=<?php echo $tx['id']; ?>" target="_blank" class="inline-block mt-2 text-xs font-bold text-indigo-600 hover:text-indigo-800">View Receipt &rarr;</a>
                                    <?php else: ?>
                                        <a href="/student/pay?id=<?php echo $tx['id']; ?>" class="inline-block mt-2 px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">Pay Now &rarr;</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var totalPaid = <?php echo json_encode($totalPaid); ?>;
            var totalPending = <?php echo json_encode($totalPending); ?>;
            
            // Only render chart if there is data
            if(totalPaid > 0 || totalPending > 0) {
                var options = {
                    series: [totalPaid, totalPending],
                    labels: ['Total Paid', 'Pending Dues'],
                    chart: {
                        type: 'donut',
                        height: 280,
                        fontFamily: 'Outfit, sans-serif'
                    },
                    colors: ['#10B981', '#F43F5E'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    name: { show: true },
                                    value: {
                                        show: true,
                                        formatter: function (val) {
                                            return "$" + val.toLocaleString()
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#studentChart"), options);
                chart.render();
            } else {
                document.querySelector("#studentChart").innerHTML = '<div class="h-full flex items-center justify-center text-slate-400 text-sm">No fee data available</div>';
            }
        });
    </script>
</body>
</html>
