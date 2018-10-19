<?php 
		  if($customer_info->ISverified==1){
			$iconverified='<span style="top:0px;right:20px;position:absolute;"><img data-uk-tooltip title="Verified User" src="'.base_url().'upload/verified.png" alt="'.$customer_info->UserName.'"/></span>';
		 }
		 else{
			 $iconverified='';
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
		$OrderHTML="";
		 foreach($Customerorder as $ThisOrder){
        
        $OrderHTML.='
        
                <tr>
                    <td>
                        '.$ThisOrder->OrderID.'
                    </td>
                    <td width="150">
                        '.nl2br($ThisOrder->DeliveryAddress).'
                    </td>
                    <td>
                        '.$ThisOrder->OrderStatus.'
                    </td>
                  <td>
                        '.$ThisOrder->Amount.'
                    </td>
                    <td>
                        '.$ThisOrder->DateInserted.'
                    </td>';
					
					$OrderHTML.='<td>
                       <a href="'.base_url().'Vieworderdetails/'.$ThisOrder->OrderID.'" target="_blank">View Invoice</a>
                    </td>
                </tr> ';
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
	
	if(($orderinfo->riderstatus=="Accept") &&($orderinfo->DeliveryTime=="")){

	$orderstatusmode=$orderinfo->OrderStatus.' By CRM';

	}

else if(($orderinfo->riderstatus=="received") &&($orderinfo->DeliveryTime=="")){

	$orderstatusmode=$orderinfo->OrderStatus.' By Rider';

	}

else{

	$orderstatusmode=$orderinfo->riderstatus.' By Rider';

	}
if($customer_info->ISverified==1){
	$chk ="checked";
	}
else{
	$chk ='';
	}
if($customer_info->ISverified==0){
	$chk1 ="checked";
	}
else{
	$chk1 ='';
	}

		  ?>
<div id="page_content_inner">
  <h4 class="heading_a uk-margin-bottom">Order List</h4>
  <div class="md-card uk-margin-medium-bottom">
    <div class="md-card-content">
      <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-large-1-3">
          <h4 class="heading_c uk-margin-small-bottom">Customer Info and Delivery Info <img style="cursor:pointer;"  data-uk-tooltip="{pos:'right'}" title="Edit Info" id="Updateopenclosetime" class="pull-left" data-uk-modal="{target:'#modal_editcustomer'}" href="javascript:void(0)" alt="" src="<?php echo base_url();?>theme/default/img/edit_icon.png"></h4>
          <div id="modal_editcustomer" class="uk-modal">
                                <div class="uk-modal-dialog">
                                <button type="button" class="uk-modal-close uk-close"></button>
                                <form class="form-horizontal" method="post" action="<?php echo base_url();?>/crmlogin/updatecustomer" name="From_user" enctype="multipart/form-data"><input name="userid" value="<?php echo $customer_info->UserID;?>" title="" size="30" type="hidden">
                                <input name="orderid" value="<?php echo $orderinfo->OrderID;?>" title="" size="30" type="hidden">
                                <div class="md-input-wrapper md-input-filled">
                                <label>User Name</label>
                                <input class="md-input label-fixed" value="<?php echo $customer_info->UserName;?>" name="UserName" id="UserName" type="text">
                                    <span class="md-input-bar "></span></div>
                                
                                <div class="md-input-wrapper md-input-filled">
                                <label>User Email</label>
                               <input class="md-input label-fixed" value="<?php echo $customer_info->UserEmail;?>" name="UserEmail" id="UserEmail" type="text">
                                    <span class="md-input-bar "></span></div>
                                 <div class="md-input-wrapper md-input-filled">
                                <label>User Phone</label>
                                <input class="md-input label-fixed" value="<?php echo $customer_info->PhoneNumber;?>" name="phone" id="phone" type="text">
                                    <span class="md-input-bar "></span></div>
                                <div class="md-input-wrapper md-input-filled">
                                <label>Phone Number</label>
                                <input class="md-input label-fixed" value="<?php echo $customer_info->PhoneMobile;?>" name="mobile" id="mobile" type="text">
                                    <span class="md-input-bar "></span></div>
                                <div class="md-input-wrapper md-input-filled">
                                <label>Address</label>
                                  <textarea class="md-input selecize_init" name="address" id="address" cols="30" rows="4" style="overflow: hidden; overflow-wrap: break-word; height: 121px;"><?php echo $customer_info->Address;?></textarea><span class="md-input-bar "></span>
                                    </div>
                                <div class="md-input-wrapper md-input-filled">
                                <label>Delivery Address</label>
                                  <textarea class="md-input selecize_init" name="deladdress" id="deladdress" cols="30" rows="4" style="overflow: hidden; overflow-wrap: break-word; height: 121px;"><?php echo $orderinfo->DeliveryAddress;?></textarea><span class="md-input-bar "></span>
                                    </div>
                                    <div class="md-input-wrapper">
                                    <span class="icheck-inline">
                                        <input type="radio" name="verified" id="radio_demo_inline_1" <?php echo $chk;?> value="1" data-md-icheck />
                                        <label for="radio_demo_inline_1" class="inline-label">Yes</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="verified" id="radio_demo_inline_2" <?php echo $chk1;?> value="0" data-md-icheck />
                                        <label for="radio_demo_inline_2" class="inline-label">No</label>
                                    </span>
                                </div>
                                <input type="submit" value="Update" class="md-btn md-btn-primary">
                                </form>
                            </div>
                            </div>
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
              <div class="md-list-content"> <span class="md-list-heading"><a>Rider Name</a></span> <span class="uk-text-small uk-text-muted"><?php 
			 if(!empty($rider_info->RiderName)){echo $rider_info->RiderName;}?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Phone</a></span> <span class="uk-text-small uk-text-muted"><?php if(!empty($rider_info->phone)){echo $rider_info->phone;}?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Area</a></span> <span class="uk-text-small uk-text-muted"><?php  if(!empty($rider_info->RiderareaName)){echo $rider_info->RiderareaName;}?></span> </div>
            </li>
            <li>
              <div class="md-list-content"> <span class="md-list-heading"><a>Order Status</a></span> <span class="uk-text-small uk-text-muted"><?php echo $orderstatusmode;?></span> </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="md-card-content md-bg-grey-100">
    <div class="uk-accordion" id="my-accordion" data-uk-accordion="{showfirst: false}">
    	<h3 class="uk-accordion-title">Check Previous order history and note</h3>
        <div class="uk-accordion-content">
    	<div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                    <div class="uk-width-medium-1-1">
                                                <div class="md-input-wrapper md-input-filled"><label for="user_edit_personal_info_control">Note for customer ( <?php echo $crm_info->UserName;?> )</label><textarea class="md-input selecize_init" name="user_edit_personal_info_control" id="user_edit_personal_info_control_customer" cols="30" rows="4" style="overflow: hidden; overflow-wrap: break-word; height: 121px;"><?php echo $customer_info->notesforcusrest;?></textarea><span class="md-input-bar "></span></div>
                                            </div>
                    </div>
                    <div class="uk-width-large-1-2">
                    <div class="uk-width-medium-1-1">
                                                <div class="md-input-wrapper md-input-filled"><label for="user_edit_personal_info_control">Note for restaurant  ( <?php echo $crm_info->UserName;?> )</label><textarea class="md-input selecize_init" name="user_edit_personal_info_control" id="user_edit_personal_info_control_rest" cols="30" rows="4" style="overflow: hidden; overflow-wrap: break-word; height: 121px;"><?php echo $restaurant_info->notesforcusrest;?></textarea><span class="md-input-bar "></span></div>
                                            </div>
                    </div>
                    <div class="uk-width-large-1-2">
                    <div class="uk-grid">
                    <div class="uk-width-medium-1-2">
                      <button class="md-btn md-btn-primary" value="Submit" id="chkqty" name="product2" type="button" onclick="Updatenotes()" data-uk-button>Submit</button>
                     </div>
                     </div>
                     </div>
      </div>
      	<h3 class="md-bg-deep-purple-50">Order history  --------- order placed: <?php echo $totalorder;?>              cancelled: <?php echo $totalcancelorder;?>  Total Customer Order from this restaurant: <?php echo $totalorderthisrest;?></h3>
        <div class="md-card-content md-bg-brown-100">
      <div class="uk-grid" data-uk-grid-margin>
      	<div class="uk-width-large-1-1">
        	<table class="uk-table uk-table-condensed">
        <thead>
          <tr>
            <th>Order Number</th>
            <th>Delivery Address</th>
            <th>Order Status</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          
          <?php echo $OrderHTML;?>
         
        </tbody>
      </table>
        </div>
      </div>
      </div>
      </div>
        
      
    </div>
    </div>
     <div class="md-card-content md-bg-blue-grey-100">
     <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3">
                    <div class="uk-width-medium-1-1">
                                                <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Category</label><select id="category" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="Category" title="Select Category"  onchange="getfoodname('<?php echo $orderinfo->RestaurantID;?>')">
							<option value="" selected="selected">Choose Category</option>
							<?php echo $option;?></select><span class="md-input-bar "></span></div>
                                            </div>
                    </div>
                    <div class="uk-width-large-1-3">
                    <div class="uk-width-medium-1-1">
                     <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Products</label>
                     <select name="Foodname" id="Foodname" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Product" onchange="getproductname('<?php echo $orderinfo->RestaurantID;?>','<?php echo $orderinfo->OrderID;?>');"></select><span class="md-input-bar "></span></div>
                                            </div>
                    </div>
                    
                    <div class="uk-width-large-1-3">
                    <div class="uk-grid">
                    <div class="uk-width-medium-1-3" style="margin-top:10px;">
                    <a class="md-btn disabled" data-uk-modal="{target:'#modal_overflow'}" href="javascript:void(0)" id="addfood">Add Food</a>
                            <div id="modal_overflow" class="uk-modal">
                                <div class="uk-modal-dialog">
                                    <button type="button" class="uk-modal-close uk-close"></button>
                                    <div class="md-input-wrapper md-input-filled"><label><strong id="productname"></strong></label></div>
                                    <div class="md-input-wrapper md-input-filled">
                                    <label>Quantity</label>
                                    <input class="md-input label-fixed" value="1" name="quantity" id="quantity" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            </div>
                     </div>
                     </div>
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
            <th>Action</th>
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
            <td><a class="md-btn md-btn-danger" onclick="deletefood('<?php echo $item->CartID;?>','<?php echo $item->OrderID;?>','<?php echo $pid;?>','<?php echo $item->Price;?>','<?php echo $item->Quantity;?>','<?php echo $item->Type;?>')">Delete</a></td>
          </tr>
          <?php } ?>
          <tr>
            <td align="right"><strong>Payment Method</strong></td>
            <td><select name="psymenttype" id="psymenttype" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Payment Method" ><?php echo $paymenttype;?></select></td>
            <td align="left"><a class="md-btn md-btn-primary" onclick="updatepayment()">Submit</a></td>
            <td colspan="2" align="right"><strong>Subtotal</strong></td>
            <td align="right"><strong><?php echo $SubTotal;?></strong></td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
         	<td align="right"><strong>Delivery Method</strong></td>
            <td><select name="delivery" id="shipping" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Delivery Method" ><?php echo $delveeryselect;?></select></td>
            <td align="left"><a class="md-btn md-btn-primary" onclick="updatedelivery()">Change Method</a></td>
            <td colspan="2" align="right"><strong>Shipping Charge</strong></td>
            <td align="right"><strong><?php echo $delfee;?></strong></td>
            <td align="right">&nbsp;</td>
          </tr>
           <tr>
         	<td align="right"><strong>Discount Type:</strong></td>
            <td><strong><?php echo $orderinfo->DiscountType;?></strong></td>
            <td colspan="3" align="right"><strong>Discount:<?php echo $orderinfo->DiscountPercentage;?></strong></td>
             <td align="right"><strong><?php echo $orderinfo->Discount;?></strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
         	<td colspan="4" align="right"><strong>&nbsp;</strong></td>
            <td align="right"><strong>Vat:(<?php echo $orderinfo->Vat;?>)%</strong></td>
            <td align="right"><strong><?php echo $orderinfo->Vatamount;?></strong></td>
            <td>&nbsp;</td>
          </tr>
         <tr>
         	<td colspan="4" align="right"><strong>&nbsp;</strong></td>
            <td align="right"><strong>Service Charge:(<?php echo $orderinfo->ServiceCharge;?>)%</strong></td>
            <td align="right"><strong><?php echo $orderinfo->ServiceChargeAmount;?></strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5" align="right"><strong>Grand total</strong></td>
            <td align="right"><strong><?php echo  $SubTotal+$delfee;?></strong></td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php 
	$originalDate = $orderinfo->DateInserted;
$ordertime = date("H:m A", strtotime($originalDate));
	?>
    <div class="md-card-content">
      <table class="uk-table uk-table-condensed">
        <thead>
          <tr>
            <th>Order Time</th>
            <th>Delivary Time</th>
            <th>Order Status</th>
            <th>Remarks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          
          <tr>
            <td><div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                  	<input name="orderid" id="orderid" type="hidden" value="<?php echo $orderinfo->OrderID;?>" />
                                    <input class="md-input" value="<?php echo $ordertime;?>" id="orderdtime" name="orderdtime" readonly="readonly" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div></td>
            <td><div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="uk_tp_1">Select time</label>
                                        <input class="md-input" type="text" value="<?php echo $orderinfo->DeliveryTime;?>" id="uk_tp_1" data-uk-timepicker>
                                    </div></td>
            <td><div class="uk-width-medium-1-1">
                                    <select id="orderstatus" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="orderstatus" title="Select Status" onchange="selecttatus()">
                                        <option value="">Select Status</option>
                                		<option value="Pending" <?php if($orderinfo->OrderStatus=="Pending"){ echo "selected";}?>>Pending</option>
                                        <option value="Processing" <?php if($orderinfo->OrderStatus=="Processing"){ echo "selected";}?>>Processing</option>
                                        <option value="Delivered" <?php if($orderinfo->OrderStatus=="Delivered"){ echo "selected";}?>>Delivered</option>
                                        <option value="Cancelled" <?php if($orderinfo->OrderStatus=="Cancelled"){ echo "selected";}?>>Cancelled</option>
                                    </select>
                                    <div style="display:none" id="cancelreason">
                                    <select id="cancelreason2" name="cancelreason" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}">
                                        <option value="Out Of Zone">Out Of Zone</option>
                                        <option value="Restaurant Closed">Restaurant Closed</option>
                                        <option value="Repeat Order">Repeat Order</option>
                                        <option value="Need More Time">Need More Time</option>
                                        <option value="Restaurant Phone Off">Restaurant Phone Off</option>
                                        <option value="Invalid Contact No">Invalid Contact No.</option>
                                        <option value="Food Not Available">Food Not Available</option>
                                        <option value="Rider Not Available">Rider Not Available</option>
                                        <option value="Customer Cancelled">Customer Cancelled</option>
                                        <option value="Service Time Up">Service Time Up</option>
                                        <option value="Service Was Closed">Service Was Closed</option>
                                        <option value="Payment Unsuccessfull">Payment Unsuccessfull</option>
                                        <option value="Test order">Test order</option>
                                    </select>
                                    </div>
                                </div></td>
                                <td><textarea name="remarks" id="remarks"  cols="25" rows="3"><?php echo $orderinfo->RemarkNote;?></textarea></td>
                                <td><a class="md-btn md-btn-primary" id="submitdelevar" onclick="submitdelevar()">Submit</a></td>
          </tr>
         
        </tbody>
      </table>
    </div>
    <div class="md-card-content">
  <div class="uk-grid" data-uk-grid-margin>
    	<div class="uk-width-medium-1-4">
        <h3 class="heading_a">Send Order to Rider</h3>
        <form action="<?php echo base_url();?>RiderSMS3" method="POST">
				<input type="hidden" name="Orderid3" id="Orderid3" value="<?php echo $orderinfo->OrderID;?>">
                <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Area</label>
				<select name="area3" id="area3" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Area" onchange="getarea3();">
				<option value="" selected="selected">Choose Area</option>
							<?php foreach($allrider as $arearider){ ?>
                            <option value="<?php echo $arearider->RiderareaID;?>"><?php echo $arearider->RiderareaName;?></option>
                            <?php } ?>
						</select></div>
                        <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Rider</label>
				<select name="rider3" id="rider3" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Choose Rider" onchange="getphone3();">
				<option value="" selected="selected">Choose Area</option>
						</select></div>
				<div class="md-input-wrapper md-input-filled">
                <span id="shord3" style="display:none; border:1px solid #ccc; width:100%; background:#09F; padding:2px 10px; color:#fff;">He is taking <span id="ordnum3">0</span> Order.</span><br> 
                                    <label>Phone</label>
                                    <input class="md-input label-fixed" value="" name="RiderPhone3" id="RiderPhone3" type="text" readonly="readonly">
                                    <span class="md-input-bar "></span></div>
				<input type="submit" value="Send Order" class="md-btn md-btn-primary">
				
				</form>
        </div>
        <div class="uk-width-medium-1-4">
        <h3 class="heading_a">Send Order to Nearest Rider</h3>
        <form action="<?php echo base_url();?>RiderSMS4" method="POST">
        <input type="hidden" name="Orderid3" id="Orderid3" value="<?php echo $orderinfo->OrderID;?>">
        <input type="submit" value="Send Order" class="md-btn md-btn-primary">
        </form>
        </div>
        <div class="uk-width-medium-1-4">
        <h3 class="heading_a">Message to Rider</h3>
        <form action="<?php echo base_url();?>crmlogin/sendtomsg" method="POST">
				<input type="hidden" name="Orderid" id="Orderid" value="<?php echo $orderinfo->OrderID;?>">
                <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Area</label>
				<select name="area" id="area" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Area" onchange="getarea();">
				<option value="" selected="selected">Choose Area</option>
							<?php foreach($allrider as $arearider){ ?>
                            <option value="<?php echo $arearider->RiderareaID;?>"><?php echo $arearider->RiderareaName;?></option>
                            <?php } ?>
						</select></div>
                        <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Rider</label>
				<select name="rider" id="rider" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Choose Rider" onchange="getphone();">
				<option value="" selected="selected">Choose Area</option>
						</select></div>
				<div class="md-input-wrapper md-input-filled">
                <span id="shord" style="display:none; border:1px solid #ccc; width:100%; background:#09F; padding:2px 10px; color:#fff;">He is taking <span id="ordnum">0</span> Order.</span><br> 
                                    <label>Phone</label>
                                    <input class="md-input label-fixed" value="" name="RiderPhone" id="RiderPhone" type="text" readonly="readonly">
                                    <span class="md-input-bar "></span></div>
                                    <div class="md-input-wrapper md-input-filled">
                                    <span style="color:red; font-size:10px;">Please remove unwanted space, - , comma etc... || <br>
				ideal number should look like this : 01793111333</span>
                                    <label for="user_edit_personal_info_control">Message</label><textarea class="md-input selecize_init" name="SMSText" id="" cols="30" rows="4" style="overflow: hidden; overflow-wrap: break-word; height: 121px;"><?php echo $restaurant_info->RestaurantName;?>  Name : <?php echo $customer_info->UserName;?> Mobile :  <?php echo $customer_info->PhoneMobile;?> Phone :  <?php echo $customer_info->PhoneNumber;?> Add:<?php echo $orderinfo->DeliveryAddress;?> res-<?php echo $orderinfo->Restaurantamount;?> tk,cus-<?php echo $orderinfo->GrandTotal;?> tk.</textarea><span class="md-input-bar "></span></div>
				<input type="submit" value="Send Order" class="md-btn md-btn-primary">
				
				</form>
        </div>
        <div class="uk-width-medium-1-4">
        <h3 class="heading_a">Select Rider</h3>
        <form action="<?php echo base_url();?>crmlogin/riderselect" method="POST">
				<input type="hidden" name="Orderid2" id="Orderid2" value="<?php echo $orderinfo->OrderID;?>">
                <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Area</label>
				<select name="area2" id="area2" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Area" onchange="getarea2();">
				<option value="" selected="selected">Choose Area</option>
							<?php foreach($allrider as $arearider){ ?>
                            <option value="<?php echo $arearider->RiderareaID;?>"><?php echo $arearider->RiderareaName;?></option>
                            <?php } ?>
						</select></div>
                        <div class="md-input-wrapper"><label for="user_edit_personal_info_control">Choose Rider</label>
				<select name="rider2" id="rider2" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Choose Rider" onchange="getphone2();">
				<option value="" selected="selected">Choose Area</option>
						</select></div>
				<div class="md-input-wrapper md-input-filled">
                                    <label>Phone</label>
                                    <input class="md-input label-fixed" value="" name="RiderPhone2" id="RiderPhone2" type="text" readonly="readonly">
                                    <span class="md-input-bar "></span></div>
				<input type="submit" value="Send Order" class="md-btn md-btn-primary">
				
				</form>
        </div>
        </div>
    </div>
    <div class="md-card-content">
    <div class="uk-width-medium-1-1">
                    <div class="md-card" data-uk-grid-margin>
                        <div class="md-card-content">
                            <ul class="uk-tab" data-uk-tab="{connect:'#tabs_anim2', animation:'slide-horizontal'}">
                                <li class="uk-active"><a href="#">Permanent</a></li>
                                <li><a href="#">Flexible</a></li>
                            </ul>
                            <ul id="tabs_anim2" class="uk-switcher uk-margin">
                                <li>
                                <table class="uk-table uk-table-condensed">
                                    <thead>
                                      <tr>
                                        <th>Rider Name</th>
                                        <th>Rider Status</th>
                                        <th>Phone</th>
                                        <th>Area</th>
                                        <th>Order ON Process</th>
                                        <th>Pocket Money</th>
                                        <th>Delivered Order</th>
                                        <th>Balance</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                     <?php foreach($permanentrider as $permanent){
										$torrow=$this->Foodmart_model->getridertotalorder($permanent->RiderlistID);
										$totaldeliord=$this->Foodmart_model->ridertotaldeli($permanent->RiderlistID);
										$creditbalance=$this->Foodmart_model->ridertotalcredit($permanent->RiderlistID);
										$debitbalance=$this->Foodmart_model->ridertotaldebit($permanent->RiderlistID);
										if((empty($creditbalance->totalcredit)) && (empty($debitbalance->totaldebit))){
											$balance=0;
											}
										else{
											$balance=$creditbalance->totalcredit-$debitbalance->totaldebit;
											}
										$updatetime = $torrow->DateUpdated;
										$newTime = date("h:i A",strtotime($updatetime));
										$actualtime=date('h:i A');
										$newlogoutTime = date("h:i A",strtotime($actualtime." -15 minutes"));
										$lastupdate = strtotime($newTime);
										$sortlogout = strtotime($newlogoutTime);
										if($lastupdate<$sortlogout){
										$permanent->RiderName.$newTime."<br>";
										$inactive="<td style='background:#CC0000; color:#fff;'>Inactive</td>";
										}
										else{
										$inactive="<td>Active</td>";
										$permanent->RiderName.$newTime."<br>";
										}
										 ?> 
                                      <tr>
            							<td><?php echo $permanent->RiderName;?></td>
                                        <?php echo $inactive;?>
                                        <td><?php echo $permanent->phone;?></td>
                                        <td><?php echo $permanent->RiderareaName;?></td>
                                        <td><?php echo $torrow->totalord;?></td>
                                        <td><?php echo $totaldeliord->totalordeli;?></td>
                                        <td><?php echo $permanent->paddy_cash;?></td>
                                        <td><?php echo $balance;?></td>
                                      </tr>
                                     <?php } ?>
                                    </tbody>
                                  </table>
                                </li>
                                <li><table class="uk-table uk-table-condensed">
                                    <thead>
                                      <tr>
                                        <th>Rider Name</th>
                                        <th>Rider Status</th>
                                        <th>Phone</th>
                                        <th>Area</th>
                                        <th>Order ON Process</th>
                                        <th>Pocket Money</th>
                                        <th>Delivered Order</th>
                                        <th>Balance</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                     <?php foreach($flexiablerider as $flexiable){
										$torrow=$this->Foodmart_model->getridertotalorder($flexiable->RiderlistID);
										$totaldeliord=$this->Foodmart_model->ridertotaldeli($flexiable->RiderlistID);
										$creditbalance=$this->Foodmart_model->ridertotalcredit($flexiable->RiderlistID);
										$debitbalance=$this->Foodmart_model->ridertotaldebit($flexiable->RiderlistID);
										if((empty($creditbalance->totalcredit)) && (empty($debitbalance->totaldebit))){
											$balance=0;
											}
										else{
											$balance=$creditbalance->totalcredit-$debitbalance->totaldebit;
											}
										$updatetime = $torrow->DateUpdated;
										$newTime = date("h:i A",strtotime($updatetime));
										$actualtime=date('h:i A');
										$newlogoutTime = date("h:i A",strtotime($actualtime." -15 minutes"));
										$lastupdate = strtotime($newTime);
										$sortlogout = strtotime($newlogoutTime);
										if($lastupdate<$sortlogout){
										$flexiable->RiderName.$newTime."<br>";
										$inactive="<td style='background:#CC0000; color:#fff;'>Inactive</td>";
										}
										else{
										$inactive="<td>Active</td>";
										$flexiable->RiderName.$newTime."<br>";
										}
										 ?> 
                                      <tr>
            							<td><?php echo $flexiable->RiderName;?></td>
                                        <?php echo $inactive;?>
                                        <td><?php echo $flexiable->phone;?></td>
                                        <td><?php echo $flexiable->RiderareaName;?></td>
                                        <td><?php echo $torrow->totalord;?></td>
                                        <td><?php echo $totaldeliord->totalordeli;?></td>
                                        <td><?php echo $flexiable->paddy_cash;?></td>
                                        <td><?php echo $balance;?></td>
                                      </tr>
                                     <?php } ?>
                                    </tbody>
                                  </table></li>
                            </ul>
                        </div>
                    </div>
                </div>
    </div>
  </div>
  
