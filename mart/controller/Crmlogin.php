<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crmlogin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
		//$this->output->enable_profiler(TRUE);
		 
		
    }
	public function index()
	{
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
       if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
             $this->load->view('crmlogin/login');
        }
		else{
			//print_r($sessiondata);
			redirect('dashboard');
			}
		
	}
	public function CrmLoginCheck()
    {
        $username = $this->input->post('admin_email_address');
        $password = md5($this->input->post('admin_password'));
		//$this->load->model('Foodmart_model');
        $result=$this->Foodmart_model->crmLoginCheckInfo($username,$password);
		//print_r($result);
        $sdata=array();
        if($result <> 0)
        {
		   $myid = $this->session->userdata('CrmUserID');
		    $sql="select * from tblstaffinfo where UserID='".$myid."'";
			$query_result=  $this->db->query($sql);
			$myinfo=$query_result->row();
			//print_r($myinfo);
			if(!empty($myinfo)){
			$myinfo->workingtime;
			$workingtime = $myinfo->workingtime;
			$logtime=explode('-',$workingtime);
			$logiintime = $logtime[0];
			$logouttime = $logtime[1];
			$weekend = $myinfo->weekend;
			$newTime = date("h:i A",strtotime($logiintime." -30 minutes"));
			$newlogoutTime = date("h:i A",strtotime($logouttime." +15 minutes"));
			$actualtime=date('h:i A');
			$sortactualtime = strtotime($actualtime);
			$currentday=date('l');
			$sortlogin = strtotime($newTime);
			$sortlogout = strtotime($newlogoutTime);
						if(($sortactualtime > $sortlogin) && ($sortactualtime<$sortlogout) &&($currentday!=$weekend)){
							$udata['Isonline']='1';
							$this->db->where('UserID',$myid);
							$this->db->update('tbluser',$udata);
							
							$emdata['UserID']=$myid;
							$emdata['logintime']=$actualtime;
							$emdata['logouttime']="";
							$emdata['EmployeelogUUID']=GUID();
							$emdata['UserIDInserted']=$myid;
							$emdata['UserIDUpdated']=$myid;
							$emdata['UserIDLocked']=$myid;
							$emdata['DateInserted']=date('Y-m-d H:i:s');
							$emdata['DateUpdated']=date('Y-m-d H:i:s');
							$emdata['DateLocked']=date('Y-m-d H:i:s');
							$this->db->insert('tblemployeelog',$emdata);
							redirect('dashboard');
						}
						else{
							$this->session->unset_userdata('CrmUserID');
							$this->session->unset_userdata('CrmUsersCategory');
							$this->session->unset_userdata('CrmUserName');
							$this->session->unset_userdata('CrmUserEmail');
							$this->session->sess_destroy();
							$sdata['exceptional']='Your Login Time is expired???';
		    				$this->session->set_userdata($sdata);
							}
			}
			else{
				 $sdata['exceptional']='Your Profile is not ready!!! Inform To Admin To Manage Staff Information!!!';
				$this->session->set_userdata($sdata);
				redirect('Crmlogin',$sdata);
				}
        }
        else
        {
            $sdata['exceptional']='Your Password Or Username Are Invalid ???';
		    $this->session->set_userdata($sdata);
           redirect('Crmlogin',$sdata);
        }
    }
	public function logoff()
    {
		$myid = $this->session->userdata('CrmUserID');
		$udata['Isonline']='0';
		$this->db->where('UserID',$myid);
		$this->db->update('tbluser',$udata);
		$sql="select * from tblstaffinfo where UserID='".$myid."'";
					$query_result=  $this->db->query($sql);
					$myinfo=$query_result->row();
					if(!empty($myinfo)){
					$myinfo->workingtime;
					$workingtime = $myinfo->workingtime;
					$logtime=explode('-',$workingtime);
					$logiintime = $logtime[0];
					$logouttime = $logtime[1];
					$weekend = $myinfo->weekend;
					$newTime = date("h:i A",strtotime($logiintime." -30 minutes"));
					$newlogoutTime = date("h:i A",strtotime($logouttime." +15 minutes"));
					$actualtime=date('h:i A');
					$sortactualtime = strtotime($actualtime);
					$currentday=date('l');
					$sortlogin = strtotime($newTime);
					$sortlogout = strtotime($newlogoutTime);
						if(($sortactualtime > $sortlogin) && ($sortactualtime<$sortlogout) &&($currentday!=$weekend)){
							$emdata['UserID']=$myid;
							$emdata['logintime']="";
							$emdata['logouttime']=$actualtime;
							$emdata['EmployeelogUUID']=GUID();
							$emdata['UserIDInserted']=$myid;
							$emdata['UserIDUpdated']=$myid;
							$emdata['UserIDLocked']=$myid;
							$emdata['DateInserted']=date('Y-m-d H:i:s');
							$emdata['DateUpdated']=date('Y-m-d H:i:s');
							$emdata['DateLocked']=date('Y-m-d H:i:s');
							$this->db->insert('tblemployeelog',$emdata);
						}
					}
		$this->session->unset_userdata('CrmUserID');
		$this->session->unset_userdata('CrmUsersCategory');
		$this->session->unset_userdata('CrmUserName');
		$this->session->unset_userdata('CrmUserEmail');
		$this->session->unset_userdata('CrmBranch');
		redirect('Crmlogin');
    }
	public function crmdashboard()
    {
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{   
		$data=array();
        $data['title']='Dashboard';
        $data['content']=$this->load->view('crmlogin/dashboard',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	
	public function Incommingorder()
    {
       $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
       if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
             $this->load->view('crmlogin/login');
        }
		else{
	    $data=array();
        $data['title']='View Incomming Order';
        $data['orderinfo']=  $this->Foodmart_model->read_allincommingorder();
        $data['content']=$this->load->view('crmlogin/incomming',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	public function editdelivary($id)
    {
       $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{   
	    $data=array();
        $data['title']='View Order';
		$ins['OrderShown']  = 1;
		$ins['ShownBy']  = $crm_id;
		$this->Foodmart_model->update_infomulti('tblorder', $ins, 'OrderID', $id);
		$data['orderinfo']=  $this->Foodmart_model->read_singleorder($id);
		$data['crm_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->ShownBy));
		$data['customer_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->UserID));
		$data['restaurant_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->RestaurantID));
		$data['rider_info']=  $this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $data['orderinfo']->riderid));
		$data['allcategory'] =  $this->Foodmart_model->read_all('*', 'tblproducts', array('UserID' =>$data['orderinfo']->RestaurantID));
		$data['iteminfo'] =  $this->Foodmart_model->read_all('*', 'tblcart', array('OrderID' => $id));
		$data['Customerorder'] =  $this->Foodmart_model->read_all('*', 'tblorder', array('UserID' =>$data['customer_info']->UserID));
		$data['totalorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, '', '','UserID',$data['customer_info']->UserID);
		$data['totaldeliverorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, 'OrderStatus', 'Delivered','UserID', $data['customer_info']->UserID);
		$data['totalcancelorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, 'OrderStatus', 'Cancelled','UserID', $data['customer_info']->UserID);
		$data['totalorderthisrest']=  $this->Foodmart_model->Ordercountrest('tblorder', $data['orderinfo']->UserID, $data['orderinfo']->RestaurantID);
		$data['permanentrider']=  $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderareaName' => $data['restaurant_info']->ResAarea,'Rider_type'=>'Permanent','RiderlistIsActive'=>1));
		$data['flexiablerider']=  $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderareaName' => $data['restaurant_info']->ResAarea,'Rider_type'=>'Flexible','RiderlistIsActive'=>1));
		$data['allrider']=  $this->Foodmart_model->read_allgroup('*', 'tblriderlist', array('branchname' => $mylocation),'RiderlistID','','branchname');
		/*
		if($data['orderinfo']->shippingmethod==1){'tbl_orders', $updateord, 'orderid', $orderid
			if(!empty($data['orderinfo']->delivarymethodtype)){
        $data['shipping_method']=$this->Super_admin_model->read('*', 'tbl_shipping', array('shippingid' => $data['orderinfo']->delivarymethodtype));
		}
		else{
			 $data['shipping_method']=(object)array('shippingtitle'=>'delivary','shippingcost'=>'0.00');
			}
		}*/
        $data['content']=$this->load->view('crmlogin/orderedit',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
    }
	}
	
	public function orderdetails($id)
    {
       $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{   
	    $data=array();
        $data['title']='View Order';
		$ins['OrderShown']  = 1;
		$ins['ShownBy']  = $crm_id;
		$this->Foodmart_model->update_infomulti('tblorder', $ins, 'OrderID', $id);
		$data['orderinfo']=  $this->Foodmart_model->read_singleorder($id);
		$data['crm_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->ShownBy));
		$data['customer_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->UserID));
		$data['restaurant_info']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $data['orderinfo']->RestaurantID));
		$data['rider_info']=  $this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $data['orderinfo']->riderid));
		$data['allcategory'] =  $this->Foodmart_model->read_all('*', 'tblproducts', array('UserID' =>$data['orderinfo']->RestaurantID));
		$data['iteminfo'] =  $this->Foodmart_model->read_all('*', 'tblcart', array('OrderID' => $id));
		$data['Customerorder'] =  $this->Foodmart_model->read_all('*', 'tblorder', array('UserID' =>$data['customer_info']->UserID));
		$data['totalorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, '', '','UserID',$data['customer_info']->UserID);
		$data['totaldeliverorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, 'OrderStatus', 'Delivered','UserID', $data['customer_info']->UserID);
		$data['totalcancelorder']=  $this->Foodmart_model->Ordercount('tblorder', $id, 'OrderStatus', 'Cancelled','UserID', $data['customer_info']->UserID);
		$data['totalorderthisrest']=  $this->Foodmart_model->Ordercountrest('tblorder', $data['orderinfo']->UserID, $data['orderinfo']->RestaurantID);
		$data['permanentrider']=  $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderareaName' => $data['restaurant_info']->ResAarea,'Rider_type'=>'Permanent','RiderlistIsActive'=>1));
		$data['flexiablerider']=  $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderareaName' => $data['restaurant_info']->ResAarea,'Rider_type'=>'Flexible','RiderlistIsActive'=>1));
		$data['allrider']=  $this->Foodmart_model->read_allgroup('*', 'tblriderlist', array('branchname' => $mylocation),'RiderlistID','','branchname',$mylocation);
		/*
		if($data['orderinfo']->shippingmethod==1){'tbl_orders', $updateord, 'orderid', $orderid
			if(!empty($data['orderinfo']->delivarymethodtype)){
        $data['shipping_method']=$this->Super_admin_model->read('*', 'tbl_shipping', array('shippingid' => $data['orderinfo']->delivarymethodtype));
		}
		else{
			 $data['shipping_method']=(object)array('shippingtitle'=>'delivary','shippingcost'=>'0.00');
			}
		}*/
        $data['content']=$this->load->view('crmlogin/orderdetails',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
    }
	}
	
	function getproduct(){
		 $restid = $this->input->post('restid');
         $categoryid = $this->input->post('catename');
		 $allproduct =  $this->Foodmart_model->read_all('*', 'tblproducts', array('CategoryID' =>$categoryid,'UserID'=>$restid));
		 $productlist="";
		 foreach($allproduct as $product){
			 $productlist.='<option value="'.$product->ProductsID.'" data-name="'.$product->ProductName.'">'.$product->ProductName.'</option>';
			 }
		echo '<option value="" selected="selected">Choose Product</option>';
		echo $productlist;
		}
	function getmenuinfo(){
	 $restid = $this->input->post('restid');
	 $productid = $this->input->post('productid');
	 $orderid = $this->input->post('orderid');
	 $allproduct =  $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' =>$productid,'UserID'=>$restid));
	 $Restaurant=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restid));
	$prices=explode(",",rtrim($allproduct->Price,","));
								$discount=explode(",",rtrim($allproduct->Discount,","));
								$type=explode(",",rtrim($allproduct->Type,","));
								$type=explode(",",rtrim($allproduct->Type,","));
								$maxtop1=explode(",",rtrim($allproduct->numoftoping,","));
								$maxtop2=explode(",",rtrim($allproduct->numoftoping2,","));

								if($allproduct->offerNote==""){
								$note=0;
								}
								else{
								$note=explode(",",rtrim($allproduct->offerNote,","));
								}
								$ProductCount=sizeof($prices);
							    
								$Discount=count($discount);
								$Type=count($type);
								$Note =count($note);
								$maxtopt1=count($maxtop1);
								$maxtopt2=count($maxtop2);
								$xx=0;?>
                                <div class="uk-modal-dialog" id="checkrd">
                                    <button type="button" class="uk-modal-close uk-close"></button>
                                    <div class="md-input-wrapper md-input-filled"><label> <?php echo $allproduct->ProductName;?></label></div>
                                    <div class="md-input-wrapper md-input-filled">
                                    <label>Quantity</label>
                                    <input class="md-input label-fixed" value="1" name="quantity" id="quantity" type="text">
                                    <span class="md-input-bar "></span></div>
                                    <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                            <h3 class="heading_a">Select Item</h3>
                            <div class="uk-grid" id="allwedoption">
								<?php for($i=0;$i<$ProductCount;$i++){
									 if($i>1){
									   if(array_key_exists($i, $discount)){
											$discountm=$discount[$i];
											}
											else{
												$discountm='';
												}
								    }
								    else{
								       $discountm= $discount[0];
								    }
								 
									$type[$i]."_".$i;
									if($type[$i]!=""){
										$size=$type[$i];
										if($note=='0'){
										$offertxt='';
										}
										else{
										$offertxt=$note[$i];
										}
										if(in_array(null, $maxtop1)) {
										$firsttoping='';
										}
										else{
											if(array_key_exists($i, $maxtop1)){
											$firsttoping=$maxtop1[$i];
											}
											else{
												$firsttoping='';
												}
										}
										if (in_array(null, $maxtop2)) {
										$lasttoping='';
										}
										else{
											if(array_key_exists($i, $maxtop2)){
											$lasttoping=$maxtop2[$i];
											}
											else{
												$lasttoping='';
												}
										}
									}else{
										$size='';
										$offertxt='';
										$lasttoping='';
										$firsttoping='';
									}
									if($type[$i]=='0')
										$size='';
										
									

									
									// Discount on Restaurant.
									if(($Restaurant->Discount)>1){
										$discountpercent=(int)$Restaurant->Discount;
										$price=(int)$prices[$i];
										if($allproduct->productvat>0){
											$vatprice=$price+$price*$allproduct->productvat/100;
											$vatwithprice=round($vatprice);
											}
										else{
											$vatprice=$price;
											$vatwithprice=round($vatprice);
											}
										// if restaurant Discount then show main price.
										//$mainPrice=round($price-$price*$discountpercent/100);
										$mainPrice=round($price);
										
										$gettypes=$type[$i];
										$getprice=$price;
										?>
                                 
<input name="size" id="size_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $type[$i];?>" />
<input name="notes" id="notes_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $note[$i];?>" />
<input name="discount" id="discount_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $discountm;?>" />
<input name="type" id="type_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $i;?>" />
<input name="maxtop1" id="maxtop1_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>" />
<input name="maxtop2" id="maxtop2_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>" />
<input name="actualprice" id="actualprice" type="hidden" value="<?php echo $price;?>" />
<input name="First_<?php echo $vatwithprice;?>" id="First_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>">
<input name="last_<?php echo $vatwithprice;?>" id="last_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>">
                                <div class="uk-width-medium-1-2">
                                    <p>
                                        <label for="radio_demo_ty" class="inline-label"><?php echo $type[$i];?></label>
                                    </p>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <span class="icheck-inline">
                                        <input type="radio" name="select-price" id="radio_demo_inline_<?php echo $i;?>" role="<?php echo $vatwithprice;?>" value="<?php echo $type[$i];?>" onchange="getprice('<?php echo $type[$i];?>','<?php echo $vatwithprice;?>')" />
                                        <label for="radio_demo_inline_<?php echo $i;?>" class="inline-label"><?php echo $vatwithprice;?></label>
                                    </span>
                                </div>
                           
                 
                                    
                                     
								<?php 
									}
									else if(!empty($discount[$i])){
										$discountpercent=(int)$discount[$i];
										$price=(int)$prices[$i];
										if($allproduct->productvat>0){
											$vatprice=$price*$allproduct->productvat/100;
											$vatprice=($vatprice)+($price-$price*$discountpercent/100);
											$vatwithprice=round($vatprice);
											}
										else{
											$vatprice=$price-$price*$discountpercent/100;
											$vatwithprice=round($vatprice);
											}
										$mainPrice=round($price-$price*$discountpercent/100);
										
										$gettypes=$type[$i];
										$getprice=$price;
										?>
<input name="size" id="size_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $type[$i];?>" />
<input name="notes" id="notes_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $note[$i];?>" />
<input name="discount" id="discount_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $discountm;?>" />
<input name="type" id="type_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $i;?>" />
<input name="maxtop1" id="maxtop1_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>" />
<input name="maxtop2" id="maxtop2_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>" />
<input name="actualprice" id="actualprice" type="hidden" value="<?php echo $price;?>" />
<input name="First_<?php echo $vatwithprice;?>" id="First_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>">
<input name="last_<?php echo $vatwithprice;?>" id="last_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>">    
                                <div class="uk-width-medium-1-2">
                                    <p>
                                        <label for="radio_demo_ty" class="inline-label"><?php echo $type[$i];?></label>
                                    </p>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <span class="icheck-inline">
                                        <input type="radio" name="select-price" id="radio_demo_inline_<?php echo $i;?>" role="<?php echo $vatwithprice;?>" value="<?php echo $type[$i];?>" onchange="getprice('<?php echo $type[$i];?>','<?php echo $vatwithprice;?>')"/>
                                        <label for="radio_demo_inline_<?php echo $i;?>" class="inline-label"><?php echo $vatwithprice;?></label>
                                    </span>
                                </div>
                           
                                    
                                    
                                   <?php 
									}
									else{
										$price=(int)$prices[$i];
										
										if($allproduct->productvat>0){
											$vatprice=$price+$price*$allproduct->productvat/100;
											$vatwithprice=round($vatprice);
											}
										else{
											$vatprice=$price;
											$vatwithprice=round($vatprice);
											}
										
											$gettypes=$type[$i];
										$getprice=$price;
										?>
<input name="size" id="size_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $type[$i];?>" />
<input name="notes" id="notes_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $note[$i];?>" />
<input name="discount" id="discount_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $discountm;?>" />
<input name="type" id="type_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $i;?>" />
<input name="maxtop1" id="maxtop1_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>" />
<input name="maxtop2" id="maxtop2_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>" />
<input name="actualprice" id="actualprice" type="hidden" value="<?php echo $price;?>" />
<input name="First_<?php echo $vatwithprice;?>" id="First_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $firsttoping;?>">
<input name="last_<?php echo $vatwithprice;?>" id="last_<?php echo $vatwithprice;?>" type="hidden" value="<?php echo $lasttoping;?>">      
                                <div class="uk-width-medium-1-2">
                                    <p>
                                        <label for="radio_demo_ty" class="inline-label"><?php echo $type[$i];?></label>
                                    </p>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <span class="icheck-inline">
                                        <input type="radio" name="select-price" id="radio_demo_inline_<?php echo $i;?>" role="<?php echo $vatwithprice;?>" value="<?php echo $type[$i];?>" onchange="getprice('<?php echo $type[$i];?>','<?php echo $vatwithprice;?>')"/>
                                        <label for="radio_demo_inline_<?php echo $i;?>" class="inline-label"><?php echo $vatwithprice;?></label>
                                    </span>
                                </div>
                            
                                     
										<?php 
									}
									
								 }?>
                                    	</div>
                					</div>
                        </div>
								<?php  $toping1=explode(",",rtrim($allproduct->numoftoping,","));
								$totalitemtop= count(array_filter($toping1));
								$numoftoping1=sizeof($toping1);
								$toping2=explode(",",rtrim($allproduct->numoftoping2,","));
								$numoftoping2=sizeof($toping2);
								$totalitemtop2= count(array_filter($toping2));
								if($totalitemtop==""){
								echo "";
								}
								else{
								 ?>
                                 <div class="uk-grid" id="toping1" style="display:none;" data-uk-grid-margin>
                                 <h3 class="extra-item-popup-title">Select Any - <span id="maxtoping1"></span> items selected</h3>
                                 <p>&nbsp;</p>
                                 <div class="uk-width-medium-1-1">
                                 <?php 
			  $toping1item=explode(",",$allproduct->topingitem);
			  $top1=0;
			  foreach($toping1item as $ftopingname){
				  $top1++;
			  ?>
                            <span class="icheck-inline">
                                <input type="checkbox" onchange="getfirsttopping()" name="topingitem1[]" id="select-toping1-item_<?php echo $top1;?>" value="<?php echo $ftopingname;?>" data-md-icheck />
                                <label for="checkbox_demo_inline_<?php echo $top1;?>" class="inline-label"><?php echo $ftopingname;?></label>
                            </span>
                                     <?php } ?>
                                </div>
                                </div>
<?php } 
if($totalitemtop2==""){
echo "";
}
else{
?>
<!-- End of extra-item-popup-options -->
                <div class="uk-grid" id="toping2" style="display:none;" data-uk-grid-margin>
                                 <h3 class="extra-item-popup-title">Select Any - <span id="maxtoping2"></span> items selected</h3>
                                 <p>&nbsp;</p>
                                 <div class="uk-width-medium-1-1">
              <?php 
			  $toping2item=explode(",",$allproduct->topingitem2);
			  $top2=0;
			  foreach($toping2item as $stopingname){
				  $top2++;
			  ?>
              <span class="icheck-inline">
                                <input type="checkbox" onchange="getsecondtopping()" name="topingitem2[]" id="select-toping2-item_<?php echo $top2;?>" value="<?php echo $stopingname;?>" data-md-icheck />
                                <label for="checkbox_demo_inline_<?php echo $top2;?>" class="inline-label"><?php echo $stopingname;?></label>
                            </span>
				<?php } ?>
            </div>
</div>
<?php } ?>
<div class="uk-grid" id="singleitem" style="display:none;" data-uk-grid-margin>
<?php 
$toping3item=explode(",",$allproduct->topingitem3);
if(($toping3item[0]==" ")||(empty($toping3item[0]))){
	echo ' <span class="icheck-inline">
                                <input type="radio" name="select-addon-prior" id="extra-item-prior_0>" value="0" checked="checked" style="display:none;" data-md-icheck />
                            </span>
	';
		$checkoption=1;
	}
else{
	$checkoption="";

?>
<h3 class="heading_a">Choose 1 items</h3>
   <div class="uk-width-medium-1-1">
  <?php 
			  $top3=0;
			  foreach($toping3item as $chosetopingname){
				  $top3++;
			  ?>
 
     <span class="icheck-inline">
            <input type="radio" name="select-addon-prior" id="extra-item-prior_<?php echo $top3;?>" value="<?php echo $chosetopingname;?>" />
            <label for="checkbox_demo_inline_<?php echo $top3;?>" class="inline-label"><?php echo $chosetopingname;?></label>
     </span>
  
<?php }?></div><?php  } ?>
</div>
<div class="uk-grid" id="addons" style="display:none;" data-uk-grid-margin>
<?php 
 $addonsitem=explode(",",rtrim($allproduct->addonitems,","));
 $addonsprice=explode(",",rtrim($allproduct->addonitemsprice,","));
 //print_r($addonsitem);
  if(($addonsitem[0]=="")||($addonsitem[0]=="0")){
	 echo '<input type="checkbox" id="select-addon-item-1-0"  name="extraaddons[]" value="" style="display:none;">';

	 }
 else{
?>
<div class="col-sm-12">
                <div class="col-sm-12">
                <h3 class="extra-item-popup-title">Select Addon  <span class="extra-item-popup-addon-total-price text-right">+ &#x9f3;<span id="totalcost">00</span></span>
</h3>
              </div>
             </div>
             <div class="uk-width-medium-1-1">
              <?php  $numofaddonsitem=sizeof($addonsitem);
			  $add2=0;
			  	for($add2=0;$add2<$numofaddonsitem;$add2++){
			  ?>
    <span class="icheck-inline">
            <input type="checkbox" onchange="addadons()" name="extraaddons[]" id="elect-addon-item-1-<?php echo $add2;?>" role="<?php echo $addonsprice[$add2];?>" value="<?php echo $addonsitem[$add2];?>" data-md-icheck />
            <label for="select-addon-item-1-<?php echo $add2;?>" class="inline-label"><?php echo $addonsitem[$add2];?></label>
     <span class="extra-item-popup-addon-price text-right">+ &#x9f3;<?php echo $addonsprice[$add2];?></span>
     </span> 
              
				<?php } ?>
				</div>
				<?php  } ?>
            </div>
<div class="md-input-wrapper"><button type="button" class="md-btn md-btn-primary disabled" id="disallowed" onclick="addtocartitem('<?php echo $restid;?>','<?php echo $orderid;?>','<?php echo $productid;?>');">Add Food Item</button></div>
                                 </div>
	<?php }
	function addnewfood(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{ 
		$productid = $this->input->post('ProductID');
		$qty = $this->input->post('itemty');	
		$price = $this->input->post('acprice');
		$orderid =$this->input->post('orderid');
		$restid =$this->input->post('RID');
		$sessid = $this->session->userdata('CrmUserID');;
		$type =$this->input->post('type'); 
		$topping1 =$this->input->post('toping1');
		$topping2 =$this->input->post('toping2');
		$topping3 =$this->input->post('toping3');
		$addons =$this->input->post('addons');
		$pricewithaddons = $this->input->post('price');
		$orgprice=$this->input->post('orgprice');
		$restcommisiontotal=0;
		$withoutcom=0;
		$pcommisionprice=0;


$CatrProducts=$this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $productid));
$Userrestau=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $CatrProducts->UserID));
$ordrow=  $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));


$prodiscount=explode(",",rtrim($CatrProducts->Discount,","));
$propricemain=explode(",",rtrim($CatrProducts->Price,","));
$protype=explode(",",rtrim($CatrProducts->Type,","));
$key=array_search($type,$protype);
$Discount=(int)$prodiscount[$key];
$actualpyice=(int)$propricemain[$key];

$vatpercent=$CatrProducts->productvat;
$foodtotal= $price*$qty;
if(($Discount>0) ||($Discount!="")){
	$foodAmount=$foodtotal*$Discount/100;
	$indis=$Discount;
	}
else{
	$foodAmount=0;
	$indis="0";
	}
$forvatprice = $foodtotal-$foodAmount;
$calvat=$forvatprice*$Userrestau->Vat/100;
$calsrv=$forvatprice*$Userrestau->ServiceCharge/100;
					$identity=$CatrProducts->Ccommision;
					if($identity==""){
					$gtotalr=($price*$qty)-$foodAmount;
					$restcommition=($Userrestau->Commission*$gtotalr)/100;
					$nitrest=$gtotalr-$restcommition;
					$restcommisiontotal=$nitrest;
					}
				if($identity=="0"){
					$zerocom=($price*$qty)-$foodAmount;
					$withoutcom=$zerocom;
					}
				if($identity>0){
					$ptotal=($price*$qty)-$foodAmount;
					$productcommition=($CatrProducts->Ccommision*$ptotal)/100;
					$nitpcom=$ptotal-$productcommition;
					$pcommisionprice=$nitpcom;
					}

$restbill= $restcommisiontotal+$pcommisionprice+$withoutcom;
$finalbill= $restbill+$calvat+$calsrv;
	

$ordqty = $ordrow->Quantity+$qty;
$addamount =$forvatprice;
$ordprice = $ordrow->Amount+$addamount;
$orddelifee = $ordrow->DeliveryFee;
$orddis = $ordrow->DiscountPercentage;
$restaurantprice=$ordrow->Restaurantamount+$finalbill;

if($orddis>0){
$DiscountAmount=($ordprice*$orddis)/100;
}
else{
	$DiscountAmount=0;
	}

$ordvat = $ordrow->Vat;
if($ordvat>0){
$vatAmount=($ordvat*$ordprice)/100;
}
else{
	$vatAmount=0;
	}

$ordscharge = $ordrow->ServiceCharge;
if($ordscharge>0){
$SchargeAmount=($ordscharge*$ordprice)/100;
}
else{
	$SchargeAmount=0;
	}
$grandtotal= ($ordprice+$vatAmount+$SchargeAmount+$orddelifee)-$DiscountAmount;
$cartquery=$this->Foodmart_model->read_allcart('*', 'tblcart', array('ProductID' => $productid,'Company'=>$restid,'OrderID'=>$orderid,'Type'=>$type));
	if(!empty($cartquery)){
foreach($cartquery as $cartrow){
			$finalqty= $cartrow->Quantity+$qty;
			   $cartudata['Price']=$pricewithaddons;
			   $cartudata['Quantity']=$finalqty;
			   $this->Foodmart_model->updatecarttable($pricewithaddons,$finalqty,$productid,$restid,$orderid,$type);
		}
		 	   $udata['Quantity']=$ordqty;
			   $udata['Amount']=$ordprice;
			   $udata['Restaurantamount']=$restaurantprice;
			   $udata['Discount']=$DiscountAmount;
			   $udata['Vatamount']=$vatAmount;
			   $udata['ServiceChargeAmount']=$SchargeAmount;
			   $udata['GrandTotal']=$grandtotal;
			   $this->db->where('OrderID',$orderid);
			   $this->db->update('tblorder',$udata);
	}
	else{
		$PayAblePrice=$price-$foodAmount;
		if($vatpercent>0){
			$vatprice=$PayAblePrice+$PayAblePrice*$vatpercent/100;
			$vatwithprice=round($vatprice);
			}
		else{
			$vatprice=$price;
			$vatwithprice=round($vatprice);
			}
			
			
			$insdata['ProductID']=$productid;
			$insdata['Price']=$pricewithaddons;
			$insdata['Type']=$type;
			$insdata['Company']=$restid;
			$insdata['Quantity']=$qty;
			$insdata['toppingname1']=$topping1;
			$insdata['toppingname2']=$topping2;
			$insdata['toppingname3']=$topping3;
			$insdata['adonsnames']=$addons;
			$insdata['OrderID']=$orderid;
			$insdata['Discount']=$indis;
			$insdata['UserIDInserted']=$sessid;
			$insdata['UserIDUpdated']=$sessid;
			$insdata['UserIDLocked']=$sessid;
			$insdata['DateInserted']=date('Y-m-d H:i:s');
			$insdata['DateUpdated']=date('Y-m-d H:i:s');
			$insdata['DateLocked']=date('Y-m-d H:i:s');
			$this->db->insert('tblcart',$insdata);

		
			   $udata['Quantity']=$ordqty;
			   $udata['Amount']=$ordprice;
			   $udata['Restaurantamount']=$restaurantprice;
			   $udata['Discount']=$DiscountAmount;
			   $udata['Vatamount']=$vatAmount;
			   $udata['ServiceChargeAmount']=$SchargeAmount;
			   $udata['GrandTotal']=$grandtotal;
			   $this->db->where('OrderID',$orderid);
			   $this->db->update('tblorder',$udata);
		}
	}
		}
	function deleteitem(){
			$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{ 
			$productid = $this->input->post('productid');	
			$qty = $this->input->post('qty');
			$orderid = $this->input->post('orderid');
			$type = $this->input->post('type');
			$price = $this->input->post('price');
			$cartid = $this->input->post('cartid');
			
			$restcommisiontotal=0;
			$withoutcom=0;
			$pcommisionprice=0;
			
			$CatrProducts=$this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $productid));
			$Userrestau=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $CatrProducts->UserID));
			$ordrow=  $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
			$proprice=explode(",",rtrim($CatrProducts->Price,","));
			$prodiscount=explode(",",rtrim($CatrProducts->Discount,","));
			$protype=explode(",",rtrim($CatrProducts->Type,","));
			$key=array_search($type,$protype);
			$delPrice=(int)$proprice[$key];
			$Discount=(int)$prodiscount[$key];
			$foodtotal= $delPrice*$qty;
			if(($Discount>0) ||($Discount!="")){
				$foodAmount=$foodtotal*$Discount/100;
				$indis=$Discount;
				}
			else{
				$foodAmount=0;
				$indis="0,";
				}
			$forvatprice = $foodtotal-$foodAmount;
			$calvat=$forvatprice*$Userrestau->Vat/100;
			$calsrv=$forvatprice*$Userrestau->ServiceCharge/100;
			$identity=$CatrProducts->Ccommision;
			if($identity==""){
					$gtotalr=($delPrice*$qty)-$foodAmount;
					$restcommition=($Userrestau->Commission*$gtotalr)/100;
					$nitrest=$gtotalr-$restcommition;
					$restcommisiontotal=$nitrest;
					}
				if($identity=="0"){
					$zerocom=($delPrice*$qty)-$foodAmount;
					$withoutcom=$zerocom;
					}
				if($identity>0){
					$ptotal=($delPrice*$qty)-$foodAmount;
					$productcommition=($CatrProducts->Ccommision*$ptotal)/100;
					$nitpcom=$ptotal-$productcommition;
					$pcommisionprice=$nitpcom;
					}
				$restbill= $restcommisiontotal+$pcommisionprice+$withoutcom;
				$finalbill= $restbill+$calvat+$calsrv;
				$ordqty = $ordrow->Quantity-$qty;
				$addamount = $forvatprice;
				$ordprice = $ordrow->Amount-$addamount;
				$orddelifee = $ordrow->DeliveryFee;
				$restaurantprice=$ordrow->Restaurantamount-$finalbill;
				$orddis = $ordrow->DiscountPercentage;
				if($orddis>0){
				$DiscountAmount=($ordprice*$orddis)/100;
				}
				else{
					$DiscountAmount=0;
					}
				$ordvat = $ordrow->Vat;
				if($ordvat>0){
				$vatAmount=($ordvat*$ordprice)/100;
				}
				else{
					$vatAmount=0;
					}
				$ordscharge = $ordrow->ServiceCharge;
				if($ordscharge>0){
				$SchargeAmount=($ordscharge*$ordprice)/100;
				}
				else{
					$SchargeAmount=0;
					}
					
			  $grandtotal= ($ordprice+$vatAmount+$SchargeAmount+$orddelifee)-$DiscountAmount;
			   $udata['Quantity']=$ordqty;
			   $udata['Amount']=$ordprice;
			   $udata['Restaurantamount']=$restaurantprice;
			   $udata['Discount']=$DiscountAmount;
			   $udata['Vatamount']=$vatAmount;
			   $udata['ServiceChargeAmount']=$SchargeAmount;
			   $udata['GrandTotal']=$grandtotal;
			   $this->db->where('OrderID',$orderid);
			   $this->db->update('tblorder',$udata);
				
			  $this->Foodmart_model->deleteitem('tblcart','CartID',$cartid);
		}
		}
	public function submitdeliver(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{ 
		$delvartime=$this->input->post('delvartime');
		$orderstatus=$this->input->post('orderstatus');
		$orderid=$this->input->post('orderid');
		
		
		if($orderstatus=="Cancelled"){
		$reason =$this->input->post('cancelreason');
		$cusstatus="Cancel";
		$resstatus="Cancel";
		$riderstatus="";
		$updateord['OrderStatus']=$orderstatus;
		$updateord['RemarkNote']=$this->input->post('remarks');
		$updateord['DeliveryTime']=$delvartime;
		$updateord['cancelreason']=$reason;
		$updateord['restStatus']=$resstatus;
		$updateord['cuStatus']=$cusstatus;
		$updateord['riderstatus']=$riderstatus;
		$this->Foodmart_model->update_info('tblorder', $updateord, 'OrderID', $orderid);
		
		$ordercan=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
		$updatecup['codeisexpired']="0";
		$this->Foodmart_model->update_info('tblcoupon', $updatecup, 'CouponCode', $ordercan->promocode);
		$customerinfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $ordercan->UserID));
		SendSMS('88'.$customerinfo->PhoneMobile,
		$SMS ="Dear Sir/Madam, We extremely sorry for not proceed your order Due to Some {$reason}. We are waiting for your next order. - foodmar.");
		}
	else if($orderstatus=="Processing"){
		$reason = "";
		$cusstatus="Accepted";
		$resstatus="Update";
		$riderstatus="";
		$updateord['OrderStatus']=$orderstatus;
		$updateord['RemarkNote']=$this->input->post('remarks');
		$updateord['DeliveryTime']=$delvartime;
		$updateord['cancelreason']=$reason;
		$updateord['restStatus']=$resstatus;
		$updateord['cuStatus']=$cusstatus;
		$updateord['riderstatus']=$riderstatus;
		$this->Foodmart_model->update_info('tblorder', $updateord, 'OrderID', $orderid);
		
		$order=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
		$user=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $order->UserID));
		$RestaurantInfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $order->RestaurantID));
		if($order->Shipping=="1") { // if delivery
			// sent email on Processiong
			SendMail(
				$ToEmail=$user->UserEmail,
				$Subject="Your Order on process",
				$Body="
					
					
					Dear Sir/Madam, 
					<br><br>
	
					We are very much pleased to inform you ,we have received your order and your order on process.ÊThank you for choosing foodmart service. Our dedicated Rider team will serve your food within our estimated time  or if there are any other updates. 
					<br>	
					We desire you might receive your food within one hour. 				
					<br><br>
					Payment Type: Full Paid. Online payment/Cash on delivery. <br>
					".GetOrderTableForEmail($orderid)."
					<br><br>
					Support Team<br>
					<br>					
					Foodmart <br>
					www.foodmart.com.bd <br>
					Hotline:01793111333<br>
					Love food.Love foodmart.<br>
	
				",
				$FromName="Foodmart.com.bd",
				$FromEmail = "order@foodmart.com.bd",
				$ReplyToName="Foodmart.com.bd",
				$ReplyToEmail="order@foodmart.com.bd",
				$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			);
		}
		if($order->Shipping=="0") { // if Pickup option
			// sent email on Processiong
			SendMail(
				$ToEmail=$user["UserEmail"],
				$Subject="Your Order on process",
				$Body="
					
					
					Dear Sir/Madam, 
					<br><br>
	
					We are very much pleased to inform you ,we have received your order and your order on process.ÊThank you for choosing foodmart service. We desire your food will be ready within our estimated time.You will be able to collect your food as soon as your food is cooked. Normally it takes 30 minutes. 

					<br><br>
					
					Restaurant address : <br>
					Phone : ".$RestaurantInfo->PhoneNumber." <br>
					".$RestaurantInfo->ResAddress."					
					<br><br>
	
					Please feel free to <a href=\"https://www.foodmart.com.bd/admin.php?Theme=default&Base=Page&Script=Contactus\">contact</a> us if any queries.
					<br><br>
					Payment Type: Full Paid. Online payment/Cash on delivery. <br>
					".GetOrderTableForEmail($orderid)."
					
					
					
					<br><br>
					Support Team<br>
					<br>					
					Foodmart <br>
					www.foodmart.com.bd <br>
					Hotline:01793111333<br>
					Love food.Love foodmart.<br>
	
				",
				$FromName="Foodmart.com.bd",
				$FromEmail = "order@foodmart.com.bd",
				$ReplyToName="Foodmart.com.bd",
				$ReplyToEmail="order@foodmart.com.bd",
				$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			);
		}
		// Send SMS Customer
		SendSMS('88'.$user->PhoneMobile,
			$SMS ="Dear Sir/Madam, Your order ID: ".$orderid." on process.We are dedicated to serve your food on estimated time. Thanks your cooperation Support Team \n01793111333.");
		}
	else if($orderstatus=="Delivered"){
		$reason = "";
		$cusstatus="Accepted";
		$resstatus="Release";
		$riderstatus="Accept";
		$updateord['OrderStatus']=$orderstatus;
		$updateord['RemarkNote']=$this->input->post('remarks');
		$updateord['DeliveryTime']=$delvartime;
		$updateord['cancelreason']=$reason;
		$updateord['restStatus']=$resstatus;
		$updateord['cuStatus']=$cusstatus;
		$updateord['riderstatus']=$riderstatus;
		$this->Foodmart_model->update_info('tblorder', $updateord, 'OrderID', $orderid);
		
		$order=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
		$user=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $order->UserID));
		$updateus['ISverified']=1;
		$this->Foodmart_model->update_info('tbluser', $updateus, 'UserID', $user->UserID);
		$ordercan=$this->Foodmart_model->read_all('*', 'tblorder', array('UserID' => $order->UserID));
		$numrows=count($ordercan);
		if($numrows<=1){
		SendSMS('88'.$user->PhoneMobile,
			$SMS ="Congratulations ! Your Foodmart account is now verified ! Thank you for being our valuable customer.");	
		}
		
		if($order->PaymentMethod==1){
			$profit=$order->GrandTotal-$order->Restaurantamount;
			}
		else if($order->PaymentMethod==2){
			$profit=0;
			}
		else if($order->PaymentMethod==3){
			$profit=0;
			}
		//add profit to rider statement
		$mValue_UUID=GUID();
		$riderid = $order->riderid;
		$accounttype = "Credit";
		$refference = $orderid;
		$amount = $profit;
		$userid = $crm_id;
		$sessionid = mt_rand(100, 1000) . $crm_id;
		
		if($order->PaymentMethod==1){
			$insdata['RiderAccountsUUID']=GUID();
			$insdata['RiderID']=$order->riderid;
			$insdata['refference']=$orderid;
			$insdata['actype']="Credit";
			$insdata['amount']=$profit;
			$insdata['RiderAccountsIsActive']=1;
			$insdata['isapproved']=1;
			$insdata['UserIDInserted']=$crm_id;
			$insdata['UserIDUpdated']=$crm_id;
			$insdata['UserIDLocked']=$crm_id;
			$insdata['DateInserted']=date('Y-m-d H:i:s');
			$insdata['DateUpdated']=date('Y-m-d H:i:s');
			$insdata['DateLocked']=date('Y-m-d H:i:s');
			$insdata['Session']=$sessionid;
			$this->db->insert('tblriderAccounts',$insdata);	
		} 
		else if($order->PaymentMethod==2){
		    $insdata['RiderAccountsUUID']=GUID();
			$insdata['RiderID']=$order->riderid;
			$insdata['refference']=$orderid;
			$insdata['actype']="Debit";
			$insdata['amount']=$order-Restaurantamount;
			$insdata['RiderAccountsIsActive']=1;
			$insdata['isapproved']=1;
			$insdata['UserIDInserted']=$crm_id;
			$insdata['UserIDUpdated']=$crm_id;
			$insdata['UserIDLocked']=$crm_id;
			$insdata['DateInserted']=date('Y-m-d H:i:s');
			$insdata['DateUpdated']=date('Y-m-d H:i:s');
			$insdata['DateLocked']=date('Y-m-d H:i:s');
			$insdata['Session']=$sessionid;
			$this->db->insert('tblriderAccounts',$insdata);	
		 }
		else if($order->PaymentMethod==3){
		    $insdata['RiderAccountsUUID']=GUID();
			$insdata['RiderID']=$order->riderid;
			$insdata['refference']=$orderid;
			$insdata['actype']="Debit";
			$insdata['amount']=$order-Restaurantamount;
			$insdata['RiderAccountsIsActive']=1;
			$insdata['isapproved']=1;
			$insdata['UserIDInserted']=$crm_id;
			$insdata['UserIDUpdated']=$crm_id;
			$insdata['UserIDLocked']=$crm_id;
			$insdata['DateInserted']=date('Y-m-d H:i:s');
			$insdata['DateUpdated']=date('Y-m-d H:i:s');
			$insdata['DateLocked']=date('Y-m-d H:i:s');
			$insdata['Session']=$sessionid;
			$this->db->insert('tblriderAccounts',$insdata);	
		 }
		 $riderinfo=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $order->riderid));
		  if($riderinfo->Rider_type=="Permanent"){
					$riderincome=0;
					}
		  else if($riderinfo->Rider_type=="Flexible"){
					$riderincome=$riderinfo->rider_salery;
					}	
		    $insrider['RiderincomeUUID']=GUID();
			$insrider['RiderID']=$order->riderid;
			$insrider['Orderid']=$orderid;
			$insrider['refference']=$orderid;
			$insrider['actype']="Debit";
			$insrider['amount']=$riderincome;
			$insrider['RiderincomeIsActive']=1;
			$insrider['isapproved']=1;
			$insrider['UserIDInserted']=$crm_id;
			$insrider['UserIDUpdated']=$crm_id;
			$insrider['UserIDLocked']=$crm_id;
			$insrider['DateInserted']=date('Y-m-d H:i:s');
			$insrider['DateUpdated']=date('Y-m-d H:i:s');
			$insrider['DateLocked']=date('Y-m-d H:i:s');
			$insrider['Session']=$sessionid;
			$this->db->insert('tblriderincomestatement',$insrider);
			$htmlContent=GetOrderTableForEmail($orderid);
			if($order->Shipping=="1") { // if delivery
			// sent email on Processiong
			SendMail(
				$ToEmail=$user->UserEmail,
				$Subject="Your order delivered successfully",
				$Body=$htmlContent,
				$FromName="Foodmart.com.bd",
				$FromEmail = "order@foodmart.com.bd",
				$ReplyToName="Foodmart.com.bd",
				$ReplyToEmail="order@foodmart.com.bd",
				$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			);
		}
		if($order->Shipping=="0") { // if Pickup option
			
			// sent email on Processiong
			SendMail(
				$ToEmail=$user->UserEmail,
				$Subject="Your order received successfully",
				$Body=$htmlContent,
				$FromName="Foodmart.com.bd",
				$FromEmail = "order@foodmart.com.bd",
				$ReplyToName="Foodmart.com.bd",
				$ReplyToEmail="order@foodmart.com.bd",
				$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			);
		}
		// Send SMS
		SendSMS('88'.$user->PhoneMobile,
			$SMS ="Dear Sir/Madam, Thank you for your nice cooperation. We are waiting for next order. Love food.Love foodmart. Offer update: www.foodmart.com.bd");
		}
	else{
		$reason = "";
		$cusstatus="";
		$resstatus="";
		$riderstatus="";
		$updateord['OrderStatus']=$orderstatus;
		$updateord['RemarkNote']=$this->input->post('remarks');
		$updateord['DeliveryTime']=$delvartime;
		$updateord['cancelreason']=$reason;
		$updateord['restStatus']=$resstatus;
		$updateord['cuStatus']=$cusstatus;
		$updateord['riderstatus']=$riderstatus;
		$this->Foodmart_model->update_info('tblorder', $updateord, 'OrderID', $orderid);
		}
		
		 }
		}
	public function pendingorder()
    {
         $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{  
		$data=array();
        $data['title']='View Pending Order';
        $data['orderinfo']=  $this->Foodmart_model->read_allpendingorder();
        $data['content']=$this->load->view('crmlogin/pending',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	public function mypendingorder()
    {
		 $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{ 
        $data=array();
        $data['title']='View Pending Order';
        $data['orderinfo']=  $this->Foodmart_model->read_allpendingorderbyid($crm_id);
        $data['content']=$this->load->view('crmlogin/pendingmy',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
	}
    }
	public function deliveredorder()
    {
        $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='View Complete Order';
        $data['orderinfo']=  $this->Foodmart_model->read_alldeliveredorder($crm_id);
        $data['content']=$this->load->view('crmlogin/complete',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	public function cancelorder()
    {
        $crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='View Cancel Order';
        $data['orderinfo']=  $this->Foodmart_model->read_allcancelorder($crm_id);
        $data['content']=$this->load->view('crmlogin/cancel',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	public function getriderlist(){
		 $areaid=$this->input->post('area');
		 $allrider=  $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderareaID' =>$areaid));
		  $arealist="";
		 foreach($allrider as $rider){
			 echo $arealist.='<option value="'.$rider->RiderlistID.'">'.$rider->RiderName.'</option>';
			 }
		//echo '<option value="" selected="selected">Choose Rider</option>';
		//echo $arealist;
		}
	public function getphone($phone){
		$riderphone=  $this->Foodmart_model->read_phone($phone);
		echo json_encode($riderphone);
		}
	public function ridersms(){
		$riderphone=$this->input->post('RiderPhone3');
		$riderid=$this->input->post('rider3');
		$Orderid=$this->input->post('Orderid3');
		if(!empty($riderphone)){
			if(!empty($riderid)){
		       $udata['riderstatus']="Sent";
			   $udata['riderid']=$riderid;
			   $this->db->where('OrderID',$Orderid);
			   $this->db->update('tblorder',$udata);
			}
				
		// Send Notification
		$orderInfo=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $Orderid));
		$myuserinfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderInfo->UserID));
		$myresInfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderInfo->RestaurantID));
		$mymapInfo=$this->Foodmart_model->read('*', 'tbllocation', array('RestaurantID' => $orderInfo->RestaurantID));
		$myriderinfo=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $orderInfo->riderid,'RiderlistIsActive'=>1,'Rider_status'=>1));
		if(!empty($myriderinfo)){
		$updatetime = $myriderinfo->DateUpdated;
		}
		else{
			$updatetime ="";
			}
		$newTime = date("h:i A",strtotime($updatetime));
		$actualtime=date('h:i A');
		$newlogoutTime = date("h:i A",strtotime($actualtime." -15 minutes"));
		$lastupdate = strtotime($newTime);
		$sortlogout = strtotime($newlogoutTime);
		if($lastupdate<$sortlogout){
			echo '
            <script>
                alert("ERROR occured. Location Not Updated.please check inputs");
                history.go(-1);
            </script>
        
        
        ';
			}
			else{
		$orderid=$orderInfo->OrderID;
		$amount=round($orderInfo->Amount);
		$DeliveryAddress=$orderInfo->DeliveryAddress;
		$DateInserted=$orderInfo->DateInserted;
		$OrderStatus=$orderInfo->OrderStatus;
		$RiderreceivingStatus=$orderInfo->riderstatus;
		$RestaurantID=$orderInfo->RestaurantID;
		$UserID=$orderInfo->UserID;
		$totalcommision = ($amount*$myresInfo->Commission)/100;
		$valcal = $orderInfo->Vatamount;
		$sercal = $orderInfo->ServiceChargeAmount;
		$respay = ($amount+$valcal+$sercal)-$totalcommision;	
		$Pay_To_Restaurant=round($respay);
		$Pay_To_Customer=round($orderInfo->GrandTotal);
		$RestaurantName=$myresInfo->RestaurantName;
		$ResAddress=$myresInfo->ResAddress;
		$PhoneMobile=$myresInfo->PhoneMobile;
		$Rest_Longitude=$mymapInfo->Longitude;
		$Rest_Latitude=$mymapInfo->Latitude;
		$UserName=$myuserinfo->UserName;
		$Cus_mobile=$myuserinfo->PhoneMobile;
		$senderid=$myriderinfo->Tokenno;
		
		$newmsg=array
				(
					'tag'						=> "incoming_request",
					'orderid'					=> $orderid,
					'amount'					=> $amount,
					'DeliveryAddress'			=> $DeliveryAddress,
					'DateInserted'				=> $DateInserted,
					'OrderStatus'				=> $OrderStatus,
					'RiderreceivingStatus'		=> $RiderreceivingStatus,
					'RestaurantID'				=> $RestaurantID,
					'UserID'					=> $UserID,
					'Pay_To_Restaurant'			=> $Pay_To_Restaurant,
					'Pay_To_Customer'			=> $Pay_To_Customer,
					'RestaurantName'			=> $RestaurantName,
					'ResAddress'				=> $ResAddress,
					'PhoneMobile'				=> $PhoneMobile,
					'Rest_Longitude'			=> $Rest_Longitude,
					'Rest_Latitude'				=> $Rest_Latitude,
					'UserName'					=> $UserName,
					'Cus_mobile'				=> $Cus_mobile
				);
			$message = json_encode( $newmsg );	
				
		
		define( 'API_ACCESS_KEY', 'AAAA5WXi1PQ:APA91bFc0GL_6RbF_NYyn9J7IpXJXL_WQ1busf420gdN0TOxPbg1gKxr1FeYQ6umtTUT9mBq-ymjMG3ZzuwoGpG3Dq5BZnDel9T81d0FcZbR_9s35U43G65xJBcAiW4IesfF_-aIPVmuT8pQzxmKAjiJaVZy82zAbQ' );
				$registrationIds = array($senderid);
				// prep the bundle
				$msg = array
				(
					'message' 					=> $message,
					'title'						=> "TSET",
					'subtitle'					=> "TSET",
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array
				(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				 
				$headers2 = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch2 = curl_init();
				curl_setopt( $ch2,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch2,CURLOPT_POST, true );
				curl_setopt( $ch2,CURLOPT_HTTPHEADER, $headers2 );
				curl_setopt( $ch2,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch2,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch2,CURLOPT_POSTFIELDS, json_encode( $fields2 ) );
				$result2 = curl_exec($ch2 );
				curl_close( $ch2 );
        echo '
            <script>
                alert("Order has been sent to Rider");
                history.go(-1);
            </script>
        ';
			}
		
			}
		else{
			 echo '
            <script>
                alert("ERROR occured. please check inputs");
                history.go(-1);
            </script>';
			}
		}
	public function ridersmslocation(){
		$Orderid=$this->input->post('Orderid4');
		if(!empty($Orderid)){
		       $udata['riderstatus']="Sent";
			   $udata['riderfirstsend']="Sent";
			   $this->db->where('OrderID',$Orderid);
			   $this->db->update('tblorder',$udata);
				
		// Send Notification
		$orderInfo=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $Orderid));
		$myuserinfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderInfo->UserID));
		$myresInfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderInfo->RestaurantID));
		$mymapInfo=$this->Foodmart_model->read('*', 'tbllocation', array('RestaurantID' => $orderInfo->RestaurantID));
		
		$sqlrider="SELECT *,( 6371 * acos( cos( radians(".$mymapInfo['Latitude'].") ) * cos( radians( latitude_value ) ) * cos( radians( longitude_value ) - radians(".$mymapInfo['Longitude'].") ) + sin( radians(".$mymapInfo['Latitude'].") ) * sin( radians( latitude_value ) ) ) ) AS Distance FROM tblriderlist Where Rider_type='Flexible' AND RiderlistIsActive=1 AND Rider_status=1 HAVING Distance < 2 ORDER BY Distance Limit 1";
		  $query_result=  $this->db->query($sqlrider);
          $ridertoken=$query_result->row();
		  if(!empty($ridertoken->Tokenno)){
			$arr=$ridertoken->Tokenno;
			}
		$ridrq=$ridertoken->RiderlistID;
		$totalorderthisrider="SELECT count(riderid) as totalorder FROM tblorder Where OrderStatus='Processing' AND riderid='".$result->RiderlistID."'";
		$resultriderorder = $this->db->query($totalorderthisrider);
		$countorder= $resultriderorder->row();
		if($countorder->totalorder<1){
		  $udata['riderid']=$ridrq;
		  $this->db->where('OrderID',$Orderid);
		  $this->db->update('tblorder',$udata);
		  
		   $sendin['orderid'] 		= $Orderid;
		   $sendin['riderid']  		= $ridrq;
		   $this->Foodmart_model->insert_data('tbljobsend', $sendin);
		   
		$orderid=$orderInfo->OrderID;
		$amount=round($orderInfo->Amount);
		$DeliveryAddress=$orderInfo->DeliveryAddress;
		$DateInserted=$orderInfo->DateInserted;
		$OrderStatus=$orderInfo->OrderStatus;
		$RiderreceivingStatus=$orderInfo->riderstatus;
		$RestaurantID=$orderInfo->RestaurantID;
		$UserID=$orderInfo->UserID;
		$totalcommision = ($amount*$myresInfo->Commission)/100;
		$valcal = $orderInfo->Vatamount;
		$sercal = $orderInfo->ServiceChargeAmount;
		$respay = ($amount+$valcal+$sercal)-$totalcommision;	
		$Pay_To_Restaurant=round($respay);
		$Pay_To_Customer=round($orderInfo->GrandTotal);
		$RestaurantName=$myresInfo->RestaurantName;
		$ResAddress=$myresInfo->ResAddress;
		$PhoneMobile=$myresInfo->PhoneMobile;
		$Rest_Longitude=$mymapInfo->Longitude;
		$Rest_Latitude=$mymapInfo->Latitude;
		$UserName=$myuserinfo->UserName;
		$Cus_mobile=$myuserinfo->PhoneMobile;
		$senderid=$arr;	   
		
		$newmsg=array
				(
					'tag'						=> "incoming_request",
					'orderid'					=> $orderid,
					'amount'					=> $amount,
					'DeliveryAddress'			=> $DeliveryAddress,
					'DateInserted'				=> $DateInserted,
					'OrderStatus'				=> $OrderStatus,
					'RiderreceivingStatus'		=> $RiderreceivingStatus,
					'RestaurantID'				=> $RestaurantID,
					'UserID'					=> $UserID,
					'Pay_To_Restaurant'			=> $Pay_To_Restaurant,
					'Pay_To_Customer'			=> $Pay_To_Customer,
					'RestaurantName'			=> $RestaurantName,
					'ResAddress'				=> $ResAddress,
					'PhoneMobile'				=> $PhoneMobile,
					'Rest_Longitude'			=> $Rest_Longitude,
					'Rest_Latitude'				=> $Rest_Latitude,
					'UserName'					=> $UserName,
					'Cus_mobile'				=> $Cus_mobile
				);
			$message = json_encode( $newmsg );	
		define( 'API_ACCESS_KEY', 'AAAA5WXi1PQ:APA91bFc0GL_6RbF_NYyn9J7IpXJXL_WQ1busf420gdN0TOxPbg1gKxr1FeYQ6umtTUT9mBq-ymjMG3ZzuwoGpG3Dq5BZnDel9T81d0FcZbR_9s35U43G65xJBcAiW4IesfF_-aIPVmuT8pQzxmKAjiJaVZy82zAbQ' );
				$registrationIds = array($senderid);
				// prep the bundle
				$msg = array
				(
					'message' 					=> $message,
					'title'						=> "TSET",
					'subtitle'					=> "TSET",
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array
				(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				 
				$headers2 = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch2 = curl_init();
				curl_setopt( $ch2,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch2,CURLOPT_POST, true );
				curl_setopt( $ch2,CURLOPT_HTTPHEADER, $headers2 );
				curl_setopt( $ch2,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch2,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch2,CURLOPT_POSTFIELDS, json_encode( $fields2 ) );
				$result2 = curl_exec($ch2 );
				curl_close( $ch2 );
		}
		else{
			
			$udata['JobSendStatus']="Cancel";
			$this->db->where('OrderID',$orderid);
			$this->db->update('tblorder',$udata);
			$sendin['orderid'] 		= $orderid;
			$sendin['riderid']  	= $ridrq;
			$this->Foodmart_model->insert_data('tbljobsend', $sendin);
			echo '
            <script>
                alert("Order has been sent to Rider");
                history.go(-1);
            </script>
        	';
			}
			}
		else{
			 echo '
            <script>
                alert("ERROR occured. please check inputs");
                history.go(-1);
            </script>';
			}
		}
	public function sendtomsg(){
		$riderphone=$this->input->post('RiderPhone');
		$riderid=$this->input->post('rider');
		$Orderid=$this->input->post('Orderid');
		$SMSText=$this->input->post('SMSText');
		if((!empty($riderphone)) and (!empty($SMSText))){
			if(!empty($riderid)){
		       $udata['riderstatus']="Sent";
			   $udata['riderid']=$riderid;
			   $this->db->where('OrderID',$Orderid);
			   $this->db->update('tblorder',$udata);
			}
			SendSMS("88".$riderphone,$SMS =$SMSText);
		echo '
            <script>
                alert("Order has been sent to Rider");
                history.go(-1);
            </script>
        ';
			}
		else{
			 echo '
            <script>
                alert("ERROR occured. please check inputs");
                history.go(-1);
            </script>';
			}
		}
	public function riderselect(){
		$riderphone=$this->input->post('RiderPhone2');
		$riderid=$this->input->post('rider2');
		$Orderid=$this->input->post('Orderid2');
		if(!empty($riderphone)){
			if(!empty($riderid)){
		       $udata['riderstatus']="Sent";
			   $udata['riderid']=$riderid;
			   $this->db->where('OrderID',$Orderid);
			   $this->db->update('tblorder',$udata);
			}
		echo '
            <script>
                alert("Order has been sent to Rider");
                history.go(-1);
            </script>
        ';
			}
		else{
			 echo '
            <script>
                alert("ERROR occured. please check inputs");
                history.go(-1);
            </script>';
			}
		}
	public function updatepaymentstatus(){
		$paymethod=$this->input->post('paymethod');
		$Orderid=$this->input->post('orderid');
		       $udata['PaymentMethod']=$paymethod;
			   $this->db->where('OrderID',$Orderid);
			   $this->db->update('tblorder',$udata);
		}
    public function updateshipping(){
		$shipstatus=$this->input->post('shipping');
		$curstatus=$this->input->post('curstatus');
		$oldgrtotal=$this->input->post('oldgrtotal');
		$orderid=$this->input->post('orderid');
		if($curstatus==$shipstatus){
		   
		   }
		else{
	   		$orderchange=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
	   		$checkresinfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderchange->RestaurantID));
			if($shipstatus=="1"){
				$delifee=$checkresinfo->DeliveryFee;
				$grtotal=$delifee+$oldgrtotal;
			}
			else if($shipstatus=="0"){
				$delifee="0";
				$grtotal=$oldgrtotal-$checkresinfo->DeliveryFee;
				}
			$newgrandtotal=number_format($grtotal,2);
			$udata['Shipping']=$shipstatus;
		    $udata['DeliveryFee']=$delifee;
		    $udata['GrandTotal']=$newgrandtotal;
		    $this->db->where('OrderID',$orderid);
		    $this->db->update('tblorder',$udata);
			}
		}
	public function updatecustomer(){
		$userid=$this->input->post('userid');
		$orderid=$this->input->post('orderid');
		$udata['UserName']=$this->input->post('UserName');
		$udata['UserEmail']=$this->input->post('UserEmail');
		$udata['PhoneNumber']=$this->input->post('phone');
		$udata['PhoneMobile']=$this->input->post('mobile');
		$udata['Address']=$this->input->post('address');
		$udata['DeliveryAddress']=$this->input->post('deladdress');
		$udata['ISverified']=$this->input->post('verified');
		$this->db->where('UserID',$userid);
		$this->db->update('tbluser',$udata);
		$orudata['DeliveryAddress']=$this->input->post('deladdress');
		$this->db->where('OrderID',$orderid);
		$this->db->update('tblorder',$orudata);
		 redirect('Changestatus/'.$orderid);
		}
	public function updatenotes(){
		$cususerid=$this->input->post('customerid');
		$restid=$this->input->post('restid');
		$udata['notesforcusrest']=$this->input->post('customertsxt');
		$this->db->where('UserID',$cususerid);
		$this->db->update('tbluser',$udata);
		$orudata['notesforcusrest']=$this->input->post('restaurantexst');
		$this->db->where('UserID',$restid);
		$this->db->update('tbluser',$orudata);
		}
	public function addorder()
    {
       $crm_id=$this->session->userdata('CrmUserID');
	   $crm_cat=$this->session->userdata('CrmUsersCategory');
	   $mylocation=$this->session->userdata('CrmBranch');
       if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
             $this->load->view('crmlogin/login');
        }
		else{
	    $data=array();
        $data['title']='Add New Order';
		$data['alllocation']=$this->Foodmart_model->read_allgroup('*', 'tbluser', array('servicelocation' => $mylocation,'UserIsApproved'=>1,'UserIsActive'=>1),'UserID','','ResAarea');
		//print_r($data['alllocation']);
        $data['content']=$this->load->view('crmlogin/addorder',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
    }
	public function restaurantlist(){
		$reszone=$this->input->post('location');
		$restaurantlist=  $this->Foodmart_model->read_all('*', 'tbluser', array('ResAarea' =>$reszone,'UserIsApproved'=>1,'UserIsActive'=>1));
		  $allrestaurant="";
		 foreach($restaurantlist as $restaurant){
			$allrestaurant.='<option value="'.$restaurant->UserID.'">'.$restaurant->RestaurantName.'</option>';
			 }
		echo '<option value="" selected="selected">Select Restaurant</option>';
		echo $allrestaurant;
		}
	public function restcategorylist(){
		$restaurant=$this->input->post('restaurant');
		$this->cart->destroy();
		$catlist=  $this->Foodmart_model->read_all('*', 'tblcategory', array('RestaurantID' =>$restaurant));
		  $allcategory="";
		 foreach($catlist as $category){
			$allcategory.='<option value="'.$category->CategoryID.'">'.$category->Name.'</option>';
			 }
		echo '<option value="" selected="selected">Select Category</option>';
		echo $allcategory;
		}	
		public function getproductlist(){
		 $restid = $this->input->post('restid');
         $categoryid = $this->input->post('catename');
		 $data['productlist'] =  $this->Foodmart_model->read_all('*', 'tblproducts', array('CategoryID' =>$categoryid,'UserID'=>$restid));
		 $data['Restaurant'] =$this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$restid));
		 $this->load->view('crmlogin/productlist',$data);
		}	
	public function clearcart(){
		$this->cart->destroy();
		}
	public function foodtocart(){
		$currentid=$this->session->userdata('CrmUserID');
		$productID=$this->input->post('ProductID');
		$checktype=$this->input->post('pty');
		if($checktype=="0"){
			$productType="";
			}
		else{
			$productType=$this->input->post('pty');
			}
		$restaurantID=$this->input->post('RID');
		$qty=1;
		$offertxt=$this->input->post('Offertxt');
		$CartRest=$this->input->post('SRID');
		$toping1list=$this->input->post('toping1');
		$toping2list=$this->input->post('toping2');
		$toping3list=$this->input->post('toping3');
		$addonslist=$this->input->post('addons');
		$itemnote=$this->input->post('itemnote');
		$ptypeorsize=$this->input->post('pty');
		$productinfo= $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $productID));
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$string = preg_replace('/\s+/', '', $this->input->post('pty'));
		$ptypename = str_replace(':', '', $string);
		$myid=$productID."_".$ptypename;
		$productName=$productinfo->ProductName;
		$getprice=explode(",",rtrim($productinfo->Price,","));
		$prodiscount=explode(",",rtrim($productinfo->Discount,","));
		$protype=explode(",",rtrim($productinfo->Type,","));
		$protofferNote=explode(",",rtrim($productinfo->offerNote,","));
		$toping1=explode(",",rtrim($productinfo->topingitem,","));
		$toping2=explode(",",rtrim($productinfo->topingitem2,","));
		$toping3=explode(",",rtrim($productinfo->topingitem3,","));
		if(($productinfo->numoftoping=="") || ($productinfo->numoftoping2=="")){
			$numtop="0,";
			$numtop2="0,";
			}
		else{
			$numtop=$productinfo->numoftoping;
			$numtop2=$productinfo->numoftoping;
			}
		$topingitemno=explode(",",rtrim($numtop,","));
		$topingitemno2=explode(",",rtrim($numtop2,","));
		$alladdons=explode(",",rtrim($productinfo->addonitems,","));
		$alladdonsprice=explode(",",rtrim($productinfo->addonitemsprice,","));
		$key=array_search($ptypeorsize,$protype);
		$getproPrice=(int)$getprice[$key];
		$getprotype=(int)$protype[$key];
		if(array_key_exists($key, $protofferNote)){
		   $getprofferNote=$protofferNote[$key];
		}
		else{
		    $getprofferNote="";
		}
		$getprodiscount=(int)$prodiscount[$key];
		if(array_key_exists($key, $topingitemno)){
		   $maxtoping=(int)$topingitemno[$key];
		}
		else{
		    $maxtoping="";
		}
		if(array_key_exists($key, $topingitemno2)){
		    $maxtoping2=(int)$topingitemno2[$key];
		}
		else{
		     $maxtoping2="";
		}
		if($restaurantinfo->Discount>0){
			$discountpercent=$restaurantinfo->Discount;
			if($productinfo->productvat>0){
			$vatprice=$getproPrice+$getproPrice*$productinfo->productvat/100;
			$vatwithprice=round($vatprice);
			}
		else{
			$vatprice=$getproPrice;
			$vatwithprice=round($vatprice);
			}
		}
		else if((!empty($getprodiscount)) || ($getprodiscount>0)){
			$discountpercent=(int)$getprodiscount;
			if($productinfo->productvat>0){
				$vatprice=$getproPrice*$productinfo->productvat/100;
				$vatprice=($vatprice)+($getproPrice-$getproPrice*$productinfo->productvat/100);
				$vatwithprice=round($vatprice);
				}
			else{
				$vatprice=$getproPrice-$getproPrice*$discountpercent/100;
				$vatwithprice=round($vatprice);
				}
			}
		else{
			if($productinfo->productvat>0){
				$vatprice=$getproPrice+$getproPrice*$productinfo->productvat/100;
				$vatwithprice=round($vatprice);
				}
			else{
				$vatprice=$getproPrice;
				$vatwithprice=round($vatprice);
				}
			}
		$gettoping=explode(",",$toping1list);
		$gettoping2=explode(",",$toping2list);
		$getaddons=explode(",",$addonslist);
		
		
	   
		$totaladdonsprice=0;
		if($ptypeorsize=="0"){
			$ptypeorsize="";
			}
		else{
			$ptypeorsize=$ptypeorsize;
			}
		if($getaddons[0]!="") { 
       foreach($getaddons as $seaddons){
				$addonskey=array_search($seaddons,$alladdons);
				$singlepriceaddons=$alladdonsprice[$addonskey];
				$totaladdonsprice=$totaladdonsprice+$singlepriceaddons;
			}
	   }
	   
		
		$vatwithprice=$vatwithprice+$totaladdonsprice;
		
			$insert_data = array(
						'id' 		  => $myid,
						'proid' 	  => $productID,
						'name' 		  => $productName,
						'price' 	  => $vatwithprice,
						'actualprice' => $getproPrice,
						'discount'    => $getprodiscount,
						'type'        => $ptypeorsize,
						'offertxt'    => $getprofferNote,
						'toping1'     => $toping1list,
						'toping2'     => $toping2list,
						'toping3'     => $toping3list,
						'addons'      => $addonslist,
						'RID'         => $restaurantID,
						'itemnote'    => $itemnote,
						'SRID'  	  => $CartRest,
						'qty'         => $qty
					);
			$this->cart->insert($insert_data);
			$sdata['Restaurant']=$restaurantinfo;
			$this->load->view('crmlogin/foodcartdata',$sdata);
		
		}
	function updatefood(){
		$cartID=$this->input->post('CartID');
		$productqty=$this->input->post('qty');
		$Udstatus=$this->input->post('Udstatus');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		if(($Udstatus=="del") && ($productqty>0)){
				$data = array(
				'rowid'=>$cartID,
				'qty'=>$productqty-1
				);
				$this->cart->update($data);
			}
		if($Udstatus=="add"){
			$data = array(
				'rowid'=>$cartID,
				'qty'=>$productqty+1
				);
				$this->cart->update($data);
			}
			$this->load->view('crmlogin/foodcartdata',$sdata);
		}
	function Placeneworder(){
		$crm_id=$this->session->userdata('CrmUserID');
		$restid='';
		foreach ($this->cart->contents() as $item){
			$restid=$item['RID'];
			}
		$restaurantID=$restid;
		$name=$this->input->post('name');
		$password=md5($name);
		$email=$this->input->post('email');
		$Phone=$this->input->post('Phone');
		$DeliveryAddress=$this->input->post('Address');
		$Deliverymethod=$this->input->post('shipping-method');
		$paymentmethod=$this->input->post('payment-method');
		$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserEmail' => $email,'PhoneMobile'=>$Phone));
		if(!$userinfo){
			$datauser['UserUUID']      	   	   		   = GUID();
			$datauser['UserName']       			   = $name;
			$datauser['UserPassword']    			   = $password;
			$datauser['UsersCategory']   	   		   = 2;
			$datauser['UserEmail']                     = $email;
			$datauser['PhoneMobile']   	      		   = $Phone;
			$datauser['Isphoneverified']			   = 0;
			$datauser['UserIsApproved']           	   = 1;
			$datauser['UserIsActive']           	   = 1;
			$datauser['DateInserted']   	   		   = date('Y-m-d H:i:s');
			$datauser['DateUpdated']   	   			   = date('Y-m-d H:i:s');
			$datauser['DateLocked']   	   			   = date('Y-m-d H:i:s');
			$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datauser);
				if($insert_ID > 0){ 
					$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
						$sessiondata = array(
						'UserID' =>$usersession->UserID,
						'UsersCategory' =>$usersession->UsersCategory,
						'UserName' =>$usersession->UserName,
						'UserEmail' =>$usersession->UserEmail
						);
					$this->session->set_userdata($sessiondata);
				}
			}
			else{
				$sessiondata = array(
					'UserID' =>$userinfo->UserID,
					'UsersCategory' =>$userinfo->UsersCategory,
					'UserName' =>$userinfo->UserName,
					'UserEmail' =>$userinfo->UserEmail
					);
				$this->session->set_userdata($sessiondata);
				}

		$Offer='';
		$DeliveryType=1;
		$Deliverydate=date('Y-m-d');
		$new_date_format ="0000-00-00";
		$foodinstruction="";
		$Instruction="";
		$subtotal = $this->cart->total();
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' =>$restid,'UserIsApproved'=>1,'UserIsActive'=>1));
		 if($restaurantinfo->Vat>0){
		  $vat= $subtotal*$restaurantinfo->Vat/100;
		  }
		else{
			$vat= "0.00";
			}
		if($restaurantinfo->ServiceCharge>0){
			$service= $subtotal*$restaurantinfo->ServiceCharge/100;
			}
		else{
			$service= "0.00";
			}
		if($Deliverymethod=="1"){
		$deliveryfee=$restaurantinfo->DeliveryFee;
		$txtDeliveryMethod="Delivery";	
		}
		else{
			$deliveryfee="0.00";
			$txtDeliveryMethod="Pick Up";
		}
		$discount="0";
		$offercode="";
		$discountpercent="";
		$promocode="NULL";
		if($paymentmethod==1){
				$txtPaymentMethod="Cash On Delivery";
			}else if($paymentmethod==2){
				$txtPaymentMethod="Online Card Payment";
			}
			else if($paymentmethod==3){
				$txtPaymentMethod="Bkash Payment";
			}
		$grandtotal=($subtotal+$vat+$service+$deliveryfee)- $discount;
		$session=session_id();
		$TotalPrice=0;
		$pcommisionprice=0;
		$withoutcom=0;
		$restcommisiontotal=0;
		$x=0;
		$html='';
		$currentid=$this->session->userdata('UserID');
		foreach ($this->cart->contents() as $item){
					$productinfo= $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $item['proid']));
					$identity=$productinfo->Ccommision;
					$totalitemprice=$item['price']*$item['qty'];
					$itemactualprice=$item['actualprice']*$item['qty'];
					$itemdiscount=$itemactualprice*$item['discount']/100;
					$TotalPrice+=$totalitemprice;
					$x+=$item['qty'];
					if($identity==""){
						$restcommition=($restaurantinfo->Commission*$totalitemprice)/100;
						$nitrest=$totalitemprice-$restcommition;
						$restcommisiontotal=$nitrest+$restcommisiontotal;
						}
					if($identity=="0"){
						$withoutcom=$totalitemprice+$withoutcom;
						}
					if($identity>0){
						$productcommition=($productinfo->Ccommision*$totalitemprice)/100;
						$nitpcom=$totalitemprice-$productcommition;
						$pcommisionprice=$nitpcom+$pcommisionprice;
						}
					
					 $datatocart['CartUUID']     = GUID();
					 $datatocart['ProductID']    = $item['proid'];
					 $datatocart['Price']        = $item['price'];
					 $datatocart['Discount']     = $itemdiscount;
					 $datatocart['Type']      	 = $item['type'];
					 $datatocart['offertxt']     = $item['offertxt'];
					 $datatocart['toppingname1'] = $item['toping1'];
					 $datatocart['toppingname2'] = $item['toping2'];
					 $datatocart['toppingname3'] = $item['toping3'];
					 $datatocart['adonsnames']   = $item['addons'];
					 $datatocart['itemnotes']    = $item['itemnote'];
					 $datatocart['Company']      = $item['RID'];
					 $datatocart['Quantity']     = $item['qty'];
					 $datatocart['Session']      = $session;
					 $datatocart['CartIsActive'] = "1";
					 $datatocart['UserIDInserted'] = $currentid;
					 $datatocart['UserIDUpdated']  = $currentid;
					 $datatocart['UserIDLocked']   = $currentid;
					 $datatocart['DateInserted'] = date('Y-m-d H:i:s');
					 $datatocart['DateUpdated']  = date('Y-m-d H:i:s');
					 $datatocart['DateLocked']   = date('Y-m-d H:i:s');
					 $datatocart['IsAppCart']   = "0";
					 $this->Foodmart_model->insert_data('tblcart', $datatocart);
					 }
	    $restbill= $restcommisiontotal+$pcommisionprice+$withoutcom+$vat+$service;
		 $datatorder['OrderUUID']     		= GUID();
		 $datatorder['UserID']     			= $currentid;
		 $datatorder['RestaurantID']    	= $restaurantID;
		 $datatorder['Session']    			= $session;
		 $datatorder['Quantity']        	= $x;
		 $datatorder['OrderStatus']     	= "Pending";
		 $datatorder['Amount']      		= $subtotal;
		 $datatorder['Restaurantamount']	= $restbill;
		 $datatorder['Discount']     		= $discount;
		 $datatorder['DiscountType']        = $Offer;
		 $datatorder['DiscountPercentage']  = $discountpercent;
		 $datatorder['promocode']      		= $promocode;
		 $datatorder['Shipping']      		= $Deliverymethod;
		 $datatorder['Vat']      			= $restaurantinfo->Vat;
		 $datatorder['Vatamount']      		= $vat;
		 $datatorder['PaymentMethod']       = $paymentmethod;
		 $datatorder['ServiceCharge']       = $restaurantinfo->ServiceCharge;
		 $datatorder['ServiceChargeAmount'] = $service;
		 $datatorder['DeliveryFee']      	= $deliveryfee;
		 $datatorder['GrandTotal']      	= $grandtotal;
		 $datatorder['DeliveryAddress']     = $DeliveryAddress;
		 
		 $datatorder['delivarytype'] 		= $DeliveryType;
		 if($DeliveryType==2){
		 $datatorder['preordeDate']      	= $new_date_format;
		 $datatorder['preordertime']      	= $Deliverytime;
		 }
		 $datatorder['foodinstraction']     = $foodinstruction;
		 if($restaurantinfo->isnonfoodmart==1){
		 $datatorder['Nonfoodmart']         = $restaurantinfo->isnonfoodmart;
		 }
		 $datatorder['Instruction']      	= $Instruction;
		 $datatorder['OrderIsActive'] 		= "1";
		 $datatorder['ShownBy']             = $crm_id; 
		 $datatorder['UserIDInserted'] 		= $currentid;
		 $datatorder['UserIDUpdated']  		= $currentid;
		 $datatorder['UserIDLocked']   		= $currentid;
		 $datatorder['DateInserted'] 		= date('Y-m-d H:i:s');
		 $datatorder['DateUpdated']  		= date('Y-m-d H:i:s');
		 $datatorder['DateLocked']   		= date('Y-m-d H:i:s');
		 $datatorder['IsAppOrder']   		= "0";
		 $lastinserid=$this->Foodmart_model->insert_data('tblorder', $datatorder);
		 $datatocartupdate['OrderID']      = $lastinserid;
		 $datatocartupdate['OrderID']      = $lastinserid;
		 $datatocartupdate['CartIsActive'] = "0";
		 $this->Foodmart_model->update_info('tblcart', $datatocartupdate, 'Session', $session);
			 // Delivery
			if($Deliverymethod=="1"){
				// sent email on order place.
				$ToEmail=$this->session->userdata('UserEmail');
				$htmlContent=GetOrderTableForEmail($lastinserid);
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->to($ToEmail);
				$this->email->from('order@foodmart.com.bd','Foodmart');
				$this->email->subject('Food order Information');
				$this->email->message($htmlContent);
				$this->email->send();
			}
			// if pickup
			if($Deliverymethod=="0"){
				// sent email on order place.
				$ToEmail=$this->session->userdata('UserEmail');
				$htmlContent=GetOrderTableForEmail($lastinserid);
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->to($ToEmail);
				$this->email->from('order@foodmart.com.bd','Foodmart');
				$this->email->subject('Food order Information');
				$this->email->message($htmlContent);
				$this->email->send();
			 }
				SendSMS('88'.$userinfo->PhoneMobile,
				$SMS ="Dear Sir/Madam, Thank you for order foodmart Your order has been placed. Your order ID is {$lastinserid} Foodmart support team will call you soon. 01793111333");
		   $sdata['message']='Order Placed Successfully!!';
			$this->session->unset_userdata('UserID');
			$this->session->unset_userdata('UsersCategory');
			$this->session->unset_userdata('UserName');
			$this->session->unset_userdata('UserEmail');
			$this->cart->destroy();
			$this->session->set_userdata($sdata);
			redirect('Addnew-order');
		}
	function incommingalert(){
		$neorder=$this->Foodmart_model->placeincomming_order();
		echo $neorder;
		}
	public function crmdaylyreport(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Crm Order Submit Report';
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$data['rows']=  $this->Foodmart_model->read_all('*', 'tblorder', array('OrderStatus' => 'Delivered','ShownBy'=>$crm_id,'DAY(DateInserted)'=>$day,'MONTH(DateInserted)'=>$month,'YEAR(DateInserted)'=>$year));
		$data['totalorder']=  $this->Foodmart_model->totalcrmorder('*', 'tblorder', array('OrderStatus' => 'Delivered','ShownBy'=>$crm_id,'DAY(DateInserted)'=>$day,'MONTH(DateInserted)'=>$month,'YEAR(DateInserted)'=>$year));
		$data['Executive']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $crm_id));
		if($this->input->method() === 'post'){
			 $datatocartupdate['crmstatus'] = "1";
			$where="CheckBy={$crm_id} AND DAY(DateInserted)='{$day}' AND MONTH(DateInserted)='{$month}' AND YEAR(DateInserted)='{$year}'";
			$this->db->where($where);
			$this->db->update('tblcrmchecked', $datatocartupdate);
			}
        $data['content']=$this->load->view('crmlogin/DailyWorkingReport',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
			}
		}
	function chngordstatus(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		$orderid =$this->input->post('orderid');
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$OrderDetails=$this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
		$restaurent=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $OrderDetails->UserID));
		$restaurentcom = $restaurent->Commission;
		$Executive=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $crm_id));
		$totalorder=  $this->Foodmart_model->totalcrmorder('*', 'tblorder', array('OrderStatus' => 'Delivered','ShownBy'=>$crm_id,'DAY(DateInserted)'=>$day,'MONTH(DateInserted)'=>$month,'YEAR(DateInserted)'=>$year));
		$rows=  $this->Foodmart_model->read_all('*', 'tblorder', array('OrderStatus' => 'Delivered','ShownBy'=>$crm_id,'DAY(DateInserted)'=>$day,'MONTH(DateInserted)'=>$month,'YEAR(DateInserted)'=>$year));
		
		$paytype = $this->input->post('paytype');
		$restau = $this->input->post('restau');
		$customer = $this->input->post('customer');
		$profit = $this->input->post('profit');
		$status = $this->input->post('remark');
		
		$tramount = str_replace(",", "", $restau);
		$tcamount = str_replace(",", "", $customer);
		$tpamount = str_replace(",", "", $profit);
		
		$ordtime1=strtotime($OrderDetails->DateInserted);
		$Time1=date("h:i a", $ordtime1);
		$deltime1 = strtotime($OrderDetails->DeliveryTime);
		$ordetimec1 = strtotime($Time1);
		$subTime1 = $deltime1 - $ordetimec1;
		$h1 = ($subTime1/(60*60))%24;
		$m1 = ($subTime1/60)%60;
		$caltime1=$h1.'hs.'.$m1."min";

		$Nonfoodmart   = $OrderDetails->Nonfoodmart;
		if(empty($Nonfoodmart)){
			$Nonfoodmart=NULL;
			}
		else{
			$Nonfoodmart=$Nonfoodmart;
			}
		 $insertdata['CrmcheckedUUID']     	 = GUID();
		 $insertdata['orderid']              = $this->input->post('orderid');
		 $insertdata['UserID']               = $OrderDetails->UserID;
		 $insertdata['RestaurantID']         = $OrderDetails->RestaurantID;
		 $insertdata['Location']             = $OrderDetails->Location;
		 $insertdata['Quantity']             = $OrderDetails->Quantity;
		 $insertdata['OrderStatus']          = $OrderDetails->OrderStatus;
		 $insertdata['crmstatus']            = "0";
		 $insertdata['Restaurantpercent']    = $restaurentcom;
		 $insertdata['Amount']      		 = $tramount;
		 $insertdata['DeliveryFee']      	 = $OrderDetails->DeliveryFee;
		 $insertdata['GrandTotal']      	 = $tcamount;
		 $insertdata['nitprofit']      	 	 = $tpamount;
		 $insertdata['DeliveryAddress']      = $OrderDetails->DeliveryAddress;
		 $insertdata['PaymentMethod']        = $OrderDetails->PaymentMethod;
		 $insertdata['CrmcheckedIsActive']   = $OrderDetails->OrderIsActive;
		 $insertdata['CheckShown']      	 = $OrderDetails->OrderShown;
		 $insertdata['CheckBy']      		 = $OrderDetails->ShownBy;
		 $insertdata['Category']      		 = $OrderDetails->Category;
		 $insertdata['Cooktime']      		 = $OrderDetails->Cooktime;
		 $insertdata['DeliveryTime']      	 = $OrderDetails->DeliveryTime;
		 $insertdata['deliveredtime']        = $caltime1;
		 $insertdata['RemarkNote']      	 = $OrderDetails->RemarkNote;
		 $insertdata['riderid']      		 = $OrderDetails->riderid;
		 $insertdata['Nonfoodmart']      	 = $Nonfoodmart;
		 $insertdata['UserIDInserted']       = $OrderDetails->UserIDInserted;
		 $insertdata['UserIDUpdated']        = $OrderDetails->UserIDUpdated;
		 $insertdata['UserIDLocked']         = $OrderDetails->UserIDLocked;
		 $insertdata['DateInserted']      	 = $OrderDetails->DateInserted;
		 $insertdata['DateUpdated']      	 = $OrderDetails->DateUpdated;
		 $insertdata['DateLocked']      	 = $OrderDetails->DateLocked;
		 $IsAppOrder['DateInserted']      	 = $OrderDetails->IsAppOrder;
		 $this->Foodmart_model->insert_data('tblcrmchecked', $insertdata);
		 $datatocartupdate['crmstatus'] = "1";
		 $this->Foodmart_model->update_info('tblorder', $datatocartupdate, 'OrderID', $orderid);
		 $fromdate = date('Y-m-d');
		$tr='';
		$i=1;
		foreach($rows as $row){
		$CustomerData=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $row->UserID));
		$RestaurantData=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $row->RestaurantID));
		$riderData=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $row->riderid));
	if($row->PaymentMethod==1){
		$Cat="Cash";
	}
	if($row->PaymentMethod==2){
		$Cat="Online";
	}
	if($row->PaymentMethod==3){
		$Cat="Bkash";
	}

	$Amount=(int)$row->Amount;
	
	$CommissionPercent=(int)$RestaurantData->Commission;
	$NetCommission=	$Amount*$CommissionPercent/100;
	$PayAbleAmount=$Amount-$NetCommission;
	
	$ordtime=strtotime($row->DateInserted);
	$Time=date("h:i a", $ordtime);
	$deltime = strtotime($row->DeliveryTime);
	$ordetimec = strtotime($Time);
	$subTime = $deltime - $ordetimec;
	$h = ($subTime/(60*60))%24;
	$m = ($subTime/60)%60;
	$caltime=$h.'hs.'.$m."min";
	
	
	
	$ClientPayment = (int)$row->Amount + $row->ServiceChargeAmount + $row->Vatamount+ $row->DeliveryFee - $row->Discount;
	$RestaurantPayment = $row->Restaurantamount;
	
	$Profit=$ClientPayment - $RestaurantPayment;
	
	if($row->crmstatus!=1){
	$tr.='
		<tr>
			<td>'.$row->OrderID.'</td>
			<td>'.$CustomerData->UserName.'</td>
			<td>'.$CustomerData->Address.'</td>
			<td>'.$CustomerData->PhoneNumber.'</td>
			<td>'.$RestaurantData->RestaurantName.'</td>
			<td>'.$RestaurantData->Commission.'%</td>
			<td><input name="paytype" id="paytype_'.$row->OrderID.'" type="text" value="'.$Cat.'" style="width:80px;" /></td>
			<td>'.$caltime.'</td>
			<td>'.$riderData->RiderName.'</td>
			<td>'.$row->DeliveryFee.'</td>
			<td><input name="remark" id="remark_'.$row->OrderID.'" type="text" value="'.$row->RemarkNote.'" style="width:100px;"/></td>
			<td style="text-align:right"><input name="payrest" id="payrest_'.$row->OrderID.'" type="text" value="'.number_format($RestaurantPayment,2).'" style="width:80px;" /></td>
			<td style="text-align:right"><input name="paycustomer" id="paycustomer_'.$row->OrderID.'" type="text" value="'.number_format($ClientPayment,2).'" style="width:80px;" /></td>
			<td style="text-align:right"><input name="nitprofit" id="nitprofit_'.$row->OrderID.'" type="text" value="'.number_format($Profit,2).'" style="width:80px;" /></td>
			<td style="text-align:right"><a class="btn btn-primary" onclick="updateord('.$row->OrderID.')">Update</a></td>';
		$tr.='</tr>	
	';
	}
	else{
		$crmcheckedData=$this->Foodmart_model->read('*', 'tblcrmchecked', array('UserID' => $row->UserID));
		$tr.='
		<tr>
			<td>'.$row->OrderID.'</td>
			<td>'.$CustomerData->UserName.'</td>
			<td>'.$CustomerData->Address.'</td>
			<td>'.$CustomerData->PhoneNumber.'</td>
			<td>'.$RestaurantData->RestaurantName.'</td>
			<td>'.$RestaurantData->Commission.'%</td>
			<td>'.$Cat.'</td>
			<td>'.$caltime.'</td>
			<td>'.$riderData->RiderName.'</td>
			<td>'.$row->DeliveryFee.'</td>
			<td>'.$row->RemarkNote.'</td>
			<td style="text-align:right">'.$crmcheckedData->Amount.'</td>
			<td style="text-align:right">'.$crmcheckedData->GrandTotal.'</td>
			<td style="text-align:right">'.$crmcheckedData->nitprofit.'</td>
			<td style="text-align:right">Completed</td>';	
		$tr.='</tr>';	
		}
