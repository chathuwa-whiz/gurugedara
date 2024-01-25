<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Edit Sale

    </h1>

    <ol class="breadcrumb">

      <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Edit Sale</li>
		<!-- Log on to codeastro.com for more projects! -->
    </ol>

  </section>

  <section class="content">

    <div class="row">
      
      <!--=============================================
      THE FORM
      =============================================-->
      <div class="col-lg-6 col-xs-12">
        
        <div class="box box-default">

          <div class="box-header with-border"></div>

          <form role="form" method="post" class="saleForm">

            <div class="box-body">
                
                <div class="box">

                  <?php

                    $item = "id";
                    $value = $_GET["idSale"];

                    $sale = ControllerSales::ctrShowSales($item, $value);

                    $itemUser = "id";
                    $valueUser = $sale["idSeller"];

                    $seller = ControllerUsers::ctrShowUsers($itemUser, $valueUser);

                    $itemCustomers = "id";
                    $valueCustomers = $sale["idCustomer"];

                    $customers = ControllerCustomers::ctrShowCustomers($itemCustomers, $valueCustomers);

                    $taxPercentage = round($sale["discountPercentage"]);
                ?>

                    <!--=====================================
                    =            SELLER INPUT           =
                    ======================================-->
                  
                    
                    <div class="form-group">

                      <div class="input-group">
                        
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>

                        <input type="text" class="form-control" name="newSeller" id="newSeller" value="<?php echo $seller["name"]; ?>" readonly>

                        <input type="hidden" name="idSeller" value="<?php echo $seller["id"]; ?>">

                      </div>
					<!-- Log on to codeastro.com for more projects! -->
                    </div>


                    <!--=====================================
                    CODE INPUT
                    ======================================-->
                  
                    
                    <div class="form-group">

                      <div class="input-group">
                        
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>

                        <input type="text" class="form-control" id="newSale" name="editSale" value="<?php echo $sale["code"]; ?>" readonly>

                      </div>


                    </div>


                    <!--=====================================
                    =            CUSTOMER INPUT           =
                    ======================================-->
                  
                    <!-- Log on to codeastro.com for more projects! -->
                    <div class="form-group">

                      <div class="input-group">
                        
                        <span class="input-group-addon"><i class="fa fa-users"></i></span>

                        <select class="form-control" name="selectCustomer" id="selectCustomer">
                          
                            <option value="<?php echo $customers["id"]; ?>"><?php echo $customers["name"]; ?></option>

                            <?php 

                            $item = null;
                            $value = null;

                            $customers = ControllerCustomers::ctrShowCustomers($item, $value);

                            foreach ($customers as $key => $value) {
                              echo '<option value="'.$value["id"].'">'.$value["name"].'</option>';
                            }


                            ?>

                        </select>

                        <span class="input-group-addon"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalAddCustomer" data-dismiss="modal">Add Customer</button></span>

                      </div>
					<!-- Log on to codeastro.com for more projects! -->
                    </div>

                    <!--=====================================
                    =            PRODUCT INPUT           =
                    ======================================-->
                  
                    
                    <div class="form-group row newProduct">
                      <?php

                        $productList = json_decode($sale["products"], true);

                        foreach ($productList as $key => $value) {

                          $item = "id";
                          $valueProduct = $value["id"];
                          $order = "id";

                          $answer = ControllerProducts::ctrShowproducts($item, $valueProduct, $order);
                          // echo "<pre>";
                          //   print_r($sale); // or var_dump($answer);
                          // echo "</pre>";

                          $lastStock = $answer["stock"] + $value["quantity"];
                          
                          echo '<div class="row" style="padding:5px 15px">

			                        <!-- Product description -->

	                            <div class="col-xs-5" style="padding-right:0px">

	                              <div class="input-group">

	                                <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs removeProduct" idProduct="'.$value["id"].'"><i class="fa fa-times"></i></button></span>

	                                <input type="text" class="form-control newProductDescription" idProduct="'.$value["id"].'" name="addProductSale" value="'.$value["description"].'" readonly required>

	                              </div>

	                            </div>

	                            <!-- Product quantity -->

	                            <div class="col-xs-2">

	                              <input type="number" class="form-control newProductQuantity" name="newProductQuantity" min="1" value="'.$value["quantity"].'" stock="'.$lastStock.'" newStock="'.$value["stock"].'" required>

	                            </div>

	                            <!-- product price -->

	                            <div class="col-xs-3 enterPrice" style="padding-left:0px">

	                              <div class="input-group">

	                                <span class="input-group-addon">Rs.</span>

	                                <input type="text" class="form-control newProductPrice" realPrice="'.$answer["sellingPrice"].'" name="newProductPrice" value="'.$answer["sellingPrice"].'" readonly required>

	                              </div>

	                            </div>

			                        <!-- product discount -->

	                            <div class="col-xs-2 enterPrice" style="padding-left:0px">

	                              <div class="input-group">

	                                <span class="input-group-addon">Rs.</span>

	                                <input type="text" class="form-control newProductDiscount" id="newProductDiscount" realPrice="'.$answer["discountPrice"] * $value["quantity"].'" name="newProductDiscount" value="'.$answer["discountPrice"] * $value["quantity"].'" readonly>

	                              </div>

	                            </div>

	                          </div>';
                        }


                        ?>

                    </div>

                    <input type="hidden" name="productsList" id="productsList">

                    <!--=====================================
                    =            ADD PRODUCT BUTTON          =
                    ======================================-->
                    
                    <button type="button" class="btn btn-default hidden-lg btnAddProduct">Add Product</button>

                    <hr>
					<!-- Log on to codeastro.com for more projects! -->
                    <div class="row">

                      <!--=====================================
                        TAXES AND TOTAL INPUT
                      ======================================-->

                      <div class="col-xs-12">

                        <table class="table">
                          
                          <thead>
                            
                            <th><h2><b>Net Items Price</b></h1></th>
                            <th style="color:green;"><h1><b>Total</b></h1></th>

                          </thead>

                          <tbody>
                            
                            <tr>
                              <!-- net items price -->
                              <td style="width: 40%">

                                <div class="input-group">
                                  
                                  <span class="input-group-addon">Rs.</span>
                                  
                                  <input type="number" style="height:60px; font-weight:bold; font-size:60px;" class="form-control" name="netItemPrice" id="netItemPrice" value="<?php echo $sale["netItemsPrice"] ?>" placeholder="00000" totalSale="" readonly required>

                                  <input type="hidden" style="height:60px; font-weight:bold; font-size:60px;" name="netSaleItem" id="netSaleItem" required>

                                </div>

                              </td>

                              <!-- total -->
                              <td style="width: 30%">

                                <div class="input-group">
                                  
                                  <span class="input-group-addon">Rs.</span>
                                  
                                  <input type="number" style="color:green; height:60px; font-weight:bold; font-size:60px;" class="form-control" name="newSaleTotal" id="newSaleTotal" value="<?php echo $sale["totalPrice"] ?>" placeholder="00000" totalSale="" readonly required>

                                  <input type="hidden" style="color:green; height:60px; font-weight:bold; font-size:60px;" name="saleTotal" id="saleTotal" required>

                                </div>

                              </td>

                            </tr>

                            <!-- SECOND ROW -->
                            <tr>
                              <td><div style="height:10px"></div></td>
                            </tr>
                            <tr>
                              <th>Discount</th>
                              <th>Percentage</th>
                            </tr>

                            <!-- THIRD ROW -->
                            <tr>
                              <!-- total discount -->
                              <td style="width: 30%">

                                <div class="input-group">

                                  <span class="input-group-addon">Rs.</span>
                                  
                                  <input type="number" class="form-control" name="newDiscountSale" id="newDiscountSale" value="<?php echo $sale["discount"] ?>" placeholder="0" min="0" readonly required>

                                  <input type="hidden" name="newDiscountPrice" id="newDiscountPrice" required>

                                  <input type="hidden" name="newDiscountNetPrice" id="newDiscountNetPrice" required>

                                </div>
                              </td>

                              <!-- total discount as percentage -->
                              <td style="width: 30%">

                                <div class="input-group">
                            
                                  <input type="number" class="form-control" name="newDiscountPercentage" id="newDiscountPercentage" value="<?php echo $sale["discountPercentage"] ?>" placeholder="0" min="0" readonly required>

                                  <input type="hidden" name="newDiscountPercentagePrice" id="newDiscountPercentagePrice" required>

                                  <input type="hidden" name="newDiscountPercentageNetPrice" id="newDiscountPercentageNetPrice" required>
                            
                                  <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                                </div>

                              </td>
                              
                            </tr>

                          </tbody>

                        </table>
                        
                      </div>

                      <hr>
                      
                    </div>

                    <hr>

                    <!--=====================================
                      PAYMENT METHOD
                      ======================================-->

                    <div class="form-group row">
                      
                      <div class="col-xs-6" style="padding-right: 0">

                        <div class="input-group">
                      
                          <select class="form-control" name="newPaymentMethod" id="newPaymentMethod" required>
                            
                              <option value="">-Select Payment Method-</option>
                              <option value="cash">Cash</option>
                              <option value="CC">Credit Card</option>
                              <option value="DC">Debit Card</option>

                          </select>

                        </div>

                      </div>

                      <!-- <div class="paymentMethodBoxes"></div> -->
                          
                      <input type="hidden" name="listPaymentMethod" id="listPaymentMethod" required>

                    </div>

                    <div class="paymentMethodBoxes"></div>

                    <br>
                    
                </div>

            </div>
			<!-- Log on to codeastro.com for more projects! -->
            <div class="box-footer">
              <button type="submit" class="btn btn-success pull-right">Save Changes</button>
            </div>
          </form>

          <?php

            $editSale = new ControllerSales();
            $editSale -> ctrEditSale();
            
          ?>

        </div>

      </div>


      <!--=============================================
      =            PRODUCTS TABLE                   =
      =============================================-->


      <div class="col-lg-6 hidden-md hidden-sm hidden-xs">
        
          <div class="box box-default">
            
            <div class="box-header with-border"></div>

            <div class="box-body">
              
              <table class="table table-bordered table-hover table-striped dt-responsive salesTable">
                  
                <thead>

                   <tr>
                     
                    <th style="width:10px">#</th>
                     <th>Image</th>
                     <th style="width:30px">Code</th>
                     <th>Description</th>
                     <th>Price</th>
                     <th>Stock</th>
                     <th>Actions</th>
                     <th>Discount</th>
					<!-- Log on to codeastro.com for more projects! -->
                   </tr> 

                </thead>

              </table>

            </div>

          </div>


      </div>

    </div>

  </section>

