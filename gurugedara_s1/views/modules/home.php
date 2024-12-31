<?php
  $item = null;
  $value = null;
  $table = "sales";

  $sales = ModelSales::mdlShowSales($table, $item, $value);

  $totalSaleAmount = 0; // Variable to store total sale amount
			$previousDate = null; // Variable to store the previous date
	
			foreach ($sales as $row => $item){
	
				// Calculate sale amount for the current sale
				$saleAmount = $item["totalPrice"];
        
				// Check if the date has changed
				$currentDate = substr($item["saledate"],0,10);
				if ($currentDate != $previousDate) {
					$previousDate = $currentDate; // Update previous date
					$totalSaleAmount = 0; // Reset total sale amount for the new day
				}

				$totalSaleAmount += $saleAmount; // Add sale amount to total
	
			}
?>

<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Dashboard
      
      <small>Control panel</small>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Dashboard</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">
      
      <?php

        if($_SESSION["profile"] =="Administrator"){

          include "home/top-boxes.php";

        }

      ?>
    
    </div><!-- Log on to codeastro.com for more projects! -->
    
    <div class="row">

      <div class="col-lg-12">

      <?php

        if($_SESSION["profile"] =="Administrator") {

          include "reports/sales-graph.php";

        }

      ?>
      
      </div>

      <div class="col-lg-6">
        
        <?php

          if($_SESSION["profile"] =="Administrator"){

            include "reports/bestseller-products.php";

          }

        ?>

      </div><!-- Log on to codeastro.com for more projects! -->

       <div class="col-lg-6">
        
        <?php

          if($_SESSION["profile"] =="Administrator"){

            include "home/recent-products.php";

          }

        ?>

      </div>

      <div class="col-lg-12">
           
        <?php

        if($_SESSION["profile"] =="Special" || $_SESSION["profile"] =="Seller"){

           echo '<div class="box box-default">

                  <div class="box-header">

                    <h1>Welcome ' .$_SESSION["name"].'</h1>

                  </div>
                  <center>
                  <div class="box-header">

                    <h1>Today Income : Rs. ' .$totalSaleAmount.'</h1>

                  </div>
                  </center>

                </div>';

        }

        ?>

      </div>

    </div>

  </section>

</div>
<!-- Log on to codeastro.com for more projects! -->