</div>
<script>
 /* var accordion = UIkit.accordion(UIkit.$('#my-accordion'), {collapse:true, showfirst: true});
   accordion.find('[data-wrapper]').each(function () {
      accordion.toggleItem(UIkit.$(this), false, true); // animated true and collapse false
   });*/
 function getprice(type,price){
	  var getprice=$('input[name="select-price"]:checked').attr('role');
	  var maxtop1=$("#First_"+getprice).val();
	  var maxtop2=$("#last_"+getprice).val();
	  var getaddons=$("#select-addon-item-1-0").val();
	  var toping3=$('#extra-item-prior-0').val();
	  $("#disallowed").removeClass("disabled");
	  $("#maxtoping1").html(maxtop1);
	  $("#maxtoping2").html(maxtop2);
	  if(maxtop1>0){
	 	$("#toping1").show();
		}
		else{
			$("#toping1").hide();
			}
		if(maxtop2>0){
			$("#toping2").show();
		}
		else{
			$("#toping2").hide();
			}
		if(getaddons==""){
			$("#addons").hide();
		}
		else{
			$("#addons").show();
			}
		if(toping3==""){
			$("#singleitem").hide();
		}
		else{
			$("#singleitem").show();
			}
		$("#totalcost").html(getprice);
	 }
 function getfirsttopping(){
	 var totaltoping1=$("#maxtoping1").html();
		 if ($(":checkbox[name='topingitem1[]']:checked").length == totaltoping1){                                             
	   $('#toping1 :checkbox:not(:checked)').prop('disabled', true); 
	   $('#toping1 :checkbox:not(:checked)').parent().addClass('disabled'); 
	  }
	  else{    
	   $('#toping1 :checkbox:not(:checked)').prop('disabled', false);
	   $("#toping1 .popup-checkbox").removeClass("disabled"); 
	  }
	 }  
 function getsecondtopping(){
	var totaltoping2=$("#maxtoping2").html();
	  if ($(":checkbox[name='topingitem2[]']:checked").length == totaltoping2){                                             
	   $('#toping2 :checkbox:not(:checked)').prop('disabled', true); 
	   $('#toping2 :checkbox:not(:checked)').parent().addClass('disabled'); 
	  }
	  else{                                                    
	   $('#toping2 :checkbox:not(:checked)').prop('disabled', false);
	   $("#toping2 .popup-checkbox").removeClass("disabled"); 
	  }
	 }
