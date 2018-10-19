        <div id="page_content_inner">
        	<div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                			<ul class="uk-tab">
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Incomming-order" class="md-btn md-btn-wave waves-effect waves-button">Incomming Order</a></li>
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Mypending-order" class="md-btn md-btn-wave waves-effect waves-button">My Pending Order</a></li>
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Pending-order" class="md-btn md-btn-wave waves-effect waves-button">Pending Order</a></li>
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Delivered-order" class="md-btn md-btn-wave waves-effect waves-button">Completed Order</a></li>
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Cancel-order" class="md-btn md-btn-wave waves-effect waves-button">Cancel Order</a></li>                            
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>Addnew-order" class="md-btn md-btn-primary">Place New Order</a></li>
                                </ul>
                </div>
            </div>
            <h4 class="heading_a uk-margin-bottom">Add New Order</h4>
            <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content" id="orderlist">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3">
                    <div class="uk-width-medium-1-1">
                                                <select id="location" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="location" title="Select Location" onchange="getrestaurant()" required>
                                                    <option value="">Select Location</option>
                                                    <?php  foreach ($alllocation as $location){?>
                                            <option value="<?php echo $location->ResAarea;?>"><?php echo $location->ResAarea;?></option>
                                            <?php } ?>
                                                </select>
                                            </div>
                    </div>
                    <div class="uk-width-large-1-3">
      				  <div class="uk-width-medium-1-1">
                                    <select id="restaurant" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="restaurant" title="Select Restaurant" onchange="categorylist()">
                                        <option value="">Select Restaurant</option>
                                       
                                    </select>
                                </div>
                    </div>
                    <div class="uk-width-large-1-3">
                    <div class="uk-width-medium-1-1">
                                                <select id="category" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="category" title="Select Category" onchange="getallproduct()">
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                    </div>
      		</div>
                	<div class="md-card-content md-bg-deep-purple-50">
                        <div class="uk-form-row">
                            <div class="uk-grid" data-uk-grid-margin="">
                        		 <div class="uk-width-5-10">
                                 	<div class="md-card">
                        				<div class="md-card-content" id="listproduct">
                           		 			
                                        </div>
                                    </div>
                           		</div>
                           		 <div class="uk-width-5-10">
                                 	<div class="md-card">
                        				<div class="md-card-content">
                           		 			<div class="md-card-content" id="cartproduct">
                                             <input name="carttotal" id="carttotal" type="hidden" value="<?php echo $subtotal = $this->cart->total();?>" />   
                           		 					<?php if($this->cart->contents()>0){
			 ?>         
<table class="uk-table uk-table-condensed md-bg-deep-purple-50" id="crtable2">
        <thead>
          <tr>
            <th>Food Name</th>
            <th>Unit Price (BDT)</th>
            <th>Quantity </th>
            <th style="text-align:right;">Total (BDT)</th>
          </tr>
        </thead>
        <tbody class="md-bg-brown-50" id="allcartitem">
        <?php $mycart=$this->cart->contents();
		arsort($mycart);
	 		$z=0;
			$SubTotal='';
			$delfee='';
			$vatpercent='';
			$servicepercent='';
			foreach ($mycart as $item){
			$z++;
			$restid=$item['RID'];
			$Total=$item['price']*$item['qty'];
			$SubTotal+=$Total;
			$restinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restid));
			$delfee=$restinfo->DeliveryFee;
			$vatpercent=$restinfo->Vat;
			$servicepercent=$restinfo->ServiceCharge;
			?>
          <tr>
            <td><?php echo $item['name'];?><?php if($item['type']!=""){?><br/><span class='item-toping'><?php echo $item['type'];?></span><?php  }?></td>
            <td><?php echo $item['price'];?></td>
            <td><?php echo $item['qty'];?></td>
            <td align="right"><a onclick="adddeleteQty('<?php echo $item['rowid'];?>',<?php echo $item['qty'];?>,<?php echo $item['RID'];?>,'add')"><i class="material-icons">add</i></a>&nbsp;<?php echo $item['price']*$item['qty'];?>&nbsp;<a onclick="adddeleteQty('<?php echo $item['rowid'];?>',<?php echo $item['qty'];?>,<?php echo $item['RID'];?>,'del');"><i class="material-icons">remove</i></a></td>
          </tr>
          <?php } 
		  if($vatpercent>0){
			$vat= $SubTotal*$vatpercent/100;
			}
		else{
			$vat= "0.00";
			}
		if($servicepercent>0){
			$service= $SubTotal*$servicepercent/100;
			}
		else{
			$service= "0.00";
			}
			$grandtotal=$SubTotal+$vat+$service+$delfee;
		  ?>
           <tr>
            <td colspan="3" align="right"><strong>Subtotal</strong></td>
            <td align="right"><strong><?php echo $SubTotal;?>/=</strong></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><strong>Delivary Fee</strong></td>
            <td align="right"><strong><?php echo $delfee;?>/=</strong></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><strong>Vat:(<?php echo $vatpercent;?>)%</strong></td>
            <td align="right"><strong><?php echo $vat;?></strong></td>
          </tr>
         <tr>
            <td colspan="3" align="right"><strong>Service Charge:(<?php echo $servicepercent;?>)%</strong></td>
            <td align="right"><strong><?php echo $service;?></strong></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><strong>Grand total</strong></td>
            <td align="right"><strong><?php echo $grandtotal;?>/=</strong></td>
          </tr>
        </tbody>
      </table>
      
	 <?php } ?>	
                                        	</div>
                                        </div>
                                    </div>
                           		</div>
                        	</div>
                        </div>
                    
                    </div>	
                    <div class="md-card-content md-bg-teal-50">
                       <?php 
        $message=$this->session->userdata('message');
        if($message)
        {?>
                <div class="uk-alert uk-alert-success" data-uk-alert="">
                               <?php echo $message;
            $this->session->unset_userdata('message');
            ?>
                            </div>
               <?php }
        ?>
                        <form class="uk-form-stacked" id="wizard_advanced_form" name="frm_validate" action="<?php echo base_url();?>crmlogin/Placeneworder" method="post" onsubmit="return validateStandard(this)" enctype="multipart/form-data">
                        
                           <div class="uk-form-row">
                            <div class="uk-grid" data-uk-grid-margin="">
                           <div class="uk-width-1-2">
                           	 <div class="md-card">
                        		<div class="md-card-content">
                             	
                                <div class="uk-width-medium-1-1 uk-row-first">
                                  <div class="md-input-wrapper">
                                    <label>Customer Name</label>
                                    <input class="md-input" name="name" id="name" required type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>
                                
                                <div class="uk-width-medium-1-1">
                                    <label>Email</label>
                                    <input class="md-input" id="email" name="email" required type="text">
                                    <span class="md-input-bar "></span>
                               </div>
                                <div class="uk-width-medium-1-1">
                                    <label>Phone</label>
                                    <input class="md-input" id="Phone" name="Phone" required type="text">
                                    <span class="md-input-bar "></span>
                               </div>
                               
                                
                               <div class="uk-width-medium-1-1">
                                    <label>Delivery Address</label>
                                    <textarea class="md-input" name="Address" id="Address" cols="30" rows="3"></textarea>
                                    <span class="md-input-bar "></span>
                               </div>
                              <div class="uk-grid">
                                    <div class="uk-width-medium-1-6">
                                        <input class="md-btn md-btn-primary" value="Submit" name="branch" type="button" onclick="submitorder()">
                                    </div>
                              </div>
                            </div>
                            	</div>
                           </div>
                            <div class="uk-width-1-2">
                            	 <div class="md-card">
                        			<div class="md-card-content">
                                		<div class="uk-width-medium-1-1">
                              <span class="uk-form-help-block">Shipping Method</span>
                              		<span class="icheck-inline">
                                        <input type="radio" checked name="shipping-method" id="delivary" value="1" data-md-icheck />
                                        <label for="delivary" class="inline-label">Delivery</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="shipping-method" id="Pickup" value="0" data-md-icheck />
                                        <label for="Pickup" class="inline-label">Pickup</label>
                                    </span>
                             </div>
                                        <div class="uk-width-medium-1-1">
                              <span class="uk-form-help-block">Payment Method</span>
                              		<span class="icheck-inline">
                                        <input type="radio" name="payment-method" id="radio_demo_inline_1" value="2" data-md-icheck />
                                        <label for="radio_demo_inline_1" class="inline-label">Online</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" checked name="payment-method" id="radio_demo_inline_2" value="1" data-md-icheck />
                                        <label for="radio_demo_inline_2" class="inline-label">Cash on Delivery</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="payment-method" id="radio_demo_inline_3" value="3" data-md-icheck />
                                        <label for="radio_demo_inline_3" class="inline-label">Bkash Payment</label>
                                    </span>
                             </div>
                                	</div>
                                 </div>
                            </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<script>
