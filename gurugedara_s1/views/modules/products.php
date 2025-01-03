<?php

if($_SESSION["profile"] == "Seller"){

  echo '<script>

    window.location = "home";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Product Management

    </h1>

    <ol class="breadcrumb">
        
      <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Dashboard</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <button class="btn btn-success" data-toggle="modal" data-target="#addProduct"> <i class="fa fa-plus"></i> Add Product</button>
        <button onclick="exportTableToExcel('productsTable', 'products_report')" class="btn btn-primary" style="margin-left: 10px;">Download Report</button>

      </div>

      <div class="box-body">

        <table class="table table-bordered table-hover table-striped dt-responsive productsTable" id="productsTable" width="100%">
       
          <thead>
            
           <tr>
             
             <th style="width:10px">#</th>
             <th>Image</th>
             <th>Code</th>
             <th>Description</th>
             <th>Category</th>
             <th>Stock</th>
             <th>Buying Price</th>
             <th>Selling Price</th>
             <th>Discount</th>
             <th>Date added</th>
             <th>Actions</th>
           </tr> 
          </thead>
          <tbody>
            <?php
            // Fetch the product data
            $products = controllerProducts::ctrShowProducts($item, $value, $order);

            // Display the product data
            foreach ($products as $product) {
                echo '
                <tr>
                    <td>' . $product["id"] . '</td>
                    <td><img src="' . $product["image"] . '" class="img-thumbnail" width="40px"></td>
                    <td>' . $product["code"] . '</td>
                    <td>' . $product["description"] . '</td>
                    <td>' . $product["category"] . '</td>
                    <td>' . $product["stock"] . '</td>
                    <td>' . $product["buyingPrice"] . '</td>
                    <td>' . $product["sellingPrice"] . '</td>
                    <td>' . $product["discount"] . '</td>
                    <td>' . $product["date_added"] . '</td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-primary btnEditProduct" idProduct="' . $product["id"] . '" data-toggle="modal" data-target="#modalEditProduct"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btnDeleteProduct" idProduct="' . $product["id"] . '" code="' . $product["code"] . '" image="' . $product["image"] . '"><i class="fa fa-trash"></i></button>
                      </div>
                    </td>
                </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    
    </div>
  </section>
</div>

<!-- Export to Excel Function -->
<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = '<table border="1"><thead><tr><th>Code</th><th>Description</th><th>Stock</th></tr></thead><tbody>';

    // Loop through the table rows and extract the required columns
    var rows = tableSelect.getElementsByTagName('tr');
    for (var i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            tableHTML += '<tr>';
            tableHTML += '<td>' + cells[2].innerText + '</td>'; // Code
            tableHTML += '<td>' + cells[3].innerText + '</td>'; // Description
            tableHTML += '<td>' + cells[5].innerText + '</td>'; // Stock
            tableHTML += '</tr>';
        }
    }
    tableHTML += '</tbody></table>';

    // Get the current date
    var date = new Date();
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    var year = date.getFullYear();
    var currentDate = year + '-' + month + '-' + day;

    // Specify file name with the current date
    filename = filename ? filename + '_' + currentDate + '.xls' : 'products_report_' + currentDate + '.xls';
    
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
</script>

<!--=====================================
=            module add Product            =
======================================-->

<!-- Modal -->
<div id="addProduct" class="modal fade" role="dialog">
	
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="POST" enctype="multipart/form-data">

        <!--=====================================
        HEADER
        ======================================-->

        <div class="modal-header" style="background: #DD4B39; color: #fff">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Add Product</h4>

        </div>

        <!--=====================================
        BODY
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- input category -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <select class="form-control input-lg" id="newCategory" name="newCategory">

                  <option value="">Select Category</option>

                   <?php

                    $item = null;
                    $value1 = null;

                    $categories = controllerCategories::ctrShowCategories($item, $value1);

                    foreach ($categories as $key => $value) {
                      
                      echo '<option value="'.$value["id"].'">'.$value["Category"].'</option>';
                    }

                  ?>

                </select>

              </div>

            </div>

            <!--Input Code -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-code"></i></span>

                <input class="form-control input-lg" type="text" id="newCode" name="newCode" placeholder="Add Product Code" required>

              </div>

            </div>

            <!-- input description -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                <input class="form-control input-lg" type="text" id="newDescription" name="newDescription" placeholder="Add Description/Product Name" required>

              </div>

            </div>

             <!-- input Stock -->
            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-check"></i></span>

                <input class="form-control input-lg" type="number" id="newStock" name="newStock" placeholder="Add Stock" min="0" required>

              </div>

            </div>

            <!-- INPUT BUYING PRICE -->
            <div class="form-group row">

              <div class="col-xs-12 col-sm-4">

                <div class="input-group"> 

                  <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                  <input type="number" class="form-control input-lg" id="newBuyingPrice" name="newBuyingPrice" step="any" min="0" placeholder="Buying Price" required>

                </div>

              </div>

              <!-- INPUT SELLING PRICE -->
              <div class="col-xs-12 col-sm-4">  

                <div class="input-group"> 

                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                  <input type="number" class="form-control input-lg" id="newSellingPrice" name="newSellingPrice" step="any" min="0" placeholder="Selling Price" required>

                </div> 

              </div>
              
              <!-- DISCOUNT PRICE -->
              <div class="col-xs-12 col-sm-4">

                <div class="input-group"> 

                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                  <input type="number" class="form-control input-lg" id="newDiscountPrice" name="newDiscountPrice" step="any" min="0" placeholder="Discount Price" required>

                </div>

              </div>

            </div>

            <!-- CHECKBOX AND INPUT -->
            <div class="form-group row">

              <div class="col-xs-12 col-xs-6"></div>

              <div class="col-xs-12 col-xs-6"> 

                <!-- CHECKBOX PERCENTAGE -->
                <div class="col-xs-6"> 

                  <div class="form-group">   

                    <label>     

                      <input type="checkbox" class="minimal percentage">

                      Use Percentage

                    </label>

                  </div>

                </div>

                <!-- INPUT PERCENTAGE -->
                <div class="col-xs-12 col-xs-6" style="padding:0">

                  <div class="input-group"> 

                    <input type="number" class="form-control input-lg newPercentage" min="0" value="0" required>

                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                  </div>

                </div>
              
              </div>

            </div>

            <!-- INPUT DATE -->
            <div class="form-group">
              <label for="reportDate">Date</label>
              <input type="date" class="form-control" id="reportDate" name="reportDate" required>
            </div>

            <!-- input image -->
            <div class="form-group">

              <div class="panel">Upload image</div>

              <input id="newProdPhoto" type="file" class="newImage" name="newProdPhoto">

              <p class="help-block">Maximum size 2Mb</p>

              <img src="views/img/products/default/anonymous.png" class="img-thumbnail preview" alt="" width="100px">

            </div> 

          </div>

        </div>

        <!--=====================================
        FOOTER
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>

          <button type="submit" class="btn btn-success" id="addProductSave">Save Product</button>

        </div>

      </form>
	  

      <?php

          $createProduct = new ControllerProducts();
          $createProduct -> ctrCreateProducts();

        ?> 
    </div>

  </div>

</div>

<!--====  End of module add Product  ====-->

<!--=====================================
EDIT PRODUCT
======================================-->

<div id="modalEditProduct" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        HEADER
        ======================================-->

        <div class="modal-header" style="background:#DD4B39; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Edit product</h4>

        </div>

        <!--=====================================
         BODY
        ======================================-->
		
        <div class="modal-body">

          <div class="box-body">

            <!-- Select Category -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg" name="editCategory" required>
                  
                  <option id="editCategory"></option>

                </select>

              </div>

            </div>

            <!-- INPUT FOR THE CODE -->          
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editCode" name="editCode" required>

              </div>

            </div>

            <!-- INPUT FOR THE DESCRIPTION -->
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg" id="editDescription" name="editDescription" required>

              </div>

            </div>

             <!-- INPUT FOR THE STOCK -->
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" id="editStock" name="editStock" min="0" required>

              </div>

            </div>

            <!-- INPUT FOR BUYING PRICE -->
            <div class="form-group row">

                <div class="col-xs-12 col-sm-4">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                    <input type="number" class="form-control input-lg" id="editBuyingPrice" name="editBuyingPrice" step="any" min="0" required>

                  </div>

                </div>

                <!-- INPUT FOR SELLING PRICE -->
                <div class="col-xs-12 col-sm-4">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="editSellingPrice" name="editSellingPrice" step="any" min="0" required>

                  </div>

                </div>

                <!-- Discount -->
                <div class="col-xs-12 col-sm-4">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="editDiscountPrice" name="editDiscountPrice" step="any" min="0" required>

                  </div>

                </div>

            </div>
                
            <!-- CHECKBOX AND INPUT -->
            <div class="form-group row">

              <div class="col-xs-12 col-xs-6"></div>

              <div class="col-xs-12 col-xs-6"> 

                <!-- CHECKBOX PERCENTAGE -->
                <div class="col-xs-6"> 

                  <div class="form-group">   

                    <label>     

                      <input type="checkbox" class="minimal percentage">

                      Use Percentage

                    </label>

                  </div>

                </div>

                <!-- INPUT PERCENTAGE -->
                <div class="col-xs-12 col-xs-6" style="padding:0">

                  <div class="input-group"> 

                    <input type="number" class="form-control input-lg newPercentage" min="0" value="0" required>

                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                  </div>

                </div>
              
              </div>

            </div>

            <!-- INPUT DATE -->
            <div class="form-group">
              <label for="reportDate">Date</label>
              <input type="date" class="form-control" id="reportDate" name="reportDate" required>
            </div>

            <!-- INPUT TO UPLOAD IMAGE -->
             <div class="form-group">
              
              <div class="panel">Upload Image</div>

              <input type="file" class="newImage" name="editImage">

              <p class="help-block">2MB max</p>

              <img src="views/img/products/default/anonymous.png" class="img-thumbnail preview" width="100px">

              <input type="hidden" name="currentImage" id="currentImage">

            </div>

          </div>

        </div>

        <!--=====================================
        FOOTER
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>

          <button type="submit" class="btn btn-success">Save Changes</button>

        </div>

      </form>

        <?php

          $editProduct = new controllerProducts();
          $editProduct -> ctrEditProduct();

        ?>      

    </div>

  </div>

</div>



<!-- Stock Change Modal -->

<div class="modal fade" id="modalAddStock" tabindex="-1" role="dialog" aria-labelledby="stockChangeModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="stockChangeModalLabel">Update Product Stock</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>
      
      <div class="modal-body">

        <form id="stockChangeForm" role="form" method="POST" enctype="multipart/form-data">

          <div class="form-group">

            <label for="productCode">Product Code</label>

            <input type="text" class="form-control" id="productCode" name="productCode" readonly>

          </div>

          <div class="form-group">

            <label for="productName">Product Name</label>

            <input type="text" class="form-control" id="productName" name="productName" readonly>

          </div>

          <div class="form-group">

            <label for="currentStock">Current Stock</label>

            <input type="number" class="form-control" id="currentStock" name="currentStock" readonly>

          </div>

          <div class="form-group">
            
            <label for="newStock">New Stock Quantity</label>

            <input type="number" class="form-control" id="newStock" name="newStock" required>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

            <button type="submit" class="btn btn-primary" form="stockChangeForm">Save Changes</button>

          </div>

        </form>

        <?php
        
          $addStock = new controllerStocks();
          $addStock -> ctrAddStock();
        
        ?>

      </div>

    </div>

  </div>

</div>



<?php

  $deleteProduct = new controllerProducts();
  $deleteProduct -> ctrDeleteProduct();
  
?>
