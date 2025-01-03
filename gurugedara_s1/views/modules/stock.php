<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>
</head>
<body>
    <div class="content-wrapper" style="background-color: #f8f9fa; padding: 20px;">
        <div class="container" style="margin-top: 50px;">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center" style="margin-bottom: 30px; color: #343a40;">Stock Report
                        <button onclick="exportTableToExcel('stockTable', 'stock_report')" style="float: right; margin-top: -10px;" class="btn btn-primary">Download Report</button>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate">
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate">
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for products..." style="margin-bottom: 20px;">
                    <div class="table-responsive">
                        <table id="stockTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Previous Stock</th>
                                    <th>New Stock</th>
                                    <th>Current Stock</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch the stock data
                                $stocks = controllerStocks::ctrShowStock($item, $value);
                                // Group data by product name and sort by date
                                $groupedStocks = [];
                                foreach ($stocks as $stock) {
                                    $groupedStocks[$stock['productName']][] = $stock;
                                }
                                // Sort each product group by date (newest first)
                                foreach ($groupedStocks as &$stockItems) {
                                    usort($stockItems, function($a, $b) {
                                        return strtotime($b['date']) - strtotime($a['date']);
                                    });
                                }
                                unset($stockItems);
                                // Display the grouped and sorted data
                                foreach ($groupedStocks as $productName => $stockItems) {
                                    foreach ($stockItems as $stockItem) {
                                        echo '
                                        <tr data-date="' . $stockItem["date"] . '">
                                            <td>' . $stockItem["productName"] . '</td>
                                            <td>' . $stockItem["previousStock"] . '</td>
                                            <td>' . $stockItem["newStock"] . '</td>
                                            <td>' . $stockItem["currentStock"] . '</td>
                                            <td>' . $stockItem["date"] . '</td>
                                            <td>' . $stockItem["time"] . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
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
        filename = filename ? filename + '.xls' : 'stock_report_' + Date.now() + '.xls';
        
        // Create download link element
        downloadLink = document.createElement("a");
        
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
            // Setting the file name
            downloadLink.download = filename;
            
            // Triggering the function
            downloadLink.click();
        }
    }

    function exportFilteredTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var startDate = new Date(document.getElementById('startDate').value);
        var endDate = new Date(document.getElementById('endDate').value);
        var tableHTML = '<table border="1"><thead>' + tableSelect.querySelector('thead').outerHTML + '</thead><tbody>';

        // Filter rows based on date range
        var rows = tableSelect.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            var rowDate = new Date(row.getAttribute('data-date'));
            if (rowDate >= startDate && rowDate <= endDate) {
                tableHTML += row.outerHTML;
            }
        });
        tableHTML += '</tbody></table>';
        tableHTML = tableHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename ? filename + '.xls' : 'stock_report_' + Date.now() + '.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            // Triggering the function
            downloadLink.click();
        }
    }

    $(document).ready(function(){
        // Search filter
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#stockTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Date range filter
        $("#startDate, #endDate").on("change", function() {
            var startDate = new Date($("#startDate").val());
            var endDate = new Date($("#endDate").val());
            $("#stockTable tbody tr").filter(function() {
                var rowDate = new Date($(this).attr("data-date"));
                $(this).toggle(rowDate >= startDate && rowDate <= endDate);
            });
        });
    });
    </script>
</body>
</html>