$i++;}?>
		<table border="1" cellpadding="3" width="98%" align="center" id="printTable">
	<tr>
		<td colspan="18" style="text-align:center">
			<h3>Foodmart International Ltd.</h3>
			<p>Daily Working report of Customer Desk</p>
		</td>
	</tr>
	<tr> 
		<td colspan="8">
			<p>Name of Executive: <?php echo $Executive->UserName;?></p>
			<p>Date of Report: <?php echo $fromdate;?></p>
		</td>
		<td colspan="10">
			<p><b>Summary of Duty</b></p>
			<p>Total Dealing Customer: <?php echo $totalorder;?></p>
			<p>Total Received Order:  <?php echo $totalorder;?></p>
		</td>
	</tr>
	<tr>
		<th><b>Order Number</b></th>
		<th><b>Customer Name</b></th>
		<th><b>Address</b></th>
		<th><b>Mobile</b></th>
		<th><b>Restaurant<br/>Name</b></th>
		<th><b>%</b></th>
		<th><b>Categories of<br/>Customer</b></th>
		<th><b>Delivered<br/>Time</b></th>
		<th><b>Rider Name</b></th>
		<th><b>Charge</b></th>
		<th><b>Remarks</b></th>
		<th><b>Restaurant<br/>Bill</b></th>
		<th><b>Customer<br/>Bill</b></th>
		<th><b>Profit</b></th>
		<th><b>Action</b></th>
	</tr>
	<?php echo $tr;?>