function addadons(){
	var getprice=$('input[name="select-price"]:checked').attr('role');
		var totalprice=$("#totalcost").html();
		    var allVals = 0;
			$("#addons input[type=checkbox]:checked").each(function(){
				allVals += parseFloat($(this).attr('role'));
			});
			var addnewtotal= parseFloat(getprice)+parseFloat(allVals);
			var newtotal=addnewtotal.toFixed(2);
			$("#totalcost").html(newtotal); 
	}
function deletefood(cartid,ordid,proid,price,qty,type){
					var totalRowCount = $("#crtable2 tr").length;
					if(totalRowCount<=8){
						alert("You can\'t empty Order List!!!");
					return false;
					}
					var txt;
					var r = confirm("Are you sure you want to Delete this?");
					if (r == true) {
					var dataString = "productid="+proid+"&qty="+qty+"&type="+type+"&price="+price+"&orderid="+ordid+'&cartid='+cartid;
					//alert(dataString);
					$.ajax({
						type: "POST",
						url: mybaseUrl+"crmlogin/deleteitem",
						data: dataString,
						success: function(data){
							window.location.href= mybaseUrl+"Changestatus/"+ordid;
							}
						});
					 }
					}

function addtocartitem(restid,orderid,productid){
	
		if (!$("input[name='select-price']").is(':checked')) {
		   alert('Nothing is checked!');
		   var getprice="";
		   var getsize="";
		}
		else {
		var orderid=orderid;
		var id=productid;
		var type=0;
		var RID=restid;
		var acprice=$("#actualprice").val();
		var getprice=$('#allwedoption input[name="select-price"]:checked').attr('role');
		var getsize=$('#allwedoption input[name="select-price"]:checked').val();
		var maxtop1=$("#First_"+getprice).val();
	    var maxtop2=$("#last_"+getprice).val();
		var numoftop1=$("#maxtop1_"+getprice).val();
		var numoftop2=$("#maxtop2_"+getprice).val();
		var totalqty=$("#quantity").val();
		var toping3=$('input[name="select-addon-prior"]:checked').val();
		 if ($(":checkbox[name='topingitem1[]']:checked").length != maxtop1){ 
		 	alert("Please select Toping1!!!");
			return false;
		 }
		 if($(":checkbox[name='topingitem2[]']:checked").length != maxtop2){
			alert("Please select Toping2!!!");
			return false;
		 }
		if($(":radio[name='select-addon-prior']:checked").length ==0){
			alert("Please Chose any One items!!!");
			return false;
		 }
		 var toping1 = [];
			$("#toping1 input[type=checkbox]:checked").each(function(){
				toping1.push($(this).val());
			}); 
		 var toping2 = [];
			$("#toping2 input[type=checkbox]:checked").each(function(){
				toping2.push($(this).val());
			}); 

		var choosetoping=$(":radio[name='select-addon-prior']:checked").val();
		var addons = [];
			$("#addons input[type=checkbox]:checked").each(function(){
				addons.push($(this).val());
			});
			
			 var allprice = 0;
			$("#addons input[type=checkbox]:checked").each(function(){
				allprice += parseFloat($(this).attr('role'));
			});
							
			var addnewtotal= parseFloat(getprice)+parseFloat(allprice);
			var newtotal=addnewtotal.toFixed(2);
	 
	  var dataString = "ProductID="+id+"&orderid="+orderid+"&RID="+RID+"&price="+newtotal+"&type="+getsize+"&itemty="+totalqty+"&acprice="+getprice+"&toping1="+toping1+"&toping2="+toping2+"&toping3="+toping3+"&addons="+addons+'&orgprice='+acprice;
	  
	  //alert(dataString);
	  var myurl = mybaseUrl+'crmlogin/addnewfood';
	  $.ajax({
			type: "POST",
			url: myurl,
			data: dataString,
			success: function(data){
				  window.location.href= mybaseUrl+"Changestatus/"+orderid;
				}
			});
		}
	}