function getrestaurant(){
	var location=$("#location").val();
	var dataString = 'location='+location;
	$.ajax({
			type: "POST",
			url: mybaseUrl+"crmlogin/restaurantlist",
			data: dataString,
			success: function(data){
				$('#restaurant').selectize()[0].selectize.destroy();
				$("#restaurant").html(data);
				$('select#restaurant').selectize({});
				}
			});
	}
function categorylist(){
	var restaurant=$("#restaurant").val();
	var dataString = 'restaurant='+restaurant;
	$.ajax({
			type: "POST",
			url: mybaseUrl+"crmlogin/restcategorylist",
			data: dataString,
			success: function(data){
				$('#category').selectize()[0].selectize.destroy();
				$("#category").html(data);
				$('select#category').selectize({});
				$('#cartproduct').empty();
				$('#restid').val(restaurant);
				}
			});
	}
function getallproduct(){
	var catename = $("#category").val();
	var restaurant=$("#restaurant").val();
	var dataString = "catename="+catename+'&restid='+restaurant;
	$.ajax({
	type: "POST",
	url: mybaseUrl+"crmlogin/getproductlist",
	data: dataString,
	success: function(data){
		$("#listproduct").html(data);
		$("#dt_colVis").dataTable({});
		}
	});
	}
function getprice(type,price,pid){
	  var getprice=$('input[name="select-price"]:checked').attr('role');
	  var maxtop1=$("#First_"+pid+getprice).val();
	  var maxtop2=$("#last_"+pid+getprice).val();
	  var getaddons=$("#select-addon-item-1-0"+pid).val();
	  var toping3=$('#extra-item-prior-0'+pid).val();
	  $("#disallowed"+pid).removeClass("disabled");
	  $("#maxtoping1"+pid).html(maxtop1);
	  $("#maxtoping2"+pid).html(maxtop2);
	  if(maxtop1>0){
	 	$("#toping1"+pid).show();
		}
		else{
			$("#toping1"+pid).hide();
			}
		if(maxtop2>0){
			$("#toping2"+pid).show();
		}
		else{
			$("#toping2"+pid).hide();
			}
		if(getaddons==""){
			$("#addons"+pid).hide();
		}
		else{
			$("#addons"+pid).show();
			}
		if(toping3==""){
			$("#singleitem"+pid).hide();
		}
		else{
			$("#singleitem"+pid).show();
			}
		$("#totalcost"+pid).html(getprice);
	 }
 function getfirsttopping(pid){
	 var totaltoping1=$("#maxtoping1"+pid).html();
		 if ($(":checkbox[name='"+pid+"topingitem1[]']:checked").length == totaltoping1){                                             
	   $('#toping1'+pid+' :checkbox:not(:checked)').prop('disabled', true); 
	   $('#toping1'+pid+' :checkbox:not(:checked)').parent().addClass('disabled'); 
	  }
	  else{    
	   $('#toping1'+pid+' :checkbox:not(:checked)').prop('disabled', false);
	   $('#toping1'+pid+' .popup-checkbox').removeClass("disabled"); 
	  }
	 }  
 function getsecondtopping(pid){
	var totaltoping2=$("#maxtoping2"+pid).html();
	  if ($(":checkbox[name='"+pid+"topingitem2[]']:checked").length == totaltoping2){                                             
	   $('#toping2'+pid+' :checkbox:not(:checked)').prop('disabled', true); 
	   $('#toping2'+pid+' :checkbox:not(:checked)').parent().addClass('disabled'); 
	  }
	  else{                                                    
	   $('#toping2'+pid+' :checkbox:not(:checked)').prop('disabled', false);
	   $('#toping2'+pid+' .popup-checkbox').removeClass("disabled"); 
	  }
	 }