</div>


<!--=====================================
=            module add Customer            =
======================================-->

<!-- Modal -->
<div id="modalAddCustomer" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form role="form" method="POST">
        <div class="modal-header" style="background: #DD4B39; color: #fff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Customer</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">

            <!--Input name -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input class="form-control input-lg" type="text" name="newCustomer" placeholder="Write name" required>
              </div>
            </div>

            <!--Input id document -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input class="form-control input-lg" type="number" min="0" name="newIdDocument" placeholder="Write your ID" required>
              </div>
            </div>

            <!--Input email -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input class="form-control input-lg" type="text" name="newEmail" placeholder="Email" required>
              </div>
            </div>

            <!--Input phone -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                <input class="form-control input-lg" type="text" name="newPhone" placeholder="phone" data-inputmask="'mask':'(999) 999-9999'" data-mask required>
              </div>
            </div>

            <!--Input address -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                <input class="form-control input-lg" type="text" name="newAddress" placeholder="Address" required>
              </div>
            </div>


            <!--Input phone -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input class="form-control input-lg" type="text" name="newBirthdate" placeholder="Birth Date" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Customer</button>
        </div>
      </form><!-- Log on to codeastro.com for more projects! -->

      <?php

        $createCustomer = new ControllerCustomers();
        $createCustomer -> ctrCreateCustomer();

      ?>
    </div>

  </div>
</div><!-- Log on to codeastro.com for more projects! -->

<!--====  End of module add Customer  ====-->