function selecttatus(){
		var status = $("#orderstatus").val();
		if(status=="Cancelled"){
			$("#cancelreason").show();
			}
		else{
			$("#cancelreason").hide();
			}
}
function submitdelevar(){ 
	var errorfound="";
	var orderid=$("#orderid").val();
	var delvartime=$("#uk_tp_1").val();
	var orderstatus=$("#orderstatus").val();
	var remarks=$("#remarks").val();
	if(orderstatus=="Cancelled"){
		var reason=$("#cancelreason2").val();
		}
	else{
		var reason="";
		}
	if(delvartime==""){
		alert("Please Select Delivary Time");
		var errorfound=1;
		return false;
		}
	if(orderstatus==""){
		alert("Please Select Status");
		var errorfound=1;
		return false;
		}
	
	  if(errorfound==""){
		var dataString = {'delvartime':delvartime,'orderstatus':orderstatus,'remarks':remarks,'orderid':orderid,'reason':reason};
		 var myurl = mybaseUrl+'crmlogin/submitdeliver';
			$.ajax({
					type: "POST",
					url:myurl,
					data: dataString,
					success: function(data){
						alert("Successfully Submit Information");
						window.location.href =mybaseUrl+"Pending-order";
					}
				});
	  }
	}
function getarea(){
				var area = $("#area").val();
				var dataString = "area="+area;
				$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/getriderlist",
				data: dataString,
				success: function(data){
					$('#rider').selectize()[0].selectize.destroy();
					$("#rider").html(data);
					$('select#rider').selectize({});
					}
				});
				}