function addadons(pid){
	var getprice=$('input[name="select-price"]:checked').attr('role');
		var totalprice=$("#totalcost"+pid).html();
		    var allVals = 0;
			$('#addons'+pid+' input[type=checkbox]:checked').each(function(){
				allVals += parseFloat($(this).attr('role'));
			});
			var addnewtotal= parseFloat(getprice)+parseFloat(allVals);
			var newtotal=addnewtotal.toFixed(2);
			$("#totalcost"+pid).html(newtotal); 
	}
function addtocartitem(pid,pname,restid){
	
		if (!$("input[name='select-price']").is(':checked')) {
		   alert('Nothing is checked!');
		   var getprice="";
		   var getsize="";
		}
		else {
		var id=pid;
		var name=pname;
		var type=0;
		var RID=restid;
		var SRID=restid;
		var acprice=$("#actualprice"+pid).val();
		var getprice=$('#allwedoption'+pid+' input[name="select-price"]:checked').attr('role');
		var getsize=$('#allwedoption'+pid+' input[name="select-price"]:checked').val();
		var maxtop1=$("#First_"+pid+getprice).val();
	    var maxtop2=$("#last_"+pid+getprice).val();
		var numoftop1=$("#maxtop1_"+pid+getprice).val();
		var numoftop2=$("#maxtop2_"+pid+getprice).val();
		var discount=$("#discount_"+pid+getprice).val();
		var xx=$("#size_"+pid+getprice).val();
		var itemnote=0;
		var offrtxt=0;
		var toping3=$('input[name="select-addon-prior'+pid+'"]:checked').val();
		 if ($(":checkbox[name='"+pid+"topingitem1[]']:checked").length != maxtop1){ 
		 	alert("Please select Toping!!!");
			return false;
		 }
		 if($(":checkbox[name='"+pid+"topingitem2[]']:checked").length != maxtop2){
			alert("Please select Toping2!!!");
			return false;
		 }
		if(($(':radio[name="select-addon-prior'+pid+'"]:checked').length ==0)&&(toping3=="")){
			alert("Please Chose any One items!!!");
			return false;
		 }
		 var toping1 = [];
			$('#toping1'+pid+' input[type=checkbox]:checked').each(function(){
				toping1.push($(this).val());
			}); 
		 var toping2 = [];
			$('#toping2'+pid+' input[type=checkbox]:checked').each(function(){
				toping2.push($(this).val());
			}); 

		var choosetoping=$(':radio[name="select-addon-prior'+pid+'"]:checked').val();
		var addons = [];
			$('#addons'+pid+' input[type=checkbox]:checked').each(function(){
				addons.push($(this).val());
			});
			
			 var allprice = 0;
			$('#addons'+pid+' input[type=checkbox]:checked').each(function(){
				allprice += parseFloat($(this).attr('role'));
			});
							
			var addnewtotal= parseFloat(getprice)+parseFloat(allprice);
			var newtotal=addnewtotal.toFixed(2);

	  
	  
	  var dataString = "ProductID="+id+"&proname="+name+"&price="+newtotal+"&discount="+discount+"&type="+type+"&RID="+RID+"&pty="+xx+"&Offertxt="+offrtxt+"&SRID="+SRID+"&acprice="+acprice+"&toping1="+toping1+"&toping2="+toping2+"&toping3="+toping3+"&addons="+addons+"&itemnote="+itemnote+"&maxtop1="+numoftop1+"&maxtop2="+numoftop2;
	  var myurl =mybaseUrl+"crmlogin/foodtocart";
	  $.ajax({
			type: "POST",
			url: myurl,
			data: dataString,
			success: function(data){
			  	  $("#cartproduct").html(data);
				  $('#hidemode'+id).trigger('click');
				}
			});
	   }
	}