</table>
		<?php 
		}
	public function Employeelog(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
			$data=array();
        	$data['title']='Employee Attendness Report';
			$data['eid']=$crm_id;
			$data['content']=$this->load->view('crmlogin/employeelog',$data,TRUE);
        	$this->load->view('crmlogin/master',$data);
			}
		}
	function searchattendness(){
		$userid =$this->input->post('userid');
		if($userid!=""){
	$monthyear =$this->input->post('getmonth');
	$pieces = explode(".", $monthyear);
	$month = $pieces[1];
	$day = $pieces[0];
	$monthnumber="";
	$adminhtml="";
	/*if($month=="August"){$monthnumber=08;}*/
	if($month=="January"){
		$monthnumber="01";
		}
	else if($month=="February"){
		$monthnumber="02";
		}
	else if($month=="March"){
		$monthnumber="03";
		}
	else if($month=="April"){
		$monthnumber="04";
		}
	else if($month=="May"){
		$monthnumber="05";
		}
	else if($month=="June"){
		$monthnumber="06";
		}
	else if($month=="July"){
		$monthnumber="07";
		}
	else if($month=="August"){
		$monthnumber="08";
		}
	else if($month=="September"){
		$monthnumber="09";
		}
	else if($month=="October"){
		$monthnumber="10";
		}
	else if($month=="November"){
		$monthnumber="11";
		}
	else if($month=="December"){
		$monthnumber="12";
		}
	$monthnumber;
	$year =$pieces[2];
	
	$newtitledate=$year.'-'.$month.'-01';
	$title =date("F, Y", strtotime($newtitledate));
	$lastday = date('t',strtotime($newtitledate));
    $firstdate = date($year.'-'.$month.'-01');
    $lastdate = date($year.'-'.$month.'-'.$lastday);
$datediff = strtotime($lastdate) - strtotime($firstdate);
$datediff = floor($datediff/(60*60*24));

for($i = 0; $i < $datediff + 1; $i++){
$alldays= date("Y-m-d", strtotime($firstdate . ' + ' . $i . 'day'));


$sql="SELECT tbluser.UserName,tbluser.PhoneMobile,tblemployeelog .* FROM tblemployeelog Left Join tbluser ON tbluser.UserID=tblemployeelog.UserID WHERE tblemployeelog.UserID={$userid} AND DAY(tblemployeelog.DateInserted)=DAY('{$alldays}') AND MONTH(tblemployeelog.DateInserted)=MONTH('{$alldays}') AND YEAR(tblemployeelog.DateInserted)=YEAR('{$alldays}') Order By tblemployeelog.EmployeelogID ASC LIMIT 1";
 $query_result=  $this->db->query($sql);
 $getrows=$query_result->row();

$sql2="SELECT tbluser.UserName,tbluser.PhoneMobile,tblemployeelog .* FROM tblemployeelog Left Join tbluser ON tbluser.UserID=tblemployeelog.UserID WHERE tblemployeelog.UserID={$userid} AND DAY(tblemployeelog.DateInserted)=DAY('{$alldays}') AND MONTH(tblemployeelog.DateInserted)=MONTH('{$alldays}') AND YEAR(tblemployeelog.DateInserted)=YEAR('{$alldays}') Order By tblemployeelog.EmployeelogID DESC LIMIT 1";
$result2 = $this->db->query($sql2);
$getdrill = $result2->row();
if(!empty($getrows->UserName)){
	$name=$getrows->UserName;
	}
else{
	$name="";
	}
if(!empty($getrows->PhoneMobile)){
	$phone=$getrows->PhoneMobile;
	}
else{
	$phone="";
	}
if(!empty($getrows->logintime)){
	$logintime=$getrows->logintime;
	}
else{
	$logintime="";
	}
if(!empty($getdrill->logouttime)){
	$logouttime=$getdrill->logouttime;
	}
else{
	$logouttime="";
	}
$adminhtml.='<tr>
			  <td>'.$alldays.'</td>
			  <td>'.$name.'</td>
			  <td>'.$phone.'</td>
			  <td>'.$logintime.'</td>
			  <td>'.$logouttime.'</td>
		<tr>';}?>
		<table class="uk-table uk-table-condensed md-bg-deep-purple-50">
                              <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>Employee Name</th>
                                  <th>Phone</th>
								  <th>Login Time</th>
								  <th>Logout Time</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php echo $adminhtml;?>
                              </tbody>
                          </table>
	<?php }
		}
	public function addleave(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Leave Application Form';
		$data['User']=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $crm_id));
		$data['leaveinfo']=$this->Foodmart_model->read('*', 'tblstaffinfo', array('UserID' => $crm_id));
        $data['content']=$this->load->view('crmlogin/LeaveApply',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function saveeleave(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$mytotalleave= $this->input->post('leavetotal');
		$leavegiven= $this->input->post('leavegiven');
		$fromdate= $this->input->post('fromdate');
		$todate= $this->input->post('todate');
		$days = (strtotime($todate) - strtotime($fromdate)) / (60 * 60 * 24);
        $Total =$days+1;
		$alreadytake=$leavegiven+$Total;
		if($mytotalleave>$alreadytake){		
		$passwprd=md5($this->input->post('password'));
		$ins['LeavemanageUUID']      	   	   		= GUID();
		$ins['Userid']           					= $this->input->post('UserID');
		$ins['Username']           					= $this->input->post('UserName');
		$ins['startdate1']         					= $this->input->post('fromdate');
		$ins['Enddate2']           				    = $this->input->post('todate');
		$ins['relievername']           				= $this->input->post('relievar');
		$ins['leavetype']           				= $this->input->post('leavetype');
		$ins['reason']           					= $this->input->post('reason');
		$ins['IsApprove']		   					=  0;
		$ins['UserIDInserted'] 		= $crm_id;
		$ins['UserIDUpdated']  		= $crm_id;
		$ins['UserIDLocked']   		= $crm_id;
		$ins['DateInserted'] 		= date('Y-m-d H:i:s');
		$ins['DateUpdated']  		= date('Y-m-d H:i:s');
		$ins['DateLocked']   		= date('Y-m-d H:i:s');
		$insert_ID = $this->Foodmart_model->insert_data('tblleavemanage', $ins);
        $data['message']='Application Save Successfully !!!';
        $this->session->set_userdata($data);
        redirect('Add-Leavea-Application');
		}
		else{
			$data['error']='Your Leave Exced/Finish to your Total Leave!!!';
			$this->session->set_userdata($data);
			redirect('Add-Leavea-Application');
			}
		}
		
		}
	public function Viewleave(){
			$crm_id=$this->session->userdata('CrmUserID');
			$crm_cat=$this->session->userdata('CrmUsersCategory');
			$mylocation=$this->session->userdata('CrmBranch');
			if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
			{
				  redirect('Crmlogin');
			}
			else{
			$data=array();
			$data['title']='View Leave Status';
			$data['all_leaves']=  $this->Foodmart_model->read_all('*', 'tblleavemanage', array('Userid' => $crm_id));
			$data['content']=$this->load->view('crmlogin/viewleavestatus',$data,TRUE);
			$this->load->view('crmlogin/master',$data);
			}
		}
	public function smsending(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || (($crm_cat!=15)  && ($crm_cat!=16)))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='SMS Sending';
		if($this->input->method() === 'post'){
			$mobilenum=$this->input->post('phone');
			$message=$this->input->post('smg');
			SendSMS('88'.$mobilenum,$SMS =$message);
			$sdata['message']='SMS Send Successfully !!!';
           $this->session->set_userdata($sdata);
		   redirect('Sendsms');
		}
        $data['content']=$this->load->view('crmlogin/smssend',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function riderlist(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Rider List';
		$data['riderlist']=$this->Foodmart_model->read_all('*', 'tblriderlist', array('branchname' => $mylocation));
        $data['content']=$this->load->view('crmlogin/riderlist',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function addriderarea(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Add Rider Area';
		if($this->input->method() === 'post'){
		 $insertdata['RiderareaUUID']     	 = GUID();
		 $insertdata['riderbranch']     	 = $mylocation;
		 $insertdata['RiderareaName']        = $this->input->post('Areaname');
		 $insertdata['RiderareaIsActive']    = 1;
		 $insertdata['UserIDInserted']       = $crm_id;
		 $insertdata['UserIDUpdated']        = $crm_id;
		 $insertdata['UserIDLocked']         = $crm_id;
		 $insertdata['DateInserted']      	 = date('Y-m-d H:i:s');
		 $insertdata['DateUpdated']      	 = date('Y-m-d H:i:s');
		 $insertdata['DateLocked']      	 = date('Y-m-d H:i:s');
		 $this->Foodmart_model->insert_data('tblriderarea', $insertdata);
			$sdata['message']='Rider Area Add Successfully !!!';
           $this->session->set_userdata($sdata);
		   redirect('Add-Rider-Area');
		}
        $data['content']=$this->load->view('crmlogin/addriderarea',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function addrider(){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Add Rider';
		$data['allareas']=$this->Foodmart_model->read_all('*', 'tblriderarea', array('riderbranch' => $mylocation));
        $data['content']=$this->load->view('crmlogin/addrider',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function saverider(){
			$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		 $data=array();
		 $areaid=$this->input->post('riderarea');
		 $areadetails=$this->Foodmart_model->read('*', 'tblriderarea', array('RiderareaID' => $areaid));
         $data['title']='Add Rider';
		 $insertdata['RiderlistUUID']     	 = GUID();
		 $insertdata['RiderareaID']     	 = $areaid;
		 $insertdata['RiderareaName']     	 = $areadetails->RiderareaName;
		 $insertdata['branchname']     	     = $mylocation;
		 $insertdata['RiderName']            = $this->input->post('ridername');
		 $insertdata['phone ']               = $this->input->post('Phone');
		 $insertdata['password']             = $this->input->post('password');
		 $insertdata['Rider_type']           = $this->input->post('ridertype');
		 $insertdata['rider_salery']         = $this->input->post('salary');
		 $insertdata['nid']                  = $this->input->post('nid');
		 $insertdata['paddy_cash']           = $this->input->post('paddycash');
		 $insertdata['minimumlimit']         = $this->input->post('minimumamount');
		 $insertdata['Extralimit']           = $this->input->post('maximumamount');
		 $insertdata['ownorcompanyvichletype'] = $this->input->post('ownorcompanyvichletype');
		 $insertdata['vichletype']           = $this->input->post('vichletype');
		 $insertdata['RiderlistIsActive']    = $this->input->post('RiderlistIsActive');;
		 $insertdata['UserIDInserted']       = $crm_id;
		 $insertdata['UserIDUpdated']        = $crm_id;
		 $insertdata['UserIDLocked']         = $crm_id;
		 $insertdata['DateInserted']      	 = date('Y-m-d H:i:s');
		 $insertdata['DateUpdated']      	 = date('Y-m-d H:i:s');
		 $insertdata['DateLocked']      	 = date('Y-m-d H:i:s');
		 $insertdata['Session']      		 = $areaid.mt_rand().time();
		 $this->Foodmart_model->insert_data('tblriderlist', $insertdata);
			$sdata['message']='Rider Add Successfully !!!';
           $this->session->set_userdata($sdata);
		   redirect('Add-Rider');
		}
		}
	public function updaterider($id){
		$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		$data=array();
        $data['title']='Update Rider';
		$data['riderinfo']=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $id));
		$data['allareas']=$this->Foodmart_model->read_all('*', 'tblriderarea', array('riderbranch' => $mylocation));
        $data['content']=$this->load->view('crmlogin/editrider',$data,TRUE);
        $this->load->view('crmlogin/master',$data);
		}
	}
	public function saveeditrider(){
			$crm_id=$this->session->userdata('CrmUserID');
		$crm_cat=$this->session->userdata('CrmUsersCategory');
		$mylocation=$this->session->userdata('CrmBranch');
		if((empty($crm_id)) || ($crm_cat!=16))
        {
              redirect('Crmlogin');
        }
		else{
		 $data=array();
		 $areaid=$this->input->post('riderarea');
		 $riderid=$this->input->post('riderid');
		 $areadetails=$this->Foodmart_model->read('*', 'tblriderarea', array('RiderareaID' => $areaid));
         $data['title']='Update Rider';
		 $insertdata['RiderareaID']     	 = $areaid;
		 $insertdata['RiderareaName']     	 = $areadetails->RiderareaName;
		 $insertdata['branchname']     	     = $mylocation;
		 $insertdata['RiderName']            = $this->input->post('ridername');
		 $insertdata['phone ']               = $this->input->post('Phone');
	     if(!empty($this->input->post('password'))){
		    $insertdata['password']             = $this->input->post('password');
		 }
		 $insertdata['Rider_type']           = $this->input->post('ridertype');
		 $insertdata['rider_salery']         = $this->input->post('salary');
		 $insertdata['nid']                  = $this->input->post('nid');
		 $insertdata['paddy_cash']           = $this->input->post('paddycash');
		 $insertdata['minimumlimit']         = $this->input->post('minimumamount');
		 $insertdata['Extralimit']           = $this->input->post('maximumamount');
		 $insertdata['ownorcompanyvichletype'] = $this->input->post('ownorcompanyvichletype');
		 $insertdata['vichletype']           = $this->input->post('vichletype');
		 $insertdata['RiderlistIsActive']    = $this->input->post('RiderlistIsActive');;
		 $insertdata['UserIDUpdated']        = $crm_id;
		 $insertdata['DateUpdated']      	 = date('Y-m-d H:i:s');
		 $this->Foodmart_model->update_info('tblriderlist', $insertdata, 'RiderlistID', $riderid);
			$sdata['message']='Rider Update Successfully !!!';
           $this->session->set_userdata($sdata);
		   redirect('listrider');
		}
		}
	public function orderreport(){
			$crm_id=$this->session->userdata('CrmUserID');
			$crm_cat=$this->session->userdata('CrmUsersCategory');
			$mylocation=$this->session->userdata('CrmBranch');
			if($mylocation=='Dhaka'){
			$crmid=4;	
				}
			else{
			$crmid=15;	
				}
			if((empty($crm_id)) || ($crm_cat!=16))
			{
				  redirect('Crmlogin');
			}
			else{
				$data=array();
				$data['title']='View Leave Status';
				$year = date('Y');
				$month = date('m');
				$day = date('d');
				$data['Executives']=$this->Foodmart_model->read_all('*', 'tbluser', array('UsersCategory' =>$crmid,'UserIsActive'=>1,'UserIsApproved'=>1,'servicelocation'=>$mylocation));
				$data['Riders']=$this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderlistIsActive' => 1, 'branchname'=>$mylocation));
				$data['fromDate'] = '';
				$data['toDate'] = '';
				 $data['num_rows']='';
				 $data['result']='';
				 $data['reportedby']='';
				 $data['statement']='';
				 $condition2="";
				if($this->input->method() === 'post'){
					$type=$this->input->post('type');
					$formdate=$this->input->post('fromDate');
					$todate=$this->input->post('toDate');
					$ridertypr=$this->input->post('ridertypr');
					$Executive=$this->input->post('Executive');
					$payment=$this->input->post('payment');
					$riderid=$this->input->post('riderid');
					$data['fromDate'] = $formdate;
					$data['toDate'] = $todate;
					
					
					if(($type=="all") ||($type=="all_executive") || ($type=="all_rider")){
					$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
					$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND tblcrmchecked.crmstatus=1 AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
					$data['reportedby'] = "Admin";
					}
					else if($type=="riderall"){
						$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND tblcrmchecked.crmstatus=1 AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$data['reportedby'] = "Admin";
						}
					else if($type=="executive"){
						$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.ShownBy ={$Executive} AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND crmstatus=1 AND tblcrmchecked.ShownBy {$Executive} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
						 $who=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $Executive));
						$data['reportedby'] = $who->UserName;
						}
					else if($type=="Paytype"){
						if(($Executive!="") && ($riderid!="")){
							$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.PaymentMethod={$payment} AND tblorder.ShownBy ={$Executive} AND tblorder.riderid={$riderid} AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
							$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND crmstatus=1 AND tblcrmchecked.ShownBy {$_POST["Executive"]} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
							$data['reportedby'] = "Admin";
							}
						if(($Executive=="") && ($riderid!="")){
						$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.PaymentMethod={$payment} AND tblorder.riderid={$riderid} AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$condition2 = "t22.servicelocation='{$mylocation}'AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND crmstatus=1 AND tblcrmchecked.ShownBy {$Executive} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
						 $who=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $riderid));
						$data['$reportedby'] = $who->RiderName;
							}
						if(($Executive!="") && ($riderid=="")){
							$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.PaymentMethod={$payment} AND tblorder.ShownBy={$Executive} AND date(tblorder.DateInserted) Between '{$$formdate}' AND '{$todate}'";
							$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND crmstatus=1 AND tblcrmchecked.ShownBy {$Executive} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
							 $who=$this->Foodmart_model->read('*', 'tbluser', array('UserID' => $Executive));
							$data['reportedby'] = $who->UserName;
							}
						if(($Executive=="") && ($riderid=="")){
							$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.PaymentMethod={$payment} AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
							$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND crmstatus=1 AND tblcrmchecked.ShownBy {$Executive} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
							$data['reportedby'] = "Admin";
							}
						}
					else if($type=="riders"){
						$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND tblorder.riderid={$riderid} AND date(tblorder.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND tblcrmchecked.crmstatus=1 AND tblcrmchecked.riderid={$riderid} AND date(tblcrmchecked.DateInserted) Between '{$formdate}' AND '{$todate}'";
						$who=$this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $riderid));
						$data['reportedby'] = $who->RiderName;
						}
					else{
						$condition = "tbluser.servicelocation='{$mylocation}' AND tblorder.OrderStatus='Delivered' AND tblorder.Nonfoodmart IS NULL AND tblorder.crmstatus=1 AND DAY(tblorder.DateInserted)=DAY('{$fromdate}') AND MONTH(tblorder.DateInserted)=MONTH('{$fromdate}') AND YEAR(tblorder.DateInserted)=YEAR('{$fromdate}')";
						$condition2 = "t22.servicelocation='{$mylocation}' AND tblcrmchecked.OrderStatus='Delivered' AND tblcrmchecked.Nonfoodmart IS NULL AND tblcrmchecked.crmstatus=1 AND DAY(tblcrmchecked.DateInserted)=DAY('{$fromdate}') AND MONTH(tblcrmchecked.DateInserted)=MONTH('{$fromdate}') AND YEAR(tblcrmchecked.DateInserted)=YEAR('{$fromdate}')";
						}
					$data['statement'] = base64_encode($condition2);
					if(($ridertypr=="Permanent") || ($ridertypr=="Flexible")){
						$sql="SELECT tblorder.*,tblriderlist.Rider_type,tblriderlist.rider_salery,tbluser.UserID FROM tblorder Left Join tbluser ON tbluser.UserID=tblorder.RestaurantID Inner Join tblriderlist ON tblorder.riderid=tblriderlist.RiderlistID WHERE {$condition}";
						}
					else{
					$sql="SELECT *,tbluser.UserID FROM tblorder Left Join tbluser ON tbluser.UserID=tblorder.RestaurantID WHERE {$condition}";
					}
				 $query_result=  $this->db->query($sql);
				 $data['num_rows']=$query_result->num_rows();
				 $data['result']=$query_result->result();
				}
				$data['content']=$this->load->view('crmlogin/WorkingReportbyadmin',$data,TRUE);
				$this->load->view('crmlogin/master',$data);
				}
		}
		
	public function editupdateorder(){
		$crm_id=$this->session->userdata('CrmUserID');
			$crm_cat=$this->session->userdata('CrmUsersCategory');
			$mylocation=$this->session->userdata('CrmBranch');
			if($mylocation=='Dhaka'){
			$crmid=4;	
				}
			else{
			$crmid=15;	
				}
			if((empty($crm_id)) || ($crm_cat!=16))
			{
				  redirect('Crmlogin');
			}
			else{
		$orderid=$this->input->post('orderid');
		$restau=$this->input->post('restau');
		$customer=$this->input->post('customer');
		$profit=$this->input->post('profit');
		$CheckBy=$this->input->post('crm');
		
		 $orderupdate['ShownBy'] = $this->input->post('crm');
		 $this->Foodmart_model->update_info('tblorder', $orderupdate, 'OrderID', $orderid);
		
		 $checkedupdate['Amount'] = $this->input->post('restau');
		 $checkedupdate['GrandTotal'] = $this->input->post('customer');
		 $checkedupdate['nitprofit'] = $this->input->post('profit');
		 $checkedupdate['CheckBy'] = $this->input->post('crm');
		 $this->Foodmart_model->update_info('tblcrmchecked', $checkedupdate, 'orderid', $orderid);
			}
		}
	public function downloadcsv2($string){
			$crm_id=$this->session->userdata('CrmUserID');
			$crm_cat=$this->session->userdata('CrmUsersCategory');
			$mylocation=$this->session->userdata('CrmBranch');
			if($mylocation=='Dhaka'){
			$crmid=4;	
				}
			else{
			$crmid=15;	
				}
			if((empty($crm_id)) || ($crm_cat!=16))
			{
				  redirect('Crmlogin');
			}
			else{
				$condition =base64_decode($string);
				$csv_filename = 'db_export_Orderreport_'.date('Y-m-d').'.csv';
				
				$sql = "SELECT tblcrmchecked.PaymentMethod,tblcrmchecked.deliveredtime,tblcrmchecked.Amount,tblcrmchecked.GrandTotal,tblcrmchecked.nitprofit,tblcrmchecked.orderid,tblcrmchecked.RemarkNote,t21.UserName,t21.PhoneNumber,t22.servicelocation,t22.RestaurantName,tblriderlist.RiderName FROM tblcrmchecked left join tbluser t21 on tblcrmchecked.UserID = t21.UserID inner join tbluser t22 on tblcrmchecked.RestaurantID = t22.UserID left join tblriderlist on tblriderlist.riderlistid=tblcrmchecked.riderid Where {$condition} Order BY tblcrmchecked.orderid ASC";
				 $query_result=  $this->db->query($sql);
				 $result=$query_result->result();
				 $users = array();
				$restautotal_pay = 0;
				$customertotal_pay=0;
				$nittotal_profit=0;
				 if(!empty($result)) {
	$i=0;
    foreach($result as $row) {
	   $query_res = "SELECT t22.RestaurantName,t22.Commission FROM tblcrmchecked Inner Join tbluser t22 ON t22.UserID=tblcrmchecked.RestaurantID Where {$condition} Order BY tblcrmchecked.orderid ASC";
 $result_res=  $this->db->query($query_res);
 $result2=$result_res->result();
$getrestname =array();
$getcomission =array();
if(!empty($result2)) {
    foreach($result2 as $row_res) {
		  $getrestname[]= $row_res->RestaurantName;
		  $getcomission[]= $row_res->Commission;
		  
    }
}
$query_crm = "SELECT t22.UserName as crmuser FROM tblcrmchecked Inner Join tbluser t22 ON t22.UserID=tblcrmchecked.CheckBy Where {$condition} Order BY tblcrmchecked.orderid ASC";
 $result_crm=  $this->db->query($query_crm);
 $result3=$result_crm->result();
$users3 = array();
if(!empty($result3)) {
     foreach($result3 as $row_crm) {
		  $users3[]= $row_crm->crmuser;
    }
}

$row->Restaurent=$getrestname[$i];
$row->Commission=$getcomission[$i];
$row->CRM=$users3[$i];

//print_r($getarray);
		//echo $row['new'] = 'Cash';
		$new =$row;
		if($row->PaymentMethod=="1"){
		 $row->Payments = 'Cash';
		}
		if($row->PaymentMethod==2){
			$row->Payments = 'Online';
			}
		if($row->PaymentMethod==3){
			$row->Payments = 'Bkash';
			}
		 unset($new);
		 $new['SI']=$i+1;
		 $new['orderid'] = $row->orderid;
		 $new['UserName']=  $row->UserName;
		 $new['PhoneNumber'] = $row->PhoneNumber;
		 $new['Restaurent'] = $row->Restaurent;
		 $new['Commission'] =  $row->Commission;
		 $new['CRM'] = $row->CRM;
		 $new['Payments'] = $row->PaymentMethod;
		 $new['RiderName'] = $row->RiderName;
		 $new['Remarks'] = $row->RemarkNote;
		 $new['deliveredtime'] =  $row->deliveredtime;
		 $new['Amount'] = $row->Amount;
		 $new['GrandTotal'] = $row->GrandTotal;
		 $new['nitprofit'] = $row->nitprofit;
		 $new['Restaurent'] = $row->Restaurent;
		 
	$tramount = str_replace(",", "", $row->Amount);
	$tcamount = str_replace(",", "", $row->GrandTotal);
	$tpamount = str_replace(",", "", $row->nitprofit);

		 
		$restautotal_pay=$restautotal_pay+$tramount;
		$customertotal_pay=$customertotal_pay+$tcamount;
		$nittotal_profit=$nittotal_profit+$tpamount;

		 
		  $users[] = $new;
		  $i++;
    }
	}
	$footer=array("SI"=>"","orderid"=>"","UserName"=>"","PhoneNumber"=>"","Restaurent"=>"","Commission"=>"","CRM"=>"","Payments"=>"","RiderName"=>"","deliveredtime"=>"Total=","Amount"=>$restautotal_pay,"GrandTotal"=>$customertotal_pay,"nitprofit"=>$nittotal_profit);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$csv_filename.'');
$output = fopen('php://output', 'w');
fputcsv($output, array('SI.','Order ID', 'Customer Name', 'Mobile','Restaurant Name','%','Cc','Catagories of Customer','Rider Name','Remarks','Delivered Time','Restaurant','Customer','Profit'));
 
if (count($users) > 0) {
    foreach ($users as $crrow) {
		//print_r($crrow);
        fputcsv($output, $crrow);
    }
	fputcsv($output, array('','', '', '','','','','','','','Total=',$restautotal_pay,$customertotal_pay,$nittotal_profit));

}
				}
		}
}