function getphone(){
	var rider = $("#rider").val();
	var dataString = "rider="+rider;
	$.ajax({
				dataType:"json",
				type: "GET",
				async:true,
				url: mybaseUrl+"crmlogin/getphone/"+rider,
				success: function(data){
					$("#RiderPhone").val(data.phone);
					$("#shord").show();
					$("#ordnum").html(data.totalord)
					}
				});
	}
function getarea2(){
				var area = $("#area2").val();
				var dataString = "area="+area;
				$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/getriderlist",
				data: dataString,
				success: function(data){
					$('#rider2').selectize()[0].selectize.destroy();
					$("#rider2").html(data);
					$('select#rider2').selectize({});
					}
				});
				}
function getphone2(){
	var rider = $("#rider2").val();
	var dataString = "rider="+rider;
	$.ajax({
				dataType:"json",
				type: "GET",
				async:true,
				url: mybaseUrl+"crmlogin/getphone/"+rider,
				success: function(data){
					$("#RiderPhone2").val(data.phone);
					}
				});
	}				
function getarea3(){
				var area = $("#area3").val();
				var dataString = "area="+area;
				$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/getriderlist",
				data: dataString,
				success: function(data){
					$('#rider3').selectize()[0].selectize.destroy();
					$("#rider3").html(data);
					$('select#rider3').selectize({});
					}
				});
				}