function adddeleteQty(id,qty,RID,status){
		if(status=="del" && qty==0){
			return false;
			}
		else{
		  var dataString = "CartID="+id+"&RID="+RID+"&qty="+qty+"&Udstatus="+status;
		  var myurl =mybaseUrl+'crmlogin/updatefood';
		  $.ajax({
				type: "POST",
				url: myurl,
				data: dataString,
				success: function(data){
					 $("#cartproduct").html(data);
					}
			});	
		}
  }
 function submitorder(){
	var cartitem = $("#carttotal").val();
	var name= $("#name").val();
	var email= $("#email").val();
	var Phone= $("#Phone").val();
	var Address= $("#Address").val();
	var password= $("#password").val();
	var email=encodeURIComponent(email);
	var password=encodeURIComponent(password);
	var Address=encodeURIComponent(Address);
	var shippingname=$('input[name="shipping-method"]:checked').val();
	
	if(cartitem==0 || cartitem==""){
		alert("Please Add Some Product!!!");
		return false;
		}
	if(name==""){
		alert("Please Add Customer Name");
		return false;
		}
	if(Phone==""){
		alert("Please Add Customer Phone");
		return false;
		}
	if(Address==""){
		alert("Please Add Customer/shipping Address");
		return false;
		}
	if(password==""){
		alert("Please Add Customer Password");
		return false;
		}
	if($('input[name="shipping-method"]:checked').length === 0) {
		alert("Please select Shipping Method!!");
		return false;
	}
	
		document.frm_validate.submit();
	 }
</script>