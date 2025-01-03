<?php
// Get total sales data
$item = null;
$value = null;
$sales = ControllerSales::ctrShowSales($item, $value);

// Initialize arrays for monthly totals
$monthlyData = [];

// Current month calculations
$currentMonth = date('m');
$currentYear = date('Y');
$currentMonthName = date('F');
$currentMonthIncome = 0;

foreach ($sales as $sale) {
    $saleMonth = date('m', strtotime($sale["saledate"]));
    $saleYear = date('Y', strtotime($sale["saledate"]));
    $monthKey = $saleYear . '-' . $saleMonth;
    
    // Initialize month if not exists
    if (!isset($monthlyData[$monthKey])) {
        $monthlyData[$monthKey] = [
            'income' => 0,
            'month' => date('F', strtotime($sale["saledate"])),
            'year' => $saleYear
        ];
    }
    
    // Add sale amount to monthly total
    $monthlyData[$monthKey]['income'] += $sale["totalPrice"];
    
    // Calculate current month totals
    if ($saleMonth == $currentMonth && $saleYear == $currentYear) {
        $currentMonthIncome += $sale["totalPrice"];
    }
}

// Calculate current month expenses and profit
$currentMonthExpenses = $currentMonthIncome * 0.70;
$currentMonthProfit = $currentMonthIncome - $currentMonthExpenses;

// Sort monthly data by date (newest first)
krsort($monthlyData);
?>

<div class="content-wrapper" style="background-color: #f8f9fa; padding: 20px;">
    <div class="container" style="margin-top: 20px;">
        <!-- Current Month Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="text-muted"><?php echo $currentMonthName; ?> <?php echo $currentYear; ?> Overview</h4>
            </div>
        </div>
        
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">Total Income</h6>
                        <h3 class="mb-0">Rs.<?php echo number_format($currentMonthIncome, 2); ?></h3>
                        <small class="text-success">
                            <i class="fa fa-calendar"></i> <?php echo $currentMonthName; ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">Total Expenses</h6>
                        <h3 class="mb-0">Rs.<?php echo number_format($currentMonthExpenses, 2); ?></h3>
                        <small class="text-danger">
                            <i class="fa fa-calendar"></i> <?php echo $currentMonthName; ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">Net Profit</h6>
                        <h3 class="mb-0">Rs.<?php echo number_format($currentMonthProfit, 2); ?></h3>
                        <small class="text-success">
                            <i class="fa fa-calendar"></i> <?php echo $currentMonthName; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Months Data -->
         <br><br><br>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Monthly Performance History</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportTableToExcel('monthlyDataTable')">
                                <i class="fa fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="monthlyDataTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Month</th>
                                        <th>Income</th>
                                        <th>Expenses</th>
                                        <th>Net Profit</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($monthlyData as $monthKey => $data): 
                                        $expenses = $data['income'] * 0.70;
                                        $profit = $data['income'] - $expenses;
                                        
                                        // Skip current month as it's shown above
                                        if ($monthKey == $currentYear.'-'.$currentMonth) continue;
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="fw-medium"><?php echo $data['month']; ?></span> 
                                            <small class="text-muted"><?php echo $data['year']; ?></small>
                                        </td>
                                        <td>Rs.<?php echo number_format($data['income'], 2); ?></td>
                                        <td>Rs.<?php echo number_format($expenses, 2); ?></td>
                                        <td>Rs.<?php echo number_format($profit, 2); ?></td>
                                        <td>
                                            <?php if ($profit > 0): ?>
                                                <span class="badge bg-success">Profitable</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Loss</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export to Excel Function -->
<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename ? filename + '.xls' : 'monthly_performance_' + Date.now() + '.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
</script>