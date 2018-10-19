<?php 
		  if($customer_info->ISverified==1){
			$iconverified='<span style="top:0px;right:20px;position:absolute;"><img data-uk-tooltip title="Verified User" src="'.base_url().'upload/verified.png" alt="'.$customer_info->UserName.'"/></span>';
		 }
if($totaldeliverorder>49 && $totaldeliverorder<99){
		$iconverified='<span style="top:0px;right:20px;position:absolute;"><img data-uk-tooltip src="'.base_url().'upload/premium1.png" title="Premium User" alt="'.$customer_info->UserName.'"/></span>';
		}
	else if($totaldeliverorder>99){
		$iconverified='<span style="top:0px;right:20px;position:absolute;"><img data-uk-tooltip src="'.base_url().'upload/signature.png" title="Signature User" alt="'.$customer_info->UserName.'"/></span>';
		}
		
		if($orderinfo->delivarytype==2){
		$preorderdate=$orderinfo->preordeDate;
		$pretime=$orderinfo->preordertime;
		$ordertype="Pre Order Delivary";
		$orderdate="Delivary Date: ".$preorderdate;
		$ordertime="Delivary Time: ".$pretime;
		}
	else{
		$ordertype="As Soon As Possible";
		}
		if($restaurant_info->isnonfoodmart==1){
		$nonf= '<span style="top:10px;right:20px;position:absolute;"> (Non Foodmart)</span>';
		}
	else{
		$nonf= '';
		}
		
		$option="";
		 foreach($allcategory as $foodrow){
		$option.='<option value="'.$foodrow->CategoryID.'">'.$foodrow->CategoryName.'</option>';
		 }
		 
		 if($orderinfo->Shipping==1){
			$DeliveryMethod="Delivery";
			$delfee=$orderinfo->DeliveryFee;
			$delveeryselect = '<option value="1" selected="selected">Delivery</option>
							<option value="0">Pick Up</option>';
		}else if($orderinfo->Shipping==0){
			$DeliveryMethod="Pick Up";
			$delfee='0';
			$delveeryselect = '<option value="1">Delivery</option>
							<option value="0" selected="selected">Pick Up</option>';
		}
		
		
		if($orderinfo->PaymentMethod==1){
		$PaymentMethod="Cash On Delivery";
		$paymenttype = '<option value="1" selected="selected">Cash On Delivery</option>
							<option value="2">Online Card Payment</option>
							<option value="3">Bkash Payment</option>';
	}else if($orderinfo->PaymentMethod==2){
		$PaymentMethod="Online Card Payment";
		$paymenttype = '<option value="1">Cash On Delivery</option>
							<option value="2" selected="selected">Online Card Payment</option>
							<option value="3">Bkash Payment</option>';
	}else if($orderinfo->PaymentMethod==3){
		$PaymentMethod="Bkash Payment";
		$paymenttype = '<option value="1">Cash On Delivery</option>
							<option value="2">Online Card Payment</option>
							<option value="3"  selected="selected">Bkash Payment</option>';
	}
		  ?>