function getphone3(){
	var rider = $("#rider3").val();
	var dataString = "rider="+rider;
	$.ajax({
				dataType:"json",
				type: "GET",
				async:true,
				url: mybaseUrl+"crmlogin/getphone/"+rider,
				success: function(data){
					$("#RiderPhone3").val(data.phone);
					$("#shord3").show();
					$("#ordnum3").html(data.totalord)
					}
				});
	}
function updatepayment(){
				var paymethod = $("#psymenttype").val();
				var ordid ='<?php echo $orderinfo->OrderID;?>';
				var dataString = "paymethod="+paymethod+"&orderid="+ordid;
				$.ajax({
						type: "POST",
						url: mybaseUrl+"crmlogin/updatepaymentstatus",
						data: dataString,
						success: function(data){
							alert("Successfully Changed the payment Method!!");
							}
						});
				}
function updatedelivery(){
				var shipping = $("#shipping").val();
				var ordid = '<?php echo $orderinfo->OrderID;?>';
				var curstatus= '<?php echo $orderinfo->Shipping;?>';
				var oldgrtotal = '<?php echo $orderinfo->GrandTotal;?>';
				var dataString = "shipping="+shipping+"&orderid="+ordid+"&curstatus="+curstatus+"&oldgrtotal="+oldgrtotal;
				$.ajax({
						type: "POST",
						url: mybaseUrl+"crmlogin/updateshipping",
						data: dataString,
						success: function(data){
							alert("Successfully Changed the Shipping Method!!");
							window.location=window.location.href;
							}
						});
				}
function Updatenotes(){
	var customertexst = $("#user_edit_personal_info_control_customer").val();
	var restaurantexst = $("#user_edit_personal_info_control_rest").val();
	var restid = '<?php echo $restaurant_info->UserID;?>';
	var customerid= '<?php echo $customer_info->UserID;?>';
	var customertexst=encodeURIComponent(customertexst);
	var restaurantexst=encodeURIComponent(restaurantexst);
	var dataString = "customertsxt="+customertexst+"&restaurantexst="+restaurantexst+"&restid="+restid+"&customerid="+customerid;
		$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/updatenotes",
				data: dataString,
				success: function(data){
					alert("Successfully Updates Note");
					window.location=window.location.href;
					}
				});
	}
</script>