<div id="page_content_inner">
  <h4 class="heading_a uk-margin-bottom">Order List</h4>
  <div class="md-card uk-margin-medium-bottom">
    <div class="md-card-content">
      <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-large-1-3">
          <h4 class="heading_c uk-margin-small-bottom">Customer Info and Delivery Info </h4>
          <ul class="md-list md-list-addon  md-bg-indigo-50">
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a><?php echo $customer_info->UserName;?></a> <?php echo $iconverified;?></span> <span class="uk-text-small uk-text-muted">Name</span> </div>
            </li>
            <li>
              <div class="md-list-addon-element"> <i class="md-list-addon-icon material-icons">&#xE158;</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $customer_info->UserEmail;?></span> <span class="uk-text-small uk-text-muted">Email</span> </div>
            </li>
            <li>
              <div class="md-list-addon-element"> <i class="md-list-addon-icon material-icons">&#xE0CD;</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $customer_info->PhoneNumber;?></span> <span class="uk-text-small uk-text-muted">Phone</span> </div>
            </li>
            <li>
              <div class="md-list-addon-element"> <i class="md-list-addon-icon material-icons">&#xE0CD;</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $customer_info->PhoneMobile;?></span> <span class="uk-text-small uk-text-muted">Mobile</span> </div>
            </li>
             <li>
             <div class="md-list-addon-element"> <i class="material-icons">&#xE88A;</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $customer_info->Address;?></span> <span class="uk-text-small uk-text-muted">Address</span> </div>
            </li>
            <li>
             <div class="md-list-addon-element"> <i class="material-icons">&#xE88A;</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $orderinfo->DeliveryAddress;?></span> <span class="uk-text-small uk-text-muted">Delivary Address</span> </div>
            </li>
            <li>
             <div class="md-list-addon-element"> <i class="material-icons">shopping_cart</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $ordertype;?></span><?php if(!empty($preorderdate)){?> <span class="uk-text-small uk-text-muted"><?php echo $orderdate;?><br /><?php echo $ordertime;?></span> <?php } ?></div>
            </li>
            <li>
             <div class="md-list-addon-element"> <i class="material-icons">comment</i> </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $orderinfo->Instruction;?></span> <span class="uk-text-small uk-text-muted">Instruction</span> </div>
            </li>
            <li>
             <div class="md-list-addon-element"> <i class="material-icons">comment</i>  </div>
              <div class="md-list-content"> <span class="md-list-heading"><?php echo $orderinfo->foodinstraction;?></span> <span class="uk-text-small uk-text-muted">Food Instruction</span> </div>
            </li>
          </ul>
        </div>
        <div class="uk-width-large-1-3">
          <h4 class="heading_c uk-margin-small-bottom">Retaurant Info</h4>
          <ul class="md-list md-bg-blue-50">
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Retaurant Name</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->RestaurantName.$nonf;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Email</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->UserEmail;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Type</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->Speciality;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Commission</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->Commission;?> %</span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Phone</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->PhoneMobile;?></span> </div>
            </li>
             <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Delivery Time</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->DeliveryTime;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Open/Close Time</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->OpeningTime.' - '.$restaurant_info->ClosingTime;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>City</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->City;?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Address</a></span> <span class="uk-text-small uk-text-muted"><?php echo $restaurant_info->Address;?></span> </div>
            </li>
          </ul>
        </div>
        <div class="uk-width-large-1-3">
          <h4 class="heading_c uk-margin-small-bottom">Rider Info</h4>
          <ul class="md-list md-bg-blue-50">
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Rider Name</a></span> <span class="uk-text-small uk-text-muted"><?php echo $rider_info->RiderName.$nonf;?></span> </div>
            </li>
            
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Phone</a></span> <span class="uk-text-small uk-text-muted"><?php echo $rider_info->phone;?></span> </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="md-card-content" id="getresult">
      <table class="uk-table uk-table-condensed md-bg-deep-purple-50" id="crtable2">
        <thead>
          <tr>
            <th>SI</th>
            <th>Category</th>
            <th>Product Name</th>
            <th>Unit Price (BDT)</th>
            <th>Quantity </th>
            <th style="text-align:right;">Total (BDT)</th>
          </tr>
        </thead>
        <tbody class="md-bg-brown-50">
          <?php 
		  $i=0;
		  $SubTotal='';
		foreach($iteminfo as $item){ 
		
		$i++;
		$pid=$item->ProductID;
		$Total=$item->Price*$item->Quantity;
		$SubTotal+=$Total;
		$products= $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $pid));
		//print_r($allqty);
		?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $products->CategoryName;?></td>
            <td><?php echo $products->ProductName;?></td>
            <td><?php echo $item->Price;?></td>
            <td><?php echo $item->Quantity;?></td>
            <td align="right"><?php echo $Total;?></td>
          </tr>
          <?php } ?>
          <tr>
            <td align="right"><strong>Payment Method</strong></td>
            <td><?php echo $PaymentMethod;?></td>
            <td align="left"></td>
            <td colspan="2" align="right"><strong>Subtotal</strong></td>
            <td align="right"><strong><?php echo $SubTotal;?></strong></td>
          </tr>
          <tr>
         	<td align="right"><strong>Delivery Method</strong></td>
            <td><?php echo $DeliveryMethod;?></td>
            <td align="left"></td>
            <td colspan="2" align="right"><strong>Shipping Charge</strong></td>
            <td align="right"><strong><?php echo $delfee;?></strong></td>
          </tr>
           <tr>
         	<td align="right"><strong>Discount Type:</strong></td>
            <td><strong><?php echo $orderinfo->DiscountType;?></strong></td>
            <td colspan="3" align="right"><strong>Discount:<?php echo $orderinfo->DiscountPercentage;?></strong></td>
             <td align="right"><strong><?php echo $orderinfo->Discount;?></strong></td>
          </tr>
          <tr>
         	<td colspan="4" align="right"><strong>&nbsp;</strong></td>
            <td align="right"><strong>Vat:(<?php echo $orderinfo->Vat;?>)%</strong></td>
            <td align="right"><strong><?php echo $orderinfo->Vatamount;?></strong></td>
          </tr>
         <tr>
         	<td colspan="4" align="right"><strong>&nbsp;</strong></td>
            <td align="right"><strong>Service Charge:(<?php echo $orderinfo->ServiceCharge;?>)%</strong></td>
            <td align="right"><strong><?php echo $orderinfo->ServiceChargeAmount;?></strong></td>
          </tr>
          <tr>
            <td colspan="5" align="right"><strong>Grand total</strong></td>
            <td align="right"><strong><?php echo  $SubTotal+$delfee;?></strong></td>
          </tr>
        </tbody>
      </table>
    </div>
   
  </div>
</div>

