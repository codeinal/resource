<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foodmart extends CI_Controller {

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
		$this->load->library('facebook');
        $this->load->library('google');
        date_default_timezone_set('Asia/Dhaka');
		$this->load->model('Foodmart_model');
		define('DIR_IMAGE', '/home/foodmarbd/public_html/');
        define('DIR_CATALOG', '/home/foodmarbd/public_html/application/');
		//$this->output->enable_profiler(TRUE);
		
    }
	public function index()
	{
		$currentid=$this->session->userdata('UserID');
		$this->Foodmart_model->updatedelfree();
		$pageslug=$this->uri->segment(1);
		if(empty($pageslug)){
			$pageslug="Home";
			}
		$data['seo']=$this->Foodmart_model->pageseo($pageslug);
		$data['allLocation']=$this->Foodmart_model->Allocation();
		$data['allArea']=$this->Foodmart_model->Allarea();
		$data['Contacts']=$this->Foodmart_model->Getcontact();
		if($currentid!=''){
			$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
			$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
			$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
			if(!empty($data['chatinfo'])){
			$myid = $data['chatinfo']->UserID;
			$myinbox = $data['chatinfo']->InboxID;
			$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
			$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
			foreach($data['replayf'] as $allreply){
			$senderid = $allreply->SendBy;
			$data['sender']=$this->Foodmart_model->senderf($senderid);
			}
			}
			}
		$data['content'] = $this->load->view('home', $data, TRUE);
        $this->load->view('index', $data);
	}
	
	function home(){
		header("Location: ".$this->config->base_url());
		}
	function Chatload(){
		$currentid=$this->session->userdata('UserID');
		$chatdata = $this->Foodmart_model->read('*', 'tblinbox', array('InboxIsActive' => 1,'UserID'=>$currentid));
		$chatconversation="";
	    if(!empty($chatdata)){
		if($chatdata->SendBy!=""){
				$allreplies="SELECT * FROM tblinbox where InboxIsActive=1 AND UserID='".$currentid."' AND InboxID!='".$chatdata->InboxID."' Order By InboxID ASC";
				$Replaies=$this->db->query($allreplies);
				
				$checkmsg = $this->Foodmart_model->read_all('InboxID', 'tblinbox', array('InboxIsActive' => 1,'UserID'=>$currentid),'InboxID','ASC');
				$messagecount=count($checkmsg);
			if($messagecount>1){	
				foreach($Replaies->result_array() as $Replay){
					if($Replay["SendBy"]==$currentid){
						$me = $this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$currentid));
						if($me->Isonline==1){
							$online="<span style='width:6px; height:6px; background:#0f0; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						else{
							$online="<span style='width:8px; height:8px; background:#999; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						$Title='<h4 class="your_replychat">'.$me->UserName.' '.$online.'</h4>';
						
					}else{
						$Sender = $this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$Replay["SendBy"]));
						if($Sender->Isonline==1){
							$online="<span style='width:6px; height:6px; background:#0f0; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						else{
							$online="<span style='width:8px; height:8px; background:#999; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						$Title='<h4>'.$Sender->UserName.' '.$online.'</h4>';
					}
					$Defference=time_elapsed($Replay["DateInserted"]);
				
					$chatconversation.='<div class="massage_container">
									<div class="col-md-6 col-lg-6 col-sm-push-6 col-xs-12 this_class_for_sm_device">
										<div class="single_massage_timechat">
											<p>
												<span class="second_sp"><img src="'.base_url().'img/clock1.png" alt="" /><span> '.$Defference.'</span></span>
											</p>
										</div>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-pull-6 col-xs-12 this_class_for_sm_device">
										<div class="single_massage_timechat">
											'.$Title.'
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="massage_bodyf">
											<p>'.$Replay["Message"].'</p>
										</div>
									</div>
								</div>	
					';
				}
			}
			}
		}
		echo $chatconversation;
		}
	function incomingorder(){
		$currentid=$this->session->userdata('UserID');
		$checkinbox3 = $this->Foodmart_model->read('OrderID', 'tblorder', array('RestaurantID'=>$currentid,'OrderStatus'=>'Processing','IsRead'=>0));
				$messagecount3=count($checkinbox3);
				if($messagecount3>0){
					echo $numm3='<span style="font-size:24px;"><i class="fa fa-envelope-o"></i></span><span style="color:#000;">'.$messagecount3.'</span>';
					}
				else{
					echo $numm3="";
					}
		}
	function notificationchat(){
		$currentid=$this->session->userdata('UserID');
		$checkinbox3 = $this->Foodmart_model->read('InboxID', 'tblinbox', array('UserID'=>$currentid,'IsRead'=>0));
				$messagecount3=count($checkinbox3);
				if($messagecount3>0){
					echo $numm3='<span style="font-size:24px;"><i class="fa fa-bell-o"></i></span>'.$messagecount3;
					}
				else{
					echo $numm3="";
					}
		}
	function ChatUpdate(){
		$currentid=$this->session->userdata('UserID');
		$reqiredID = $this->input->post('id');
		if($reqiredID!=""){
			$msg = urldecode($this->input->post('smg'));
			$senderID = $this->input->post('Sender');
			$CheckRead = $this->Foodmart_model->read('*', 'tblinbox', array('InboxID'=>$reqiredID));
			if($CheckRead->IsRead==0){
					if($CheckRead->SendBy==$senderID){
						$Read=0;
					}else{
						$Read=1;
					}
				}else{
					$Read=1;
				}
				
			$dataf['UserID']       		= $senderID;
			$dataf['Message']    		= $msg;
			$dataf['IsRead']   	   		= 0;
			$dataf['ParentID']          = $reqiredID;
			$dataf['InboxIsActive']   	= 1;
			$dataf['SendBy']   	      	= $senderID;
			$dataf['DateInserted']   	= date('Y-m-d H:i:s');
			$dataf['DateUpdated']   	= date('Y-m-d H:i:s');
			$dataf['DateLocked']   	   	= date('Y-m-d H:i:s');
			$dataup['IsRead']   	    = $Read;
			$dataup2['IsRead']   	    = 1;
			$this->Foodmart_model->insert_data('tblinbox', $dataf);
			$this->Foodmart_model->update_info('tblinbox', $dataup, 'InboxID', $reqiredID);
			$this->Foodmart_model->update_info('tblinbox', $dataup2, 'InboxID', $reqiredID);
			}
		$chatdata = $this->Foodmart_model->read('*', 'tblinbox', array('InboxIsActive' => 1,'UserID'=>$currentid));
		$chatconversation="";
		if($chatdata->SendBy!=""){
				$allreplies="SELECT * FROM tblinbox where InboxIsActive=1 AND UserID='".$currentid."' AND InboxID!='".$chatdata->InboxID."' Order By InboxID ASC";
				$Replaies=$this->db->query($allreplies);
				//print_r($Replaies->result_array());
				$checkmsg = $this->Foodmart_model->read_all('InboxID', 'tblinbox', array('InboxIsActive' => 1,'UserID'=>$currentid),'InboxID','ASC');
			$messagecount=count($checkmsg);
			if($messagecount>1){	
				foreach($Replaies->result_array() as $Replay){
					if($Replay["SendBy"]==$currentid){
						$me = $this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$currentid));
						if($me->Isonline==1){
							$online="<span style='width:6px; height:6px; background:#0f0; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						else{
							$online="<span style='width:8px; height:8px; background:#999; border-radius:50px; float:right;'>&nbsp;</span>";
							}
				$Title='<h4 class="your_replychat">'.$me->UserName.' '.$online.'</h4>';
					
						
					}else{
						$Sender = $this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$Replay["SendBy"]));
						if($Sender->Isonline==1){
							$online="<span style='width:6px; height:6px; background:#0f0; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						else{
							$online="<span style='width:8px; height:8px; background:#999; border-radius:50px; float:right;'>&nbsp;</span>";
							}
						$Title='<h4>'.$Sender->UserName.' '.$online.'</h4>';
					}
					$Defference=time_elapsed($Replay["DateInserted"]);
				
					$chatconversation.='<div class="massage_container">
									<div class="col-md-6 col-lg-6 col-sm-push-6 col-xs-12 this_class_for_sm_device">
										<div class="single_massage_timechat">
											<p>
												<span class="second_sp"><img src="'.base_url().'img/clock1.png" alt="" /><span> '.$Defference.'</span></span>
											</p>
										</div>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-pull-6 col-xs-12 this_class_for_sm_device">
										<div class="single_massage_timechat">
											'.$Title.'
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="massage_bodyf">
											<p>'.$Replay["Message"].'</p>
										</div>
									</div>
								</div>	
					';
				}
			}
			}
		echo $chatconversation;
	}
	function Createnewaccount(){
		 $this->load->view('Createnewaccount');
		}
	function signup(){
		$username = $this->input->post('UserName');
		$email = $this->input->post('UserEmailAddress');
		$phonenumber = $this->input->post('UserPhoneNumber');
		$pass1 = $this->input->post('Userpassword');
		$pass2 = $this->input->post('Confirmpass');
		
		//$this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
        $this->form_validation->set_rules('UserName','Username', 'required|trim|xss_clean|is_unique[tbluser.UserName]');
		$this->form_validation->set_rules('UserEmailAddress','User Email', 'required|valid_email|trim|xss_clean|is_unique[tbluser.UserEmail]');
		$this->form_validation->set_rules('UserPhoneNumber','Phone Number', 'required|trim|xss_clean|is_unique[tbluser.PhoneMobile]');
        $this->form_validation->set_rules('Userpassword','Password', 'required|trim|xss_clean');
		
        $password = md5($pass1);
			if($this->form_validation->run()==FALSE)
			{
				echo validation_errors();
			}
			else{
				$vcode=Randomvcode(6);	
				$datain['UserUUID']      	   	   		   = GUID();
				$datain['UserName']       				   = $this->input->post('UserName');
				$datain['UserPassword']    				   = $password;
				$datain['UsersCategory']   	   			   = 2;
			    $datain['UserEmail']                       = $this->input->post('UserEmailAddress');
				$datain['PhoneMobile']   	      		   = $this->input->post('UserPhoneNumber');
				$datain['Isphoneverified']				   =0;
				$datain['verifiedcode']  				   =$vcode;
				$datain['UserIsApproved']           	   = 1;
				$datain['UserIsActive']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
				if($insert_ID > 0){ 
				$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
					$sessiondata = array(
					'UserID' =>$usersession->UserID,
					'UsersCategory' =>$usersession->UsersCategory,
					'UserName' =>$usersession->UserName,
					'UserEmail' =>$usersession->UserEmail
					);
				$this->session->set_userdata($sessiondata);
				$udata['UserIDInserted']=$usersession->UserID;
				$udata['UserIDUpdated']=$usersession->UserID;
				$udata['UserIDLocked']=$usersession->UserID;
				$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
		 		}
				// Send SMS
			SendSMS('88'.$_POST["UserPhoneNumber"],
				$SMS ="Dear {$_POST["UserName"]}, Welcome to foodmart. You can order your favorite restaurants food by www.foodmart.com.bd or app. Support: 01793111333");
			
				// Verification Code
			SendSMS('88'.$_POST["UserPhoneNumber"],
				$SMS ="Dear {$_POST["UserName"]}, Welcome to foodmart. Your Verification Code is: {$vcode}.  Support: 01793111333");
			// sent welcome email.
			SendMail(
				$ToEmail=$_POST["UserEmailAddress"],
				$Subject="Congratulations! Your registration completed successfully!",
				$Body="
					
					Hi {$_POST["UserName"]},
					<br><br>
					
					Thank you for joining with foodmart. We take great pleasure in welcoming you to foodmart family.
					Now you can order your food from your favorite local restaurants. We serve more the 500+ restaurants  food to your doorsteps. You get to enjoy restaurants quality food from your zone. We provide you excellent delivery service with good quality. You can order your food from your favorite nearby restaurants just in a minute by our website/App/hotline. Our intention is to achieve your 100% satisfaction. 
					<br><br>
					Support Team<br>
					Foodmart <br>
					www.foodmart.com.bd  <br>
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
		
		}
	function checkphone(){
		$myid = $this->session->userdata('UserID');
		$phonenumber = $this->input->post('UserPhoneNumber');
		$userID= $this->Foodmart_model->read('*', 'tbluser', array('PhoneMobile' => $phonenumber,'UserID' => $myid));
				if(count($userID)>0){
					echo "404";
					}
				else{
					$vcode=Randomvcode(6);	
				// Verification Code
			     SendSMS('88'.$_POST["UserPhoneNumber"],
				$SMS ="Dear {$userID->UserName}, Welcome to foodmart. Your Verification Code is: {$vcode}.  Support: 01793111333");
				
					$udata['PhoneMobile']=$phonenumber;
					$udata['Isphoneverified']=0;
					$udata['verifiedcode']=$vcode;
					$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $myid);
				}
		}
	function phoneverified(){
		$myid = $this->session->userdata('UserID');
		$Vefiriedcode = $this->input->post('Vefiriedcode');
		$userID= $this->Foodmart_model->read('*', 'tbluser', array('verifiedcode' => $Vefiriedcode,'UserID' => $myid));
				if(count($userID)>0){
					$udata['Isphoneverified']=1;
					$udata['verifiedcode']=Randomvcode(6);
					$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $myid);
					
					}
				else{
					echo "404";
				}
		}
	function resendvcode(){
		$myid = $this->session->userdata('UserID');
		$vcode=Randomvcode(6);	
				// Verification Code
			SendSMS('88'.$_POST["UserPhoneNumber"],
				$SMS ="Dear {$userID->UserName}, Welcome to foodmart. Your Verification Code is: {$vcode}.  Support: 01793111333");
					$udata['Isphoneverified']=0;
					$udata['verifiedcode']=$vcode;
					$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $myid);
		}
	function signinfrm(){
		 $this->load->view('signinfrm');
		}
	function signinfrmcheckuot($id){
		 $datas['resid']=$id;
		 $this->load->view('signinfrm2',$datas);
		}
	function Forgotpass(){
		 $this->load->view('Forgotpass');
		}
	function recoverpass(){
		 $email = $this->input->post('UserEmailAddress');
		 $this->form_validation->set_rules('UserEmailAddress','User Email', 'required|valid_email|trim|xss_clean');
		 if($this->form_validation->run()==FALSE)
			{
				echo validation_errors();
			}
			else{
				$userID= $this->Foodmart_model->read('*', 'tbluser', array('UserEmail' => $email));
				if(count($userID)>0){
				$muid=$userID->UserID;
				$email=$userID->UserEmail;
				$UserName=$userID->UserName;
				$NewPassword=RandomPassword();
				
				//$datain['UserEmail']  = $this->input->post('UserEmailAddress');
				$datain['UserPassword']=md5($NewPassword);
				$this->Foodmart_model->update_info('tbluser', $datain, 'UserID', $muid);
				//Email the changed log in information to the user with a registration confirmation
				SendMail(
			$ToEmail=$email,
			$Subject="Your login detail",
			$Body="Upon your request, we have reset your login password on our system.\n\n
				If you didn't request this information, please log into your account immediately and change your password to prevent unauthorized use of your information.\n\n
				Username: {$UserName}\n
				Password: {$NewPassword}\n\n
				Thanking you,\n\n",

			$FromName="Foodmart.com.bd",
			$FromEmail = "order@foodmart.com.bd",
			$ReplyToName="Foodmart.com.bd",
			$ReplyToEmail="order@foodmart.com.bd",
			$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			
		);
				echo "success";
				}
				else{
					echo "404";
				}
			}
		}
	function login(){
            $username = $this->input->post('phone');
            $password = md5($this->input->post('pass1'));
            $cek = $this->Foodmart_model->loginUser($username, $password);
            if($cek <> 0)
            {
				$myid = $this->session->userdata('UserID');
				if(($this->session->userdata('UsersCategory')==4) || ($this->session->userdata('UsersCategory')==6) || ($this->session->userdata('UsersCategory')==8)){
					$sql="select * from tblstaffinfo where UserID='".$myid."'";
					$query_result=  $this->db->query($sql);
					$myinfo=$query_result->row();
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
							echo $this->session->userdata('UsersCategory')."_".$myid; 
							$this->session->unset_userdata('UserID');
							$this->session->unset_userdata('UsersCategory');
							$this->session->unset_userdata('UserName');
							$this->session->unset_userdata('UserEmail');
							$this->session->sess_destroy();
						}
						else{
							$this->session->unset_userdata('UserID');
							$this->session->unset_userdata('UsersCategory');
							$this->session->unset_userdata('UserName');
							$this->session->unset_userdata('UserEmail');
							$this->session->sess_destroy();
							echo $this->session->userdata('UsersCategory')."_error";
							}
					}
				else if($this->session->userdata('UsersCategory')==2){
							$udata['Isonline']='1';
							$this->db->where('UserID',$myid);
							$this->db->update('tbluser',$udata);
							echo  $this->session->userdata('UsersCategory')."_"."Success";
				}
				else if($this->session->userdata('UsersCategory')==15){
					$udata['Isonline']='1';
							$this->db->where('UserID',$myid);
							$this->db->update('tbluser',$udata);
							echo  $this->session->userdata('UsersCategory')."_"."Success";
					}
				// if Restaurant Promoter
				else if($this->session->userdata('UsersCategory')==14){
							$udata['Isonline']='1';
							$this->db->where('UserID',$myid);
							$this->db->update('tbluser',$udata);
							echo  $this->session->userdata('UsersCategory')."_"."Success";
				}
				// if Restaurant Owners
				 else if($this->session->userdata('UsersCategory')==3){
					$udata['Isonline']='1';
					$this->db->where('UserID',$myid);
					$this->db->update('tbluser',$udata);
					echo $this->session->userdata('UsersCategory')."_".$myid;
				}
				// if Backend People
				else if($this->session->userdata('UsersCategory')==1){
					$udata['Isonline']='1';
					$this->db->where('UserID',$myid);
					$this->db->update('tbluser',$udata);
					echo $this->session->userdata('UsersCategory')."_".$myid; 
				}
				
				else if($this->session->userdata('UsersCategory')>3){
					$udata['Isonline']='1';
					$this->db->where('UserID',$myid);
					$this->db->update('tbluser',$udata);
					echo $this->session->userdata('UsersCategory')."_".$myid; 
				}	
            }
            else
            {
              echo "404_error";
			   
            }
        
    }
	function checkoutlogin(){
            $username = $this->input->post('phone');
            $password = md5($this->input->post('pass1'));
            $cek = $this->Foodmart_model->loginUser($username, $password);
            if($cek <> 0)
            {
					$myid = $this->session->userdata('UserID');
					$udata['Isonline']='1';
					$this->db->where('UserID',$myid);
					$this->db->update('tbluser',$udata);
					echo  $this->session->userdata('UsersCategory')."_"."Success"; 
            }
            else
            {
              echo "404_error";
			   
            }
        
    }
		public function google_login2(){
	    
	    if($this->session->userdata('UserID')== TRUE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
			    if(isset($_GET['code'])){
			//authenticate user
			$this->google->getAuthenticate();
			
			//get user info from google
			//https://foodmart.com.bd/foodmart/google_login
			$gpInfo = $this->google->getUserInfo();
			
            //preparing data for database insertion
			$userData['oauth_provider'] = 'google';
			$userData['oauth_uid'] 		= $gpInfo['id'];
            $userData['first_name'] 	= $gpInfo['given_name'];
            $userData['last_name'] 		= $gpInfo['family_name'];
            $userData['email'] 			= $gpInfo['email'];
			$userData['gender'] 		= !empty($gpInfo['gender'])?$gpInfo['gender']:'';
			$userData['locale'] 		= !empty($gpInfo['locale'])?$gpInfo['locale']:'';
            $userData['profile_url'] 	= !empty($gpInfo['link'])?$gpInfo['link']:'';
            $userData['picture_url'] 	= !empty($gpInfo['picture'])?$gpInfo['picture']:'';
			
			//insert or update user data to the database
            $userID = $this->user->checkUser($userData);
			
			//store status & user info in session
			$this->session->set_userdata('loggedIn', true);
			$this->session->set_userdata('userData', $userData);
			
			//redirect to profile page
		//	redirect('user_authentication/profile/');
		} 
		//google login url
		 $data['loginURL'] = $this->google->loginURL();
		redirect($this->google->loginURL());
			    
			}
	}
	public function google_login()
	{
	     if(isset($_GET['code'])){
			//authenticate user
			$this->google->getAuthenticate();
			
			//get user info from google
			$gpInfo = $this->google->getUserInfo();
			
           //insert or update user data to the database
           // $userID = $this->user->checkUser($userData);
			
			$id=md5($gpInfo['id']);
						$userID= $this->Foodmart_model->read('*', 'tbluser', array('UserPassword' => $id,'UserName' => $gpInfo['given_name'],'UserIsActive'=>1));
						if(count($userID)>0){
						    echo "Please wait...";
						$sessiondata = array(
							'UserID' =>$userID->UserID,
							'UsersCategory' =>$userID->UsersCategory,
							'UserName' =>$userID->UserName,
							'UserEmail' =>$userID->UserEmail
							);
							$this->session->set_userdata($sessiondata);
						
						}
						else{
					    echo "Please wait...";
						$datain['UserUUID']      	   	   		   = GUID();
		    	        $datain['UserName']       				   = $gpInfo['given_name'];
						$datain['UserPassword']    				   = $id;
						$datain['UsersCategory']   	   			   = 2;
						$datain['UserEmail']                       = $gpInfo['email'];
						$datain['IsSocialUser']           	   	   = 1;
						$datain['UserIsApproved']           	   = 1;
						$datain['UserIsActive']           	       = 1;
						$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
						$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
						$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
						$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
						if($insert_ID > 0){ 
						$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
							$sessiondata = array(
							'UserID' =>$usersession->UserID,
							'UsersCategory' =>$usersession->UsersCategory,
							'UserName' =>$usersession->UserName,
							'UserEmail' =>$usersession->UserEmail
							);
						$this->session->set_userdata($sessiondata);
						$udata['UserIDInserted']=$usersession->UserID;
						$udata['UserIDUpdated']=$usersession->UserID;
						$udata['UserIDLocked']=$usersession->UserID;
						$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
						
							}
			
			        //redirect to profile page
		            //	redirect('user_authentication/profile/');
				}
			echo"<script>
						window.location ='".base_url()."';
				    	</script>";	
		} 
	}
public function tweetwe_login(){
		$userData = array();
		//Twitter API Configuration
		$consumerKey = 'FB5gYY3Lc9DwLDdh6G3ou9Rvz';
		$consumerSecret = 'ubCjRCynbgrmuYH1l8b1Wcr47PmouvpYpquaHvoElYuvSxdwkW';
		$oauthCallback = base_url()."foodmart/twlogin";
		
		//Include the twitter oauth php libraries
		include_once APPPATH."libraries/twitter/twitteroauth.php";
		
		//Get existing token and token secret from session
	    $sessToken = $this->session->userdata('token');
		$sessTokenSecret = $this->session->userdata('token_secret');
		
		//Get status and user info from session
		
		$sessStatus = $this->session->userdata('status');
		$sessUserData = $this->session->userdata('userData');
		
		if(isset($_REQUEST['oauth_token']) && ($sessToken !== $_REQUEST['oauth_token'])) {
	
		$this->session->unset_userdata('UserID');
		$this->session->unset_userdata('UsersCategory');
		$this->session->unset_userdata('UserName');
		$this->session->unset_userdata('UserEmail');
		$this->session->unset_userdata('token');
		$this->session->unset_userdata('token_secret');
		$this->session->unset_userdata('status');
		$this->session->unset_userdata('userData');

		
	}
		else if(isset($_REQUEST['oauth_token']) && $sessToken == $_REQUEST['oauth_token']) {
			//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
				$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessToken , $sessTokenSecret);
				$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
				
				if($connection->http_code == '200')
				{
					//Redirect user to twitter
					$sessiondata = array(
							'status' =>'verified',
							'request_vars' =>$access_token
							);
					
					$this->session->set_userdata($sessiondata);
					
					$userInfo = $connection->get('account/verify_credentials');
					//print_r($user_info);
					$id=md5($userInfo->id);
						$userID= $this->Foodmart_model->read('*', 'tbluser', array('UserPassword' => $id,'UserName' => $userInfo->screen_name,'UserIsActive'=>1));
						if(count($userinfo)>0){
							echo "Please wait...";
							$sessiondata = array(
							'UserID' =>$usersession->UserID,
							'UsersCategory' =>$usersession->UsersCategory,
							'UserName' =>$usersession->UserName,
							'UserEmail' =>$usersession->UserEmail
							);
							$this->session->set_userdata($sessiondata);
						}
						else{
						echo "Please wait...";
						$datain['UserUUID']      	   	   		   = GUID();
						$datain['UserName']       				   = $userInfo->screen_name;
						$datain['UserPassword']    				   = $id;
						$datain['UsersCategory']   	   			   = 2;
						$datain['UserEmail']                       = $id."@foodmart.com.bd";
						$datain['IsSocialUser']           	   	   = 1;
						$datain['UserIsApproved']           	   = 1;
						$datain['UserIsActive']           	       = 1;
						$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
						$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
						$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
						$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
						if($insert_ID > 0){ 
						$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
							$sessiondata = array(
							'UserID' =>$usersession->UserID,
							'UsersCategory' =>$usersession->UsersCategory,
							'UserName' =>$usersession->UserName,
							'UserEmail' =>$usersession->UserEmail
							);
						$this->session->set_userdata($sessiondata);
						$udata['UserIDInserted']=$usersession->UserID;
						$udata['UserIDUpdated']=$usersession->UserID;
						$udata['UserIDLocked']=$usersession->UserID;
						$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
							}
						}
					
					$this->session->unset_userdata('token');
					$this->session->unset_userdata('token_secret');
					redirect('home');	
				}else{
					die("error, try again later!");
				}
			}
		else{ //echo "Hi";
	
				if(isset($_GET["denied"]))
				{
					redirect('home');
					
				}
			
				//Fresh authentication
				$connection = new TwitterOAuth($consumerKey, $consumerSecret);
				$request_token = $connection->getRequestToken($oauthCallback);
				
				//Received token info from twitter
				  	$sessiondata = array(
						'token' =>$request_token['oauth_token'],
						'token_secret' =>$request_token['oauth_token_secret']
						);
					
					$this->session->set_userdata($sessiondata);
		           //$sessToken = $request_token['oauth_token'];
				   //$sessTokenSecret = $request_token['oauth_token_secret'];
				
				//Any value other than 200 is failure, so continue only if http code is 200
				if($connection->http_code == '200')
				{
					$userInfo = $connection->get('account/verify_credentials');
			//print_r($userInfo);
					//redirect user to twitter
					$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
					redirect($twitter_url);
				}else{
					die("error connecting to twitter! try again later!");
				}
			}
    }
public function twlogin(){
    	$userData = array();
		//Twitter API Configuration
		$consumerKey = 'FB5gYY3Lc9DwLDdh6G3ou9Rvz';
		$consumerSecret = 'ubCjRCynbgrmuYH1l8b1Wcr47PmouvpYpquaHvoElYuvSxdwkW';
		$oauthCallback = base_url();
		
		//Include the twitter oauth php libraries
		include_once APPPATH."libraries/twitter/twitteroauth.php";
		
		//Get existing token and token secret from session
	    $sessToken = $this->session->userdata('token');
		$sessTokenSecret = $this->session->userdata('token_secret');
		
		//Get status and user info from session
		
		$sessStatus = $this->session->userdata('status');
		$sessUserData = $this->session->userdata('userData');
		
		if($sessToken == $_GET['oauth_token']) {
		
		 //Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
				$connection = new TwitterOAuth($consumerKey, $consumerSecret, $sessToken , $sessTokenSecret);
				$access_token = $connection->getAccessToken($_GET['oauth_verifier']);
				
				if($connection->http_code == '200')
				{
				  
					//Redirect user to twitter
					$sessiondata = array(
							'status' =>'verified',
							'request_vars' =>$access_token
							);
					
					$this->session->set_userdata($sessiondata);
					
					$userInfo = $connection->get('account/verify_credentials');
					//print_r($user_info);
					$id=md5($userInfo->id);
						$userID= $this->Foodmart_model->read('*', 'tbluser', array('UserPassword' => $id,'UserName' => $userInfo->screen_name,'UserIsActive'=>1));
						if(count($userID)>0){
						    echo "Please wait...";
						$sessiondata = array(
							'UserID' =>$userID->UserID,
							'UsersCategory' =>$userID->UsersCategory,
							'UserName' =>$userID->UserName,
							'UserEmail' =>$userID->UserEmail
							);
							$this->session->set_userdata($sessiondata);
						
						}
						else{
					    echo "Please wait...";
						$datain['UserUUID']      	   	   		   = GUID();
						$datain['UserName']       				   = $userInfo->screen_name;
						$datain['UserPassword']    				   = $id;
						$datain['UsersCategory']   	   			   = 2;
						$datain['UserEmail']                       = $id."@foodmart.com.bd";
						$datain['IsSocialUser']           	   	   = 1;
						$datain['UserIsApproved']           	   = 1;
						$datain['UserIsActive']           	       = 1;
						$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
						$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
						$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
						$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
						if($insert_ID > 0){ 
						$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
							$sessiondata = array(
							'UserID' =>$usersession->UserID,
							'UsersCategory' =>$usersession->UsersCategory,
							'UserName' =>$usersession->UserName,
							'UserEmail' =>$usersession->UserEmail
							);
						$this->session->set_userdata($sessiondata);
						$udata['UserIDInserted']=$usersession->UserID;
						$udata['UserIDUpdated']=$usersession->UserID;
						$udata['UserIDLocked']=$usersession->UserID;
						$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
						
							}
						}
					
					$this->session->unset_userdata('token');
					$this->session->unset_userdata('token_secret');
				echo"<script>
						window.location ='".base_url()."';
				    	</script>";
					}else{
					die("error, try again later!");
				}
			}
    
}
public function web_login()
	{
$data['user'] = array();

		// Check if user is logged in
		if($this->facebook->is_authenticated())
		{
			// User logged in, get user details
			$user = $this->facebook->request('get', '/me?fields=id,name,email,birthday,gender,picture');
			if (!isset($user['error']))
			{
				$data['user'] = $user;
				
				  $user_information  = array(
				    'name'    => $user["name"],
				    'email'   => $user["email"],
				    'gender'  => $user["gender"]
				);
				$id=md5($user['id']);
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserPassword' => $id,'UserIsActive'=>1));
	            if(count($userinfo)>0) :
		            $sessiondata=array('UserID'=>$userinfo->UserID,'UsersCategory'=>$userinfo->UsersCategory,'UserName'=>$userinfo->UserName,'UserEmail'=>$userinfo->UserEmail);
	            else :
				$datain['UserUUID']      	   	   		   = GUID();
				$datain['UserName']       				   = $user["name"];
				$datain['UserPassword']    				   = $id;
				$datain['UsersCategory']   	   			   = 2;
			    $datain['UserEmail']                       = $user["email"];
				$datain['IsSocialUser']           	   	   = 1;
				$datain['UserIsApproved']           	   = 1;
				$datain['UserIsActive']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
				if($insert_ID > 0){ 
				$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail', 'tbluser', array('UserID' => $insert_ID));
					$sessiondata = array(
					'UserID' =>$usersession->UserID,
					'UsersCategory' =>$usersession->UsersCategory,
					'UserName' =>$usersession->UserName,
					'UserEmail' =>$usersession->UserEmail
					);
				$udata['UserIDInserted']=$usersession->UserID;
				$udata['UserIDUpdated']=$usersession->UserID;
				$udata['UserIDLocked']=$usersession->UserID;
				$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
				}
	            endif;
	            
		        $this->session->set_userdata($sessiondata);
		        redirect('MyProfile');
			}

		}
		else 
		{	$user = $this->facebook->request('get', '/me?fields=id,name,email');
		print_r($user);
			//echo 'We are unable fetch your facebook information.'; exit;
			$this->facebook->login_url();
		}

		// display view

	}
function foodmartfb() {
    $str =$this->input->get('strenc');
    $arr = unserialize(urldecode($str));
   $fbid=$arr['id'];
   $fbname=$arr['FULLNAME'];
   $fbemail=$arr['EMAIL'];
   $fbgender=$arr['Gender'];
   $vcode=Randomvcode(6);
   $id=md5($fbid);
   $isverifiedphone="";
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserPassword' => $id,'UserIsActive'=>1));
	            if(count($userinfo)>0) :
				$isverifiedphone=$userinfo->Isphoneverified;
		            $sessiondata=array('UserID'=>$userinfo->UserID,'UsersCategory'=>$userinfo->UsersCategory,'UserName'=>$userinfo->UserName,'UserEmail'=>$userinfo->UserEmail);
	            else :
				$datain['UserUUID']      	   	   		   = GUID();
				$datain['UserName']       				   = $fbname;
				$datain['UserPassword']    				   = $id;
				$datain['UsersCategory']   	   			   = 2;
			    $datain['UserEmail']                       = $fbemail;
				$datain['Isphoneverified']				   =0;
				$datain['verifiedcode']  				   =$vcode;
				$datain['IsSocialUser']           	   	   = 1;
				$datain['UserIsApproved']           	   = 1;
				$datain['UserIsActive']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Foodmart_model->insert_data('tbluser', $datain);
				if($insert_ID > 0){ 
				$usersession = $this->Foodmart_model->read('UserID,UserName,UsersCategory,UserEmail,Isphoneverified', 'tbluser', array('UserID' => $insert_ID));
					$isverifiedphone=$usersession->Isphoneverified;
					$sessiondata = array(
					'UserID' =>$usersession->UserID,
					'UsersCategory' =>$usersession->UsersCategory,
					'UserName' =>$usersession->UserName,
					'UserEmail' =>$usersession->UserEmail
					);
				$udata['UserIDInserted']=$usersession->UserID;
				$udata['UserIDUpdated']=$usersession->UserID;
				$udata['UserIDLocked']=$usersession->UserID;
				$this->Foodmart_model->update_info('tbluser', $udata, 'UserID', $insert_ID);
				}
	            endif;
	            $this->session->set_userdata($sessiondata);
				if($isverifiedphone==1){
		        redirect('MyProfile');
				}
				else{
					 redirect('phoneverify');
					}
}

public function phoneverifyhome()
	{
		$currentid=$this->session->userdata('UserID');
		$this->Foodmart_model->updatedelfree();
		$pageslug=$this->uri->segment(1);
		if(empty($pageslug)){
			$pageslug="Home";
			}
		$data['seo']=$this->Foodmart_model->pageseo($pageslug);
		$data['allLocation']=$this->Foodmart_model->Allocation();
		$data['allArea']=$this->Foodmart_model->Allarea();
		$data['Contacts']=$this->Foodmart_model->Getcontact();
		if($currentid!=''){
			$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
			$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
			$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
			if(!empty($data['chatinfo'])){
			$myid = $data['chatinfo']->UserID;
			$myinbox = $data['chatinfo']->InboxID;
			$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
			$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
			foreach($data['replayf'] as $allreply){
			$senderid = $allreply->SendBy;
			$data['sender']=$this->Foodmart_model->senderf($senderid);
			}
			}
			}
		$data['content'] = $this->load->view('home2', $data, TRUE);
        $this->load->view('index', $data);
	}
	function logout(){
		$myid = $this->session->userdata('UserID');
		$udata['Isonline']='0';
		$this->db->where('UserID',$myid);
		$this->db->update('tbluser',$udata);
		if(($this->session->userdata('UsersCategory')==4) || ($this->session->userdata('UsersCategory')==6) || ($this->session->userdata('UsersCategory')==8)){
					$sql="select * from tblstaffinfo where UserID='".$myid."'";
					$query_result=  $this->db->query($sql);
					$myinfo=$query_result->row();
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
					
		$this->session->unset_userdata('UserID');
		$this->session->unset_userdata('UsersCategory');
		$this->session->unset_userdata('UserName');
		$this->session->unset_userdata('UserEmail');
		//$this->session->sess_destroy();
		header("Location: ".$this->config->base_url());
	}
	
	function page_refresh(){
		//delete_cookie('name', $domain, $path); 
		delete_cookie("LocationCK");
		delete_cookie("AreaCK");
		header("Location: ".$this->config->base_url());
		}
	public function SearchRestaurant()
	{
		$currentid=$this->session->userdata('UserID');
		$lvookieval = $this->input->post('Location');
		$lAcookieval= $this->input->post('Area');
			if(($lvookieval!='') && ($lAcookieval!='')){
				$this->input->set_cookie(array("name"=>'LocationCK', 'value'=>$lvookieval, 'expire'=>1209600));
				$this->input->set_cookie(array("name"=>'AreaCK', 'value'=>$lAcookieval, 'expire'=>1209600));
				//header("Location: ".$this->config->base_url().SearchRestaurant);
				redirect('SearchRestaurant');
			}
			/*else{
				redirect('home');
				}
			*/
	  
	  $AreaCK= $this->input->cookie('AreaCK');
	  $LocationCK= $this->input->cookie('LocationCK'); 
	  if(empty($AreaCK)){
		  redirect('home');
		  }

		$this->Foodmart_model->updatedelfree();
		$pageslug=$this->uri->segment(1);
		if(empty($pageslug)){
			$pageslug="Home";
			}
		$data['seo']=$this->Foodmart_model->pageseo($pageslug);
		$data['allLocation']=$this->Foodmart_model->Allocation();
		$data['allArea']=$this->Foodmart_model->Allarea();
		$data['Contacts']=$this->Foodmart_model->Getcontact();
		if($currentid!=''){
			$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
			$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
			$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
			$myid = $data['chatinfo']->UserID;
			$myinbox = $data['chatinfo']->InboxID;
			$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
			$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
			foreach($data['replayf'] as $allreply){
			$senderid = $allreply->SendBy;
			$data['sender']=$this->Foodmart_model->senderf($senderid);
			}
			
			}
			
		$sdata['location']   = $LocationCK;
        $sdata['area']       = $AreaCK;
		
		$data['searchres']=   $this->Foodmart_model->get_search_result($sdata);	
		$data['mostpopular'] = $this->Foodmart_model->get_popular_result($sdata);
		$data['deals'] = $this->Foodmart_model->get_deals_result($sdata);	
		$data['content'] = $this->load->view('searchrestaurant', $data, TRUE);
        $this->load->view('index', $data);
	}
	
	public function Searchresult()
	{
		$currentid=$this->session->userdata('UserID');
		$searchterm = $this->input->post('listid');
		if($searchterm=='PopularTabxxx'){
		$filterop = 'UserIsPopular';
		}
		if($searchterm=='deals'){
		$filterop = 'Deals';
		}
		if($searchterm=='showmaps'){
		$filterop = 'fastfood';
		}
	  $AreaCK= $this->input->cookie('AreaCK');
	  $LocationCK= $this->input->cookie('LocationCK'); 

			
		$sdata['location']   	   = $LocationCK;
        $sdata['area']       	   = $AreaCK;
		$sdata['filterterm']       = $filterop;
		
		$data['searchres']=   $this->Foodmart_model->get_filter_result($sdata);	
		$data['mostpopular'] = $this->Foodmart_model->get_popular_result($sdata);
		$data['deals'] = $this->Foodmart_model->get_deals_result($sdata);	
        $this->load->view('searchresult', $data);
	}
	public function maps()
	{
		$data['maps'] = $this->Foodmart_model->get_maps_result();
			
        $this->load->view('maps', $data);
	}
	public function Searchfood()
	{
		$currentid=$this->session->userdata('UserID');
		$searchterm = $this->input->post('listid');
		
		
	  $AreaCK= $this->input->cookie('AreaCK');
	  $LocationCK= $this->input->cookie('LocationCK'); 

			
		$sdata['location']   	   = $LocationCK;
        $sdata['area']       	   = $AreaCK;
		$sdata['filterterm']       = $searchterm;
		
		$data['searchres']=   $this->Foodmart_model->get_food_result($sdata);	
		$data['mostpopular'] = $this->Foodmart_model->get_popular_result($sdata);
		$data['deals'] = $this->Foodmart_model->get_deals_result($sdata);	
        $this->load->view('searchresult', $data);
	}
	public function Searchsort()
	{
		$currentid=$this->session->userdata('UserID');
		$FirstShortBy = $this->input->post('FirstShortBy');
		$PreOrder = $this->input->post('PreOrder');
		$foodname = $this->input->post('foodname');
		
		
	  $AreaCK= $this->input->cookie('AreaCK');
	  $LocationCK= $this->input->cookie('LocationCK'); 

			
		$sdata['location']   	   = $LocationCK;
        $sdata['area']       	   = $AreaCK;
		$sdata['FirstShortBy']     = $FirstShortBy;
		$sdata['PreOrder']         = $PreOrder;
		$sdata['FreeDelivery']     = $this->input->post('FreeDelivery');
		$sdata['PriyoCard']        = $this->input->post('PriyoCard');
		$sdata['Reservation']      = $this->input->post('Reservation');
		$sdata['Deals']            = $this->input->post('Deals');
		$sdata['online']            = $this->input->post('Online');
		$sdata['foodname']         = $this->input->post('foodname');
		
		$data['searchres']=   $this->Foodmart_model->get_sorting_result($sdata);	
		$data['mostpopular'] = $this->Foodmart_model->get_popular_result($sdata);
		$data['deals'] = $this->Foodmart_model->get_deals_result($sdata);	
        $this->load->view('searchresult', $data);
	}
	public function restlist()
	{
        $this->load->view('restlist');
	}
	public function restid($id)
	{
	  $getrest = $this->db->query("SELECT UserID,RestaurantName,marchantid FROM tbluser Where marchantid='".$id."'");	
	  $acc_data= $getrest->row();
	  $acc_data->marchantid;
echo json_encode($acc_data);
	}
	function addtofavorite(){
		$ResID=$this->input->post('value');
		$UserID=$this->session->userdata('UserID');
		$ifexist = $this->Foodmart_model->read('RestaurantID,UserID', 'tblfavourite', array('RestaurantID' => $ResID,'UserID' => $UserID));
		if(count($ifexist)<1){
		$fdata['RestaurantID']=$ResID;
		$fdata['UserID']=$UserID;
		$fdata['FavouriteIsActive']="1";
		$this->Foodmart_model->insert_data('tblfavourite', $fdata);
		}
		}
		
	function menu($marchandid){
		    $data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$curtdays=date('l');	
				$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hdata['hOpen']=$openclosetime->StartingTime;
					$hdata['hClose']=$openclosetime->EndingTime;
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$hdata['hOpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hdata['hCloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hdata['hOpen']="";
					$hdata['hClose']="";
					$sdata['Open']="";
					$sdata['Close']="";
					$hdata['hOpenTime'] =  "";
					$hdata['hCloseTime'] = "";
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
				$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
				$data['content'] = $this->load->view('menu', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		}
	public function openclose($id){
		$curtdays=date('l');	
				$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $id,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hOpenTime =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hCloseTime = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hOpenTime =  "";
					$hCloseTime = "";
					}
					
			if($hOpenTime < date('H:i:s') AND $hCloseTime > date('H:i:s')){
				echo "Open";
			}else{
				echo "Close";
			}
		}
	public function ViewproductDetails()
	{
        $restaurantID=$this->input->post('resid');
		$pid=$this->input->post('id');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$proinfo= $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $pid));
		$sdata['productinfo']=$proinfo;
		$restuid=$restaurantinfo->UserID;
		$currentid=$this->session->userdata('UserID');
		$AreaCK= $this->input->cookie('AreaCK');
		$LocationCK= $this->input->cookie('LocationCK');
		$sdata['location']   	   = $LocationCK;
		$sdata['area']       	   = $AreaCK;
		$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restaurantID));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						}
					else{
						$sdata['matcharea']     = 0;
						}
					}
		$curtdays=date('l');
		$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
		
		$this->load->view('quickview',$sdata);
	}
	function reviewCustomer($marchandid){
		$data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $usercategory=$this->session->userdata('UsersCategory');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$data['Contacts']=$this->Foodmart_model->Getcontact();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();

			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			$sdata['UserCategory']     =$usercategory;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				
				$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$curtdays=date('l');	
				$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hdata['hOpen']=$openclosetime->StartingTime;
					$hdata['hClose']=$openclosetime->EndingTime;
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$hdata['hOpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hdata['hCloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hdata['hOpen']="";
					$hdata['hClose']="";
					$sdata['Open']="";
					$sdata['Close']="";
					$hdata['hOpenTime'] =  "";
					$hdata['hCloseTime'] = "";
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
				$this->form_validation->set_rules('Price','Price', 'required|trim|xss_clean');
				$this->form_validation->set_rules('Taste','Taste', 'required|trim|xss_clean');
				$this->form_validation->set_rules('Quality','Quality', 'required|trim|xss_clean');
				$this->form_validation->set_rules('Service','Service', 'required|trim|xss_clean');
					if($this->form_validation->run()==FALSE)
					{
						$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
						$data['content'] = $this->load->view('customer-review', $sdata, TRUE);
						$this->load->view('index', $data);
					}
					else{
					$datain['ReviewsUUID']      	   	  = GUID();
					$datain['RestaurantID']       		  = $restuid;
					$datain['UserID']    				  = $currentid;
					$datain['Price']   	   			      = $this->input->post('Price');
					$datain['Taste']                      = $this->input->post('Taste');
					$datain['Quality']   	      		  = $this->input->post('Quality');
					$datain['Service']           	   	  = $this->input->post('Service');
					$datain['CombReview']           	  = $this->input->post('CombReview');
					$datain['ReviewsIsActive']            = 0;
					$datain['UserIDInserted']             = $currentid;
					$datain['UserIDUpdated']              = $currentid;
					$datain['UserIDLocked']               = $currentid;
					$datain['DateInserted']   	   		  = date('Y-m-d H:i:s');
					$datain['DateUpdated']   	   		  = date('Y-m-d H:i:s');
					$datain['DateLocked']   	   		  = date('Y-m-d H:i:s');
					$insert_ID = $this->Foodmart_model->insert_data('tblreviews', $datain);
					$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
					$data['content'] = $this->load->view('customer-review', $sdata, TRUE);
					$this->load->view('index', $data);
					}
				}
		
		}
	function information($marchandid){
		$data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$data['Contacts']=$this->Foodmart_model->Getcontact();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();

			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				$location = $this->Foodmart_model->read('*', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$curtdays=date('l');	
				$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hdata['hOpen']=$openclosetime->StartingTime;
					$hdata['hClose']=$openclosetime->EndingTime;
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$hdata['hOpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hdata['hCloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hdata['hOpen']="";
					$hdata['hClose']="";
					$sdata['Open']="";
					$sdata['Close']="";
					$hdata['hOpenTime'] =  "";
					$hdata['hCloseTime'] = "";
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
				$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
				$data['content'] = $this->load->view('InformationPage', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
		
		}
	function reservation($marchandid){
		$data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$data['Contacts']=$this->Foodmart_model->Getcontact();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();

			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$sdata['reserve'] = $restaurantinfo->AllowReservation;
				$hdata['RID']     = $restuid;
				$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$curtdays=date('l');	
			$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hdata['hOpen']=$openclosetime->StartingTime;
					$hdata['hClose']=$openclosetime->EndingTime;
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$hdata['hOpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hdata['hCloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hdata['hOpen']="";
					$hdata['hClose']="";
					$sdata['Open']="";
					$sdata['Close']="";
					$hdata['hOpenTime'] =  "";
					$hdata['hCloseTime'] = "";
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					
					}
				$this->form_validation->set_rules('Name','Name', 'required|trim|xss_clean');
				$this->form_validation->set_rules('email','Email', 'valid_email|required|trim|xss_clean');
				$this->form_validation->set_rules('phone','phone', 'required|trim|xss_clean');
				$this->form_validation->set_rules('Date','Date', 'required|trim|xss_clean');
				$this->form_validation->set_rules('bookingtime','Booking Time', 'required|trim|xss_clean');
				$this->form_validation->set_rules('EventType','Event Type', 'required|trim|xss_clean');
				$this->form_validation->set_rules('TotalPerson','Person', 'required|trim|xss_clean');
				$this->form_validation->set_rules('Foods','Foods', 'required|trim|xss_clean');
					if($this->form_validation->run()==FALSE)
					{
						$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
						$data['content'] = $this->load->view('Reservation', $sdata, TRUE);
						$this->load->view('index', $data);
					}
					else{
					$dateevent =date('Y-m-d',strtotime($this->input->post('Date')));
					$datain['ReservationrequestUUID']      	= GUID();
					$datain['DateData']       		  		= $dateevent;
					$datain['eventtime']   	   			    = $this->input->post('bookingtime');
					$datain['EventType']   	   			    = $this->input->post('EventType');
					$datain['TotalPeople']                  = $this->input->post('TotalPerson');
					$datain['Foods']   	      				= $this->input->post('Foods');
					$datain['OrderType']           	   	  	= "Booking";
					$datain['Additionalrequirements']       = $this->input->post('AdditionalRequirements');
					$datain['UserID']            			= $currentid;
					$datain['email']            			= $this->input->post('email');
					$datain['phone']            			= $this->input->post('phone');
					$datain['incoming']						= "1";
					$datain['RestaurantID']            		= $restuid;
					$datain['UserIDInserted']             	= $currentid;
					$datain['UserIDUpdated']              	= $currentid;
					$datain['UserIDLocked']               	= $currentid;
					$datain['DateInserted']   	   		  	= date('Y-m-d H:i:s');
					$datain['DateUpdated']   	   		  	= date('Y-m-d H:i:s');
					$datain['DateLocked']   	   		  	= date('Y-m-d H:i:s');
					$insert_ID = $this->Foodmart_model->insert_data('tblreservationrequest', $datain);
					$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
					$data['content'] = $this->load->view('Reservation', $sdata, TRUE);
					$this->load->view('index', $data);
					}
				}
		
		}
	function PriyoCardOffers($marchandid){
		$data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['seo']=$this->Foodmart_model->pageseo($pageslug);
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$data['Contacts']=$this->Foodmart_model->Getcontact();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();

			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$curtdays=date('l');	
			$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$hdata['hOpen']=$openclosetime->StartingTime;
					$hdata['hClose']=$openclosetime->EndingTime;
					$sdata['Open']=$openclosetime->StartingTime;
					$sdata['Close']=$openclosetime->EndingTime;
					$hdata['hOpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$hdata['hCloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$hdata['hOpen']="";
					$hdata['hClose']="";
					$sdata['Open']="";
					$sdata['Close']="";
					$hdata['hOpenTime'] =  "";
					$hdata['hCloseTime'] = "";
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
				$sdata['commonsection']=$this->load->view('CommonHead', $hdata,TRUE);
				$data['content'] = $this->load->view('PriyoCardOffers', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
		
		}
	function addtocart(){
		$currentid=$this->session->userdata('UserID');
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
			$discountpercent=(int)$restaurantinfo->Discount;
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
		/*$totaltoping='';
		$totaltoping2='';
		if(!empty($maxtoping)){
		for($top1=0;$top1<=$maxtoping;$top1++){
			$totaltoping.=$gettoping[$top1].",";
			}
		$totaltoping=rtrim($totaltoping,",");
		}
		if(!empty($maxtoping2)){
		for($top2=0;$top2<=$maxtoping2;$top2++){
			$totaltoping2.=$gettoping2[$top2].",";
			}
		$totaltoping2=rtrim($totaltoping2,",");
		}*/
		$getaddons=explode(",",$addonslist);
		//print @$a['k3']? "OK $a[k3].": 'NO VALUE.'; 
		
	   
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
			//}
			$sdata['Restaurant']=$restaurantinfo;
			$sdata['loginid']   =$currentid;
			$this->load->view('cartdata',$sdata);

		}
	function updatecart(){
		$currentid=$this->session->userdata('UserID');
		$cartID=$this->input->post('CartID');
		$productqty=$this->input->post('qty');
		$Udstatus=$this->input->post('Udstatus');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['loginid']   =$currentid;
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
			$this->load->view('cartdata',$sdata);
		}
	function deleteItem(){
		$currentid=$this->session->userdata('UserID');
		$cartID=$this->input->post('CartID');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['loginid']   =$currentid;
		$data = array(
				'rowid'   => $cartID,
				'qty'     => 0
			);
		$this->cart->update($data);
		$this->load->view('cartdata',$sdata);
	}
	function cartclear(){
		$currentid=$this->session->userdata('UserID');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['loginid']   =$currentid;
		$this->cart->destroy();
		$this->load->view('cartdata',$sdata);
	}
	function popupdatecart(){
		$cartID=$this->input->post('CartID');
		$productqty=$this->input->post('qty');
		$Udstatus=$this->input->post('Udstatus');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['loginid']   =$currentid;
		$sdata['SessionUser'] = $this->session->userdata('UserID');
		$sdata['SessionCat']=$this->session->userdata('UsersCategory');
		if(($Udstatus=="del") && ($productqty>1)){
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
			$this->load->view('cartprocess',$sdata);
		}
	function popdeleteItem(){
		$cartID=$this->input->post('CartID');
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['SessionUser'] = $this->session->userdata('UserID');
		$sdata['SessionCat']=$this->session->userdata('UsersCategory');
		$data = array(
				'rowid'   => $cartID,
				'qty'     => 0
			);
		$this->cart->update($data);
		$this->load->view('cartprocess',$sdata);
	}
	function viewcart(){
		$restaurantID=$this->input->post('RID');
		$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
		$sdata['Restaurant']=$restaurantinfo;
		$sdata['SessionUser'] = $this->session->userdata('UserID');
		$sdata['SessionCat']=$this->session->userdata('UsersCategory');
		$this->load->view('cartprocess',$sdata);
	}
	function delivaryoption($marchandid){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
			 $data['content']="";
			 $currentid=$this->session->userdata('UserID');
			 $sdata['SessionCat']=$this->session->userdata('UsersCategory');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $currentid,'UserIsActive'=>1));
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userdata']=$userinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				$getlatlong = $this->Foodmart_model->read('Latitude,Longitude', 'tbllocation', array('RestaurantID' => $restuid));
				$sdata['longitude']=$getlatlong->Longitude;
				$sdata['latitude']=$getlatlong->Latitude;
				$curtdays=date('l');
			$openclosetime = $this->Foodmart_model->read('StartingTime,EndingTime', 'tbltakeaway', array('RestaurantID' => $restuid,'DayName'=>$curtdays));
				if(count($openclosetime)>0){
					$sdata['OpenTime'] =  date('H:i:s',strtotime($openclosetime->StartingTime));
					$sdata['CloseTime'] = date('H:i:s',strtotime($openclosetime->EndingTime));
					}
				else{
					$sdata['OpenTime'] =  "";
					$sdata['CloseTime'] = "";
					}
				$data['content'] = $this->load->view('delivaryoption', $sdata, TRUE);
				$this->load->view('index', $data);
		}
		  }
		  }
	function CheckOut($marchandid){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
			 $data['content']="";
			 if($this->input->post('delivery')==''){
				 $shippingmethod=$this->session->userdata('Deliverymethod');
				 }
			 else{
				  $shippingmethod=$this->input->post('delivery');
				 }
			 if($this->input->post('delivarytype')==''){
				 $deliverytype=$this->session->userdata('DeliveryType');
				 }
			 else{
				   $deliverytype=$this->input->post('delivarytype');
				 }
			 if($this->input->post('deldate')==''){
				 $deliverydate=$this->session->userdata('Deliverydate');
				 }
			 else{
				   $deliverydate=$this->input->post('deldate');
				 }
			 if($this->input->post('deltime')==''){
				 $deliverytime=$this->session->userdata('Deliverytime');
				 }
			 else{
				   $deliverytime=$this->input->post('deltime');
				 }
			if($this->input->method() === 'post'){
			 $sessiondata = array('Deliverymethod'=>$shippingmethod,'DeliveryType'=>$deliverytype,'Deliverydate'=>$deliverydate,'Deliverytime'=>$deliverytime);
			 $this->session->set_userdata($sessiondata);
			}
			 $sdata['Deliverymethod']=$this->session->userdata('Deliverymethod');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 

			 $sdata['allArea']=$this->Foodmart_model->Allarea($LocationCK);
			 $currentid=$this->session->userdata('UserID');
			 $sdata['SessionCat']=$this->session->userdata('UsersCategory');
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
			 
			 
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $currentid,'UserIsActive'=>1));
			//print_r($userinfo);
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userdata']=$userinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				/*$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $AreaCK) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$AreaCK.'';
						}
					}
				else{
					$hdata['notetext']= "";
					}*/
				$data['content'] = $this->load->view('CheckOut', $sdata, TRUE);
				$this->load->view('index', $data);
			}
			}
		}
	function CheckOutconfirm($marchandid){
		if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
			 $currentid=$this->session->userdata('UserID');
			 $sdata['SessionCat']=$this->session->userdata('UsersCategory');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$hdata['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			$hdata['allLocation']=$this->Foodmart_model->Allocation();
			$hdata['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
			$sdata['allArea']=$this->Foodmart_model->Allarea();
			$sdata['location']   	   = $LocationCK;
			$sdata['area']       	   = $AreaCK;
			$hdata['Hlocation']   	   = $LocationCK;
			$hdata['Harea']       	   = $AreaCK;
			$hdata['loginid']          =$currentid;
			$sdata['userid']           =$currentid;
			
			$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('marchantid' => $marchandid,'UserIsApproved'=>1,'UserIsActive'=>1));
			$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $currentid,'UserIsActive'=>1));
			//print_r($userinfo);
			$notexistrest = count($restaurantinfo);
			if($notexistrest<1){
				header("Location: ".$this->config->base_url());
			}
			else{
				$hdata['Restaurant']=$restaurantinfo;
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userdata']=$userinfo;
				$restuid=$restaurantinfo->UserID;
				$sdata['ID']     = $restuid;
				$hdata['RID']     = $restuid;
				$sdata['userid']           =$currentid;
				$sdata['Deliverymethod']=$this->session->userdata('Deliverymethod');
				$SessionCat=$this->session->userdata('UsersCategory');
				$UserEmail=$this->session->userdata('UserEmail');
				$postuseremail=$this->input->post('UserEmail');
				if($postuseremail==""){
					$emailadd=$this->input->post('UserEmail');
					}
				else{
					$emailadd=$postuseremail;
					}
				if($this->input->post('Address')==''){
				 $deliveryaddress=$this->session->userdata('DeliveryAddress');
				 }
			 else{
				  $deliveryaddress=$this->input->post('Address');
				 }
				//$deliveryaddress=$this->input->post('Address');
				 if($this->input->post('delArea')==''){
				 $Deliveryarea=$this->session->userdata('Deliveryarea');
				 }
			 else{
				   $Deliveryarea=$this->input->post('delArea');
				 }
				 if($this->input->post('Instruction')==''){
				 $instruction=$this->session->userdata('Instruction');
				 }
			 else{
				   $instruction=$this->input->post('Instruction');
				 }
				if($this->input->method() === 'post'){
				$phone=$this->input->post('PhoneNumber');
				$mobile=$this->input->post('PhoneMobile');
				$dataup['Address']    = $this->input->post('Address');
				$dataup['PhoneMobile']    = $mobile;
				$dataup['PhoneNumber']    = $phone;
				$dataup['DeliveryAddress']    = $deliveryaddress;
				$dataup['UserEmail']    = $emailadd;
				$this->Foodmart_model->update_info('tbluser', $dataup, 'UserID', $currentid);
				$sessiondata = array('DeliveryAddress' =>$deliveryaddress,'Deliveryarea' =>$Deliveryarea,'Instruction'=>$instruction);
			    $this->session->set_userdata($sessiondata);
				}
				 //print_r($this->session->userdata());
				//print_r($sessiondata);
				$location = $this->Foodmart_model->read('Address', 'tbllocation', array('RestaurantID' => $restuid));
				if(count($location)>0){
					$resArea = $location->Address;
					if (strpos($resArea, $Deliveryarea) !== false) {
						$sdata['matcharea']     = 1;
						$hdata['notetext']= "";
						}
					else{
						$sdata['matcharea']     = 0;
						$hdata['notetext'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$Deliveryarea.'';
						$errdata['nomatchmessage'] = 'Sorry!! This Restaurant Doesn\'t Deliver to  '.$Deliveryarea.'';
						$this->session->set_userdata($errdata);
						redirect('CheckOut/'.$marchandid);
						}
					}
				else{
					$hdata['notetext']= "";
					}
				$data['content'] = $this->load->view('CheckOut_final', $sdata, TRUE);
				$this->load->view('index', $data);
			 }
			}
		}
	function UserData(){
		if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['userid']           =$currentid;
				$SessionCat=$this->session->userdata('UsersCategory');
				$UserEmail=$this->session->userdata('UserEmail');
				$postuseremail=$this->input->post('UserEmail');
				if($postuseremail==""){
					$emailadd=$this->input->post('UserEmail');
					}
				else{
					$emailadd=$postuseremail;
					}
				if(!empty($this->input->post('DAddress'))){
				$deliveryaddress=$this->input->post('DAddress');
				}else{
				$deliveryaddress=$this->input->post('Address');
				}
				$instruction=$this->input->post('Instruction');
				$phone=$this->input->post('PhoneNumber');
				$mobile=$this->input->post('PhoneMobile');
				$dataup['Address']    = $this->input->post('Address');
				$dataup['PhoneMobile']    = $mobile;
				$dataup['PhoneNumber']    = $phone;
				$dataup['DeliveryAddress']    = $deliveryaddress;
				$dataup['UserEmail']    = $emailadd;
				$this->Foodmart_model->update_info('tbluser', $dataup, 'UserID', $currentid);
				$sessiondata = array('DeliveryAddress' =>$deliveryaddress,'Instruction'=>$instruction);
			    $this->session->set_userdata($sessiondata);
			}
		}
		function promocode(){
		if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['userid']           =$currentid;
				$SessionCat=$this->session->userdata('UsersCategory');
				$UserEmail=$this->session->userdata('UserEmail');
				$restaurantID=$this->input->post('RID');
				$promocode=$this->input->post('promocode');
				$subtotal=$this->cart->total();
				if($promocode==''){
				echo " ";
				}else{
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$data['Restaurant']=$restaurantinfo;	
				$data['Deliverymethod']=$this->session->userdata('Deliverymethod');
				$promoinfo= $this->Foodmart_model->read('*', 'tblcoupon', array('CouponCode' => $promocode,'CouponIsActive'=>1));
				//print_r($promoinfo);	
					if(count($promoinfo)>0){
						$price='';
					$cudate=date('Y-m-d');
			if(($promoinfo->prostartdate!="0000-00-00") && ($cudate>=$promoinfo->prostartdate) && ($cudate<=$promoinfo->proendate)){
				if($promoinfo->couponcodetype=="One time"){
					if($promoinfo->codeisexpired=="0"){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}
					}
					else{
					 $price='';
					}
				}
				else{
			if($promoinfo->reataurant==$restaurantID){
				if($promoinfo->ispercentortk=="Tk"){
				$getpercent=($promoinfo->Price/$subtotal)*100;
				$price=number_format($getpercent,2);
				}
				else{
					$price=$promoinfo->Price;
					}
			}
			else if($promoinfo->reataurant==0){
				if($promoinfo->ispercentortk=="Tk"){
				$getpercent=($promoinfo->Price/$subtotal)*100;
				$price=number_format($getpercent,2);
				}
				else{
					$price=$promoinfo->Price;
					}

			}
			}
				}
			else if($promoinfo->promotime!=""){
				$promotime = $promoinfo->promotime;
				$gettime=explode('-',$promotime);
				$opentime = $gettime[0];
				$closetime = $gettime[1];
				$newTime = date("h:i A",strtotime($opentime));
				$newcloseTime = date("h:i A",strtotime($closetime));
				$actualtime=date('h:i A');
				$sortactualtime = strtotime($actualtime);
				$sortopen = strtotime($newTime);
				$sortclose = strtotime($newcloseTime);
				if(($sortactualtime >= $sortopen) && ($sortactualtime<$sortclose)){
						if($promoinfo->couponcodetype=="One time"){
					if($promoinfo->codeisexpired=="0"){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}
					}
					else{
					 echo "";
					}
				}
						else{
			if($promoinfo->reataurant==$restaurantID){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}

				}
			else if($promoinfo->reataurant==0){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}

			}
			}
					}
					else{
						echo "";
						}
				}
			else if(($promoinfo->promotime=='') && ($promoinfo->prostartdate=="0000-00-00")){
			if($promoinfo->couponcodetype=="One time"){
					if($promoinfo->codeisexpired=="0"){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}
					}
					else{
					 echo "";
					}
				}
			else{
			if($promoinfo->reataurant==$restaurantID){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}

			}
			else if($promoinfo->reataurant==0){
						if($promoinfo->ispercentortk=="Tk"){
						$getpercent=($promoinfo->Price/$subtotal)*100;
						$price=number_format($getpercent,2);
						}
						else{
							$price=$promoinfo->Price;
							}
			}
			}
			}
					
					$sessiondata = array('promo' =>$price);
			    	$this->session->set_userdata($sessiondata);
					$data['promo']=$price;
					$data['promocode']=$promoinfo->CouponCode;
					$this->load->view('PromoCode', $data);
					}
					else{
						echo " ";
						}
				}
			}
		}
		function placeorder(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
			$currentid=$this->session->userdata('UserID');
			 $AreaCK= $this->input->cookie('AreaCK');
			 $LocationCK= $this->input->cookie('LocationCK'); 
			 $pageslug=$this->uri->segment(1);
			if(empty($pageslug)){
				$pageslug="Home";
				}
			$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
			$data['allLocation']=$this->Foodmart_model->Allocation();
			$data['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
				$restaurantID=$this->input->post('RID');
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userinfo']=$userinfo;
				 $Deliverymethod=$this->session->userdata('Deliverymethod');
				 $DeliveryAddress=$this->session->userdata('DeliveryAddress');
				 
				 $DeliveryType=$this->session->userdata('DeliveryType');
				 $Deliverydate=$this->session->userdata('Deliverydate');
				 $Deliverytime=$this->session->userdata('Deliverytime');
				 $foodinstruction=$this->input->post('foodinstruction');
				 if(!empty($Deliverydate)){
				$date_array = explode("/",$Deliverydate); // split the array
				$var_month = $date_array[0]; //Month seqment
				$var_day = $date_array[1]; //Day segment
				$var_year = $date_array[2]; //year segment
				$new_date_format = $var_year."-".$var_month."-".$var_day;
				 }
				 else{
					 $new_date_format ="0000-00-00";
					 }
				 
				 $Instruction=$this->session->userdata('Instruction');
				 $paymentmethod=$this->input->post('payment_method');
				 if($Deliverymethod==""){
					 $Deliverymethod=1;
				 }
				 if($this->input->post('Offer')==''){
					$Offer=''; 
				 }
				 else{
				 $Offer=$this->input->post('Offer');
				 }
				 $subtotal = $this->cart->total();
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
				if($Offer=="Restaurant"){
					$discount=$subtotal*$restaurantinfo->Discount/100;
					$discountpercent=$restaurantinfo->Discount;
					$promocode="NULL";
					}
				else if($Offer=="PromoDiscount"){
					$offercode=base64_decode($this->input->post('offercode'));
					$promocode=base64_decode($this->input->post('testinod'));
					$discount=$subtotal*$offercode/100;
					$discountpercent=$offercode;
					}
				else{
					$discount="0";
					$offercode="";
					$discountpercent="";
					$promocode="NULL";
					}
			
			$couponinfo =$this->Foodmart_model->read('*', 'tblcoupon', array('CouponCode' => $promocode));
			if(!empty($couponinfo)){
				if($couponinfo->couponcodetype='One time'){
				$promoupdate['codeisexpired']	 = "1";
				$this->Foodmart_model->update_info('tblcoupon', $promoupdate, 'CouponID', $couponinfo->CouponID);
				}
			}
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
					 $datatorder['UserIDInserted'] 		= $currentid;
					 $datatorder['UserIDUpdated']  		= $currentid;
					 $datatorder['UserIDLocked']   		= $currentid;
					 $datatorder['DateInserted'] 		= date('Y-m-d H:i:s');
					 $datatorder['DateUpdated']  		= date('Y-m-d H:i:s');
					 $datatorder['DateLocked']   		= date('Y-m-d H:i:s');
					 $datatorder['IsAppOrder']   		= "0";
					 $lastinserid=$this->Foodmart_model->insert_data('tblorder', $datatorder);
					 $datatocartupdate['OrderID']      = $lastinserid;
					 $datatocartupdate['CartIsActive'] = "0";
					 $this->Foodmart_model->update_info('tblcart', $datatocartupdate, 'Session', $session);
					 $sdata['complete']=0;
					 if((!empty($restaurantinfo->alertnumber)) && ($restaurantinfo->isalertenable==1)){
					 SendSMS('88'.$restaurantinfo->alertnumber,
				$SMS ="- Great news: You've received an order from {$userinfo->UserName} Please contact with customer and start cooking. visit- Foodmart.com.bd/admin");
					 }
					 if($paymentmethod=="1"){
					$sdata['complete']=1;
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
						/*SendMail(
							$ToEmail=$this->session->userdata('UserEmail'),
							$Subject="Food order Information",
							$Body="".GetOrderTableForEmail($lastinserid)."",
							$FromName="Foodmart.com.bd",
							$FromEmail = "order@foodmart.com.bd",
							$ReplyToName="Foodmart.com.bd",
							$ReplyToEmail="order@foodmart.com.bd",
							$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
						);*/
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
						/*SendMail(
							$ToEmail=$this->session->userdata('UserEmail'),
							$Subject="Food order Information",
							$Body="".GetOrderTableForEmail($lastinserid)."",
							$FromName="Foodmart.com.bd",
							$FromEmail = "order@foodmart.com.bd",
							$ReplyToName="Foodmart.com.bd",
							$ReplyToEmail="order@foodmart.com.bd",
							$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
						);*/
					}
					SendSMS('88'.$userinfo->PhoneMobile,
				$SMS ="Dear Sir/Madam, Thank you for order foodmart Your order has been placed. Your order ID is {$lastinserid} Foodmart support team will call you soon. 01793111333");
				   
					}
					 else if($paymentmethod=="2"){
						 $sdata['complete']=0;
						 }
					 else if($paymentmethod=="3"){
						 $sdata['complete']=2;
						 
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
						/*SendMail(
							$ToEmail=$this->session->userdata('UserEmail'),
							$Subject="Food order Information",
							$Body="".GetOrderTableForEmail($lastinserid)."",
							$FromName="Foodmart.com.bd",
							$FromEmail = "order@foodmart.com.bd",
							$ReplyToName="Foodmart.com.bd",
							$ReplyToEmail="order@foodmart.com.bd",
							$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
						);*/
					}
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
						/*SendMail(
							$ToEmail=$this->session->userdata('UserEmail'),
							$Subject="Food order Information",
							$Body="".GetOrderTableForEmail($lastinserid)."",
							$FromName="Foodmart.com.bd",
							$FromEmail = "order@foodmart.com.bd",
							$ReplyToName="Foodmart.com.bd",
							$ReplyToEmail="order@foodmart.com.bd",
							$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
						);*/
					}
					SendSMS('88'.$userinfo->PhoneMobile,
				$SMS ="Dear Sir/Madam, Thank you for order foodmart Your order has been placed. Your order ID is {$lastinserid} Foodmart support team will call you soon. 01793111333");
						 }
					    $this->session->unset_userdata('Deliverymethod');
						$this->session->unset_userdata('promo');
						$this->session->unset_userdata('DeliveryType');
						$this->session->unset_userdata('Deliverydate');
						$this->session->unset_userdata('Deliverytime');
						$this->cart->destroy();
						
					 $sdata['OrderID']=$lastinserid;
					 redirect('ConfirmOrder/'.$lastinserid);
					 //$data['content'] = $this->load->view('ordersubmit', $sdata, TRUE);
					 //$this->load->view('index', $data);
				}
		}
	function OrderConfirm($id){
	 $currentid=$this->session->userdata('UserID');
	 $AreaCK= $this->input->cookie('AreaCK');
	 $LocationCK= $this->input->cookie('LocationCK'); 
	 $pageslug=$this->uri->segment(1);
	 if(empty($pageslug)){
		$pageslug="Home";
		}
	$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
	$data['allLocation']=$this->Foodmart_model->Allocation();
	$data['allArea']=$this->Foodmart_model->Allarea();
	if($currentid!=''){
		$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
		$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
		$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
		$myid = $data['chatinfo']->UserID;
		$myinbox = $data['chatinfo']->InboxID;
		$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
		$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
		foreach($data['replayf'] as $allreply){
			$senderid = $allreply->SendBy;
			$data['sender']=$this->Foodmart_model->senderf($senderid);
			}
		}
	//echo "Hello".$id;
	$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
	//print_r($orderinfo);
	$restaurantID=$orderinfo->RestaurantID;
	$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
	$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
	 $sdata['complete']=0;
	 if($orderinfo->PaymentMethod=="1"){
		$sdata['complete']=1;
	 }
	 else if($orderinfo->PaymentMethod=="2"){
		$sdata['complete']=0;
		}
	else if($orderinfo->PaymentMethod=="3"){
		$sdata['complete']=2;
		}
	$sdata['Restaurant']=$restaurantinfo;
	$sdata['userinfo']=$userinfo;
	$sdata['OrderID']=$id;
	$sdata['Orderinfo']=$orderinfo;
	
	$sdata['userID']=$currentid;
	$data['content'] = $this->load->view('ordersubmit', $sdata, TRUE);
	$this->load->view('index', $data);
	}
    function Bkashpayment($id){
	 $currentid=$this->session->userdata('UserID');
	 $AreaCK= $this->input->cookie('AreaCK');
	 $LocationCK= $this->input->cookie('LocationCK'); 
	 $pageslug=$this->uri->segment(1);
	 if(empty($pageslug)){
		$pageslug="Home";
		}
	$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
	$data['allLocation']=$this->Foodmart_model->Allocation();
	$data['allArea']=$this->Foodmart_model->Allarea();
	if($currentid!=''){
		$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
		$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
		$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
		$myid = $data['chatinfo']->UserID;
		$myinbox = $data['chatinfo']->InboxID;
		$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
		$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
		foreach($data['replayf'] as $allreply){
			$senderid = $allreply->SendBy;
			$data['sender']=$this->Foodmart_model->senderf($senderid);
			}
		}
	//echo "Hello".$id;
	$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
	//print_r($orderinfo);
	$restaurantID=$orderinfo->RestaurantID;
	$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
	$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
	
	$sdata['Restaurant']=$restaurantinfo;
	$sdata['userinfo']=$userinfo;
	$sdata['OrderID']=$id;
	$sdata['Orderinfo']=$orderinfo;
	
	$sdata['userID']=$currentid;
	$sdata['complete']=1;
	$data['content'] = $this->load->view('ordersubmit', $sdata, TRUE);
	$this->load->view('index', $data);
	}
		function payment($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
				$restaurantID=$orderinfo->RestaurantID;
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userinfo']=$userinfo;
				$sdata['OrderID']=$id;
				$sdata['Orderinfo']=$orderinfo;
				$this->load->view('Payment', $sdata);
				}
			}
		function success($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
			if($currentid!=''){
				$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
				$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
				$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
				$myid = $data['chatinfo']->UserID;
				$myinbox = $data['chatinfo']->InboxID;
				$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
				$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
				foreach($data['replayf'] as $allreply){
				$senderid = $allreply->SendBy;
				$data['sender']=$this->Foodmart_model->senderf($senderid);
				}
				}
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
				$restaurantID=$orderinfo->RestaurantID;
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userinfo']=$userinfo;
				$sdata['OrderID']=$id;
				$sdata['Orderinfo']=$orderinfo;
				$datatoordupdate['PaymentStatus']    = "1";
				$datatocartupdate['OrderStatus']	 = "Pending";
			    $this->Foodmart_model->update_info('tblorder', $datatoordupdate, 'OrderID', $id);
				
				$cartinfo= $this->Foodmart_model->read_all('*', 'tblcart', array('OrderID' => $id));
				
				        $ToEmail=$this->session->userdata('UserEmail');
						$htmlContent=GetOrderTableForEmail($id);
		                $config['mailtype'] = 'html';
                        $this->email->initialize($config);
                        $this->email->to($ToEmail);
                        $this->email->from('order@foodmart.com.bd','Foodmart');
                        $this->email->subject('Food order Information');
                        $this->email->message($htmlContent);
                        $this->email->send();
						
				SendSMS('88'.$userinfo->PhoneMobile,
				$SMS ="Dear Sir/Madam, Thank you for order foodmart Your order has been placed. Your order ID is {$id} Foodmart support team will call you soon. 01793111333");
				$sdata['complete']=1;
				$data['content'] = $this->load->view('ordersubmit', $sdata, TRUE);
			    $this->load->view('index', $data);
				}
			}
		function fail($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
				$restaurantID=$orderinfo->RestaurantID;
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				 SendSMS('88'.$userinfo->PhoneMobile,
				$SMS ="Dear Sir/Madam, Your order has not been placed. Your order ID is {$id} Foodmart support team will call you soon. 01793111333");
				 echo "<script>
                        alert('Payment failed, please try again!!!');
						window.location.href='".$this->config->base_url()."';
                     </script>";
				
				//header("Location: ".$this->config->base_url()."CheckOut/".$restaurantinfo->marchantid);
				}
			}
		function invoicedownload_pdf($id){
				$htmlContent=GetOrderTableForpdf($id);
				//echo $htmlContent;
				$pdfFilePath = "invoice.pdf";
				$this->load->library('m_pdf');

				$param = '"en-GB-x","A4","","",10,10,10,10,6,3';
				$pdfer = new mPDF($param);
				$pdfer->WriteHTML($htmlContent);
				//$pdfer->output();
				$pdfer->output($pdfFilePath, "D");
				//$mpdf->Output('filename.pdf','F');
				//then attach the pdf file to send over email
		}
		function CreateImage($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $id));
				$restaurantID=$orderinfo->RestaurantID;
				$restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $restaurantID,'UserIsApproved'=>1,'UserIsActive'=>1));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['Restaurant']=$restaurantinfo;
				$sdata['userinfo']=$userinfo;
				$sdata['OrderID']=$id;
				$this->load->view('CreateImage', $sdata);
				}
			}
		function myprofile(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MyProfile', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function updateprofile(){
				if($this->session->userdata('UserID')== FALSE)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
					 $currentid=$this->session->userdata('UserID');
					 $userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
					$this->load->library('upload');
					$config['upload_path'] = './upload/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size'] = '30000';
					$config['image_width']= '4000';
					$config['image_height']= '4000';
					$this->upload->initialize($config);
					
					 $data=array();
					 $dateofbirth=$this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day');
					 $dataupdate['UserPicture']     = $this->input->post('UserPicture');
					 $dataupdate['UserName'] 		= $this->input->post('UserName');
					 $dataupdate['UserEmail']      	= $this->input->post('UserEmail');
					 $dataupdate['PhoneNumber'] 	= $this->input->post('PhoneNumber');
					 $dataupdate['PhoneMobile']     = $this->input->post('PhoneMobile');
					 $dataupdate['Address'] 		= $this->input->post('Address');
					 $dataupdate['DeliveryAddress'] = $this->input->post('DeliveryAddress');
					 $dataupdate['City'] 			= $this->input->post('City');
					 $dataupdate['Area']      		= $this->input->post('MyArea');
					 $dataupdate['ZIP'] 			= $this->input->post('MyZip');
					 $dataupdate['DateBorn'] 		= $dateofbirth; 
					 $dataupdate['Organization']    = $this->input->post('Organization');
					 $dataupdate['Designation']		= $this->input->post('Designation');
					 $dataupdate['Websitee']      	= $this->input->post('Websitee');
					 $dataupdate['NationalID'] 		= $this->input->post('NationalID');
					 $dataupdate['Attachment']      = $this->input->post('Attachment');
					 $dataupdate['DateUpdated']     = date('Y-m-d H:i:s');
					 
					
					 $pic0 = $this->upload->do_upload('UserPicture');
					 $image1 = $this->upload->data();
						if($pic0 !=''){
						
						if($userinfo->UserPicture){
									unlink("upload/$userinfo->UserPicture");
						}
						$config['image_library'] = 'gd2';
						$config['source_image'] = $this->upload->upload_path.$image1['file_name'];
						$config['new_image'] = 'upload/'.$image1['file_name'];
						$config['maintain_ratio'] = FALSE;
						$config['width'] = 268;
						$config['height'] = 249;
						$this->load->library('image_lib', $config);
						$this->image_lib->resize();
						$dataupdate['UserPicture'] = $image1['file_name'];
						}else{
							$dataupdate['UserPicture'] = $userinfo->UserPicture;
						}
						$pic1 = $this->upload->do_upload('Attachment');
						$image2 = $this->upload->data();
						if($pic1 !=''){
							if($userinfo->Attachment){
										unlink("upload/$userinfo->Attachment");
							}
							$dataupdate['Attachment'] = $image2['file_name'];
						}else{
							$dataupdate['Attachment'] = $userinfo->Attachment;
						}
					 
					 $this->Foodmart_model->update_info('tbluser', $dataupdate, 'UserID', $currentid);
					}
			}
		
		function changepassword(){
				if($this->session->userdata('UserID')== FALSE)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
					 $currentid=$this->session->userdata('UserID');
					 $userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
					 $oldPass=$this->input->post('OldPassword');
					 $newPass=$this->input->post('NewPassword');
					 $aouth= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserPassword'=>md5($oldPass)));
					 if(count($aouth)>0){
					 $passupdate['UserPassword'] 	= md5($newPass);
					 $this->Foodmart_model->update_info('tbluser', $passupdate, 'UserID', $currentid);
					 echo 1;
					 }
					}
			}
		function myavourites(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				//$favoriteinfo= $this->Foodmart_model->read_all('*', 'tblfavourite', array('UserID' => $this->session->userdata('UserID')));
				$sdata['userinfo']=$userinfo;
				//$sdata['favoriteinfo']=$favoriteinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('Favourites', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function myearn(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('Myearn', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function myPoints(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MyPoints', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function myPriyoCard(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MyPriyoCard', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function priyocardapply(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('mypriyoCardform', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function PriyoCardSubmit(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 	 $currentid=$this->session->userdata('UserID');
					 $data['PriyocardUUID']          = GUID();
					 $data['UserID']    		     = $currentid;
					 $data['UserName']          	 = $this->input->post('UserName');
					 $data['UserEmail']         	 = $this->input->post('UserEmail');
					 $data['PhoneNumber']      	     = $this->input->post('PhoneNumber');
					 $data['CardName']               = $this->input->post('CardName');
					 $data['IndividualUse']          = $this->input->post('IndividualUse');
					 $data['IndividualAmount']       = $this->input->post('IndividualAmount');
					 $data['MultiUse'] 			     = $this->input->post('MultiUse');
					 $data['MultiAmount']     	     = $this->input->post('MultiAmount');
					 $data['DeliveryAddress']        = $this->input->post('DeliveryAddress');
					 $data['Everyday'] 			     = $this->input->post('Everyday');
					 $data['Week']      			 = $this->input->post('Week');
					 $data['Month'] 				 = $this->input->post('Month');
					 $data['Year']      			 = $this->input->post('Year');
					 $data['CardType'] 			     = $this->input->post('CardType');
					 $data['PriyocardIsActive']      = "0";
					 $data['CardApproved']           = "0";
					 $data['UserIDInserted']         = $currentid;
					 $data['UserIDUpdated']  		 = $currentid;
					 $data['UserIDLocked']   		 = $currentid;
					 $data['DateInserted'] 		     = date('Y-m-d H:i:s');
					 $data['DateUpdated']  		     = date('Y-m-d H:i:s');
					 $data['DateLocked']   		     = date('Y-m-d H:i:s');
					 $this->Foodmart_model->insert_data('tblpriyocard', $data);
					 header("Location: ".$this->config->base_url().'MyPriyoCard');
				}
			}
		function PriyoCardFeatures(){
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('PriyoCardFeatures', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function myOrderList(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MyOrderList', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function loadordlist(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$sdata['status']=$this->input->post('listid');
        		$this->load->view('loadordlist', $sdata);
				}
			}
		function ViewOrderDetails(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$orderid=$this->input->post('id');
				$sdata['ordid']=$orderid;
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->UserID));
				$restinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->RestaurantID));
				$sdata['order']=$orderinfo;
				$sdata['user']=$userinfo;
				$sdata['Restaurant']=$restinfo;
        		$this->load->view('ViewOrderDetails', $sdata);
				}
			}
		function TrackOrderDetails($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$orderid=$id;
				$sdata['ordid']=$orderid;
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('Currentstatus', $sdata, TRUE);
        		$this->load->view('index', $data);
				
				
				
				
				}
			}
		function Onprocess($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$orderid=$id;
				$sdata['ordid']=$orderid;
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->UserID));
				$restinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->RestaurantID));
				$sdata['order']=$orderinfo;
				$sdata['user']=$userinfo;
				$sdata['Restaurant']=$restinfo;
				$data['content'] = $this->load->view('Onprocess', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function posprint($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$orderid=$id;
				$sdata['ordid']=$orderid;
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->UserID));
				$restinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->RestaurantID));
				$riderinfo= $this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $orderinfo->riderid));
				$sdata['order']=$orderinfo;
				$sdata['user']=$userinfo;
				$sdata['Restaurant']=$restinfo;
				$sdata['riderinfo']=$riderinfo;
        		$this->load->view('posprint', $sdata);
				}
			}
		
		function cooktimeupdate(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$status=$this->input->post('status');
				$orderid=$this->input->post('orderid');
			 $orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
			 $userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->UserID));
			 $restaurantinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->RestaurantID));
			 $cartinfo= $this->Foodmart_model->read_all('*', 'tblcart', array('OrderID' => $orderid));
			 $html='';
				foreach($cartinfo as $item){
					$totalitemprice=$item->Price*$item->Quantity;
					$productinfo= $this->Foodmart_model->read('*', 'tblproducts', array('ProductsID' => $item->ProductID));
					$html.='
						<tr>
							<td style="border: 1px solid black;">'.$productinfo->ProductName.'</td>
							<td  style="border: 1px solid black;" align="right">'.$item->Price.'</td>
							<td style="border: 1px solid black;" align="middle">'.$item->Quantity.'</td>
							<td style="border: 1px solid black;" align="right">'.$totalitemprice.'</td>
						</tr>
					';
					}
				if($orderinfo->PaymentMethod==1){
				$txtPaymentMethod="Cash On Delivery";
				}else if($orderinfo->PaymentMethod==2){
					$txtPaymentMethod="Online Card Payment";
				}else if($orderinfo->PaymentMethod==3){
					$txtPaymentMethod="Bkash Payment";
				}
				if($orderinfo->DeliveryFee>0){
				$deliveryfee=$orderinfo->DeliveryFee;
				}else{
					$deliveryfee="Free";
				}
				if($orderinfo->Shipping=="1"){
				$txtDeliveryMethod="Delivery";	
				}
				else{
					$txtDeliveryMethod="Pick Up";
				}
				$emailcontent='
					 <table  border="1" width="100%" style="border: 1px solid black;">
						<thead>
						<tr>
							<th style="border: 1px solid black;">
								 Product
							</th>
							<th style="border: 1px solid black;">
								 Unit Price (BDT)
							</th>
							<th style="border: 1px solid black;">
								 Quantity
							</th>
							<th style="border: 1px solid black;">
								 Total (BDT)
							</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<th class="total-label" colspan="2" align="left" style="border: 1px solid black;">
								 Payment Method: '.$txtPaymentMethod.'
							</th>
		
							<th class="total-label" align="right" style="border: 1px solid black;">
								Sub Total:
							</th>
							<th class="total-amount"  align="right" style="border: 1px solid black;">
								 '.$orderinfo->Amount.' 
							</th>
						</tr>
						<tr>
							<th class="total-label" colspan="2"  align="left" style="border: 1px solid black;">
								 Delivery Method: &nbsp;&nbsp; '.$txtDeliveryMethod.'
							</th>
		
							<th class="total-label" align="right" style="border: 1px solid black;">
								Discount: '.$orderinfo->DiscountPercentage.'%
							</th>
							<th class="total-amount" align="right" style="border: 1px solid black;">
								 - '.$orderinfo->Discount.'
							</th>
						</tr>
						
						<tr>
							<th class="total-label" colspan="2" align="left" style="border: 1px solid black;">
								 Discount Type: &nbsp;&nbsp; '.$orderinfo->DiscountType.'
							</th>
							<th class="total-label" align="right" style="border: 1px solid black;">
								Shipping Cost:
							</th>
							<th class="total-amount" align="right" style="border: 1px solid black;">
								'.$deliveryfee.'
							</th>
						</tr>
		
						<tr>
							<th class="total-label" colspan="2" style="border: 1px solid black;">
								 &nbsp;
							</th>
							<th class="total-label" align="right" style="border: 1px solid black;">
								Vat ('.$orderinfo->Vat.')%:
							</th>
							<th class="total-amount" align="right" style="border: 1px solid black;">
								'.$orderinfo->Vatamount.'
							</th>
						</tr>
						<tr>
							<th class="total-label" colspan="2" style="border: 1px solid black;">
								 &nbsp;
							</th>
							<th class="total-label" align="right" style="border: 1px solid black;">
								Service Charge ('.$orderinfo->ServiceCharge.')%:
							</th>
							<th class="total-amount" align="right" style="border: 1px solid black;">
								'.$orderinfo->ServiceChargeAmount.'
							</th>
						</tr>
						<tr>
							<th class="total-label" colspan="2" style="border: 1px solid black;">
								 &nbsp;
							</th>
		
							<th class="total-label" align="right" style="border: 1px solid black;">
								<p style="border-top:1px solid #000;">Grand Total:</p>
							</th>
							<th class="total-amount" align="right" style="border: 1px solid black;">
								<p style="border-top:1px solid #000;">'.$orderinfo->GrandTotal.'</p>
							</th>
						</tr>
						</tfoot>
						<tbody>
						'.$html.'
						</tbody>
						</table>
					 ';
				if($status=="update"){
					$cooktime=$this->input->post('cooktime');
					$dataupdate['Cooktime'] 	= $cooktime;
					$dataupdate['restStatus'] 	= "Update";
					 $this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					}
				if($status=="Approved"){
					$dataupdate['restStatus'] 	= "Approved";
					 $this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					 // sent email on Processiong
					if($order->Shipping=="1") { // if delivery
						// sent email on Processiong
						SendMail(
							$ToEmail=$user->UserEmail,
							$Subject="Your Order on process",
							$Body="
Dear Sir/Madam, 
<br><br>
We are very much pleased to inform you ,we have received your order and your order on process.Thank you for choosing foodmart service. Our dedicated Rider team will serve your food within our estimated time  or if there are any other updates. 
<br>	
We desire you might receive your food within one hour. 				
<br><br>
Payment Type: Full Paid. Online payment/Cash on delivery. <br>
".$emailcontent."
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
							$ToEmail=$user->UserEmail,
							$Subject="Your Order on process",
							$Body="
								Dear Sir/Madam, 
								<br><br>
				
								We are very much pleased to inform you ,we have received your order and your order on process.Thank you for choosing foodmart service. We desire your food will be ready within our estimated time.You will be able to collect your food as soon as your food is cooked. Normally it takes 30 minutes. 
			
								<br><br>
								
								Restaurant address : <br>
								Phone : ".$restaurantinfo->PhoneNumber." <br>
								".$restaurantinfo->ResAddress."					
								<br><br>
				
								Please feel free to <a href=\"https://www.foodmart.com.bd/index.php?Theme=default&Base=Page&Script=Contactus\">contact</a> us if any queries.
								<br><br>
								Payment Type: Full Paid. Online payment/Cash on delivery. <br>
								".$emailcontent."
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
						// Send SMS
						SendSMS('88'.$user->PhoneMobile,
						$SMS ="Dear Sir/Madam, Your order on process.We are dedicated to serve your food on estimated time. Thanks your cooperation Support Team \n01793111333.");
					}
				if($status=="Release"){
					$dataupdate['restStatus'] 	= "Release";
					$this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					}
				if($status=="Cancel"){
					$reason="Restaurant Cancelled";
					$dataupdate['restStatus'] 	= "Cancel";
					$dataupdate['OrderStatus'] 	= "Cancelled";
					$dataupdate['restStatus'] 	= $reason;
					$this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					SendMail(
			$ToEmail=$user->UserEmail,
			$Subject="Your Order Cancelled",
			$Body="
				Dear Sir/Madam,<br><br>
				We extremely sorry for not proceed your order.
				<br><br>
				We are waiting for next order.<br>
				<br>
				Love food.Love foodmart.<br>
				<br>
				Support Team<br>
				
				Foodmart <br>
				Hotline: 01793111333<br>
			",
			$FromName="Foodmart.com.bd",
			$FromEmail = "order@foodmart.com.bd",
			$ReplyToName="Foodmart.com.bd",
			$ReplyToEmail="order@foodmart.com.bd",
			$ExtraHeaderParameters=""
		);
		
		
			// Send SMS
			SendSMS('88'.$user->PhoneMobile,
			$SMS ="Dear {$user->UserName}, We extremely sorry for not proceed your order Due to {$reason}. We are waiting for your next order. - foodmart.");
	
					}
				if($status=="Accepto"){
					$dataupdate['cuStatus'] 	= "Accepted";
					$this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					}
				if($status=="Rejecto"){
					$reason="Customer Cancelled";
					$dataupdate['cuStatus'] 	= "Cancel";
					$dataupdate['OrderStatus'] 	= "Cancelled";
					$dataupdate['cuStatus'] 	= $reason;
					$this->Foodmart_model->update_info('tblorder', $dataupdate, 'OrderID', $orderid);
					SendMail(
			$ToEmail=$user["UserEmail"],
			$Subject="Your Order Cancelled",
			$Body="
				
				Dear Sir/Madam,<br><br>
				
				We extremely sorry for not proceed your order.
				<br><br>
				We are waiting for next order.<br>
				
				<br>
				
				Love food.Love foodmart.<br>
				
				<br>
				Support Team<br>
				
				Foodmart <br>
				Hotline: 01793111333<br>
		
			",
			$FromName="Foodmart.com.bd",
			$FromEmail = "order@foodmart.com.bd",
			$ReplyToName="Foodmart.com.bd",
			$ReplyToEmail="order@foodmart.com.bd",
			$ExtraHeaderParameters=""
		);
			// Send SMS
			SendSMS('88'.$user->PhoneMobile,
			$SMS ="Dear {$user->UserName}, We extremely sorry for not proceed your order Due to {$reason}. We are waiting for your next order. - foodmart.");
					}
				}
			}
		function reloadcooktime(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$orderid=$this->input->post('ordid');
				$OrderDetails= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
        		$cookingtime = $OrderDetails->Cooktime;
				$crcookTimeformat =date('H:i:s',strtotime($cookingtime));
				
				$ctime = strtotime($crcookTimeformat);
				$ctime2 = time();
				$subTime2 = $ctime2 - $ctime;
				$min = ($subTime2/60)%60;
				if($min>0){
				$newmin="You are late {$min} min";
				}
				$OrderDetails->restStatus;
				
				if(($OrderDetails->cuStatus=="Accepted") && ($OrderDetails->restStatus=="Approved")){
				$textcook = $newmin;
				}
				else{
				$textcook = "";
				}
				echo $textcook;
				}
			}
		function orderdetails($id){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				$currentid=$this->session->userdata('UserID');
				$sdata['Usercat']=$this->session->userdata('UsersCategory');
				$sdata['UserId']=$currentid;
				$AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$orderid=$id;
				$sdata['ordid']=$orderid;
				$orderinfo= $this->Foodmart_model->read('*', 'tblorder', array('OrderID' => $orderid));
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->UserID));
				$restinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $orderinfo->RestaurantID));
				$riderinfo= $this->Foodmart_model->read('*', 'tblriderlist', array('RiderlistID' => $orderinfo->riderid));
				$sdata['order']=$orderinfo;
				$sdata['user']=$userinfo;
				$sdata['RestaurantInfo']=$restinfo;
				$sdata['riderinfo']=$riderinfo;
        		$this->load->view('OrderDetails', $sdata);
				}
			}
		function massageCenter(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				$CheckRead = $this->Foodmart_model->read('*', 'tblinbox', array('UserID'=>$currentid));
				if(count($CheckRead)<1){
					$datain['UserID']       		= $currentid;
					$datain['Message']    			= "";
					$datain['IsRead']   	   		= 1;
					$datain['ParentID']          	= "0";
					$datain['InboxIsActive']   		= 1;
					$datain['SendBy']   	      	= $currentid;
					$datain['DateInserted']   		= date('Y-m-d H:i:s');
					$datain['DateUpdated']   		= date('Y-m-d H:i:s');
					$datain['DateLocked']   	   	= date('Y-m-d H:i:s');
					$this->Foodmart_model->insert_data('tblinbox', $datain);
					}
					$dataupdate['IsRead']   	   		= 1;
					$this->Foodmart_model->update_info('tblinbox', $dataupdate, 'UserID', $currentid);
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MassageCenter', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function ReplayUpdate(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$msg = urldecode($this->input->post('smg'));
				$Inbox = urldecode($this->input->post('id'));
				$Sender = urldecode($this->input->post('Sender'));
				if(!empty($Inbox)){
				$CheckRead = $this->Foodmart_model->read('*', 'tblinbox', array('InboxID'=>$Inbox));
				if($CheckRead->IsRead==0){
					if($CheckRead->SendBy==$Sender){
						$Read=0;
					}else{
						$Read=1;
					}
				}else{
					$Read=1;
				}
					$dataf['InboxUUID']			=  GUID();
					$dataf['UserID']       		= $Sender;
					$dataf['Message']    		= $msg;
					$dataf['IsRead']   	   		= 0;
					$dataf['ParentID']          = $Inbox;
					$dataf['InboxIsActive']   	= 1;
					$dataf['SendBy']   	      	= $Sender;
					$dataf['DateInserted']   	= date('Y-m-d H:i:s');
					$dataf['DateUpdated']   	= date('Y-m-d H:i:s');
					$dataf['DateLocked']   	   	= date('Y-m-d H:i:s');
					$dataup['IsRead']   	    = $Read;
					$dataup2['IsRead']   	    = 1;
					$this->Foodmart_model->insert_data('tblinbox', $dataf);
					$this->Foodmart_model->update_info('tblinbox', $dataup, 'InboxID', $Inbox);
					$this->Foodmart_model->update_info('tblinbox', $dataup2, 'InboxID', $Inbox);
				}
					$this->load->view('ReplayUpdate', $sdata);
				}
			}
		function ReplayUpdatereload(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$Inbox = urldecode($this->input->post('id'));
					$this->load->view('ReplayUpdatereload', $sdata);
				}
			}
		function myReservation(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$reservationfo= $this->Foodmart_model->read_all('*', 'tblreservationrequest', array('UserID' => $currentid));
				$sdata['userinfo']=$userinfo;
				$sdata['reserve']=$reservationfo;
				$ldata['userinfo']=$userinfo;
				
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('MyReservation', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function settings(){
			if($this->session->userdata('UserID')== FALSE)
			{
				header("Location: ".$this->config->base_url());
			}
			else{
				 $currentid=$this->session->userdata('UserID');
				 $sdata['Usercat']=$this->session->userdata('UsersCategory');
				 $sdata['UserId']=$currentid;
				 $ldata['Usercat']=$this->session->userdata('UsersCategory');
				 $ldata['UserId']=$currentid;
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$userinfo= $this->Foodmart_model->read('*', 'tbluser', array('UserID' => $this->session->userdata('UserID'),'UserIsActive'=>1));
				$sdata['userinfo']=$userinfo;
				$ldata['userinfo']=$userinfo;
				
				$sdata['leftbar']=$this->load->view('LeftSideBar', $ldata,TRUE);
				$data['content'] = $this->load->view('Settings', $sdata, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function aboutus(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>1));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>1));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('Aboutus', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function contactus(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>4));
				$sdata['Slider']=$sliderinfo;
				$sdata['msg']='';
				if($this->input->method() === 'post'){
				$Name = $this->input->post('Name');
				$Email = $this->input->post('Email');
				$PhoneNo = $this->input->post('PhoneNo');
				$Massage = $this->input->post('Massage');
				$datain['ContactUUID']      	   	   	   = GUID();
				$datain['Name']       				       = $Name;
				$datain['Email']    				       = $Email;
				$datain['PhoneNo']   	   			       = $PhoneNo;
			    $datain['Massage']                         = $Massage;
				$datain['ContactIsActive']   	      	   = 1;
				$datain['UserIDInserted']           	   = 1;
				$datain['UserIDUpdated']           	       = 1;
				$datain['UserIDLocked']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$this->Foodmart_model->insert_data('tblcontact', $datain);
				$sdata['msg']="Your Message Successfully Sent!";
				}
				else{
					$sdata['msg']="Please fill the from correct information";
					}
				$data['content'] = $this->load->view('Contactus', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function helpTutorial(){
			
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>14));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>10));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('HelpTutorial', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function terms(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>9));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>2));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('Terms', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function availAbleCity(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>11));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>8));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('AvailAbleCity', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function availAbleArea(){
				$currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>12));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>7));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('AvailAbleArea', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function suggation(){
			
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>15));
				$sdata['Slider']=$sliderinfo;
				$sdata['msg']='';
				if($this->input->method() === 'post'){
				$Suggation = $this->input->post('Suggation');
				$name = $this->input->post('Name');
				$email = $this->input->post('Email');
				$phone = $this->input->post('PhoneNo');
				$massage = $this->input->post('Massage');
				SendMail(
				$ToEmail="info@foodmart.com.bd",
				$Subject=$Suggation,
				$Body="
				Name: ".$name."<br>
				Email: ".$email."<br>
				Phone: ".$phone."<br>
				".$massage."
					<br><br>
				",
				$FromName=$email,
				$FromEmail = $email,
				$ReplyToName="Foodmart.com.bd",
				$ReplyToEmail="order@foodmart.com.bd",
				$ExtraHeaderParameters="orderarchive@foodmart.com.bd"
			);	
				$sdata['msg']="Your Message Successfully Sent!";
				}
				else{
					$sdata['msg']="";
					}
				$data['content'] = $this->load->view('Suggation', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function paymentMethod(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>15));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>11));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('PaymentMethod', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function app(){
				$currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read_all('*', 'tblappmanage', array('AppmanageIsActive' =>1));
				$pageinfo= $this->Foodmart_model->read('*', 'tblappmanage', array('Section' =>5));
				$sdata['PageQuery']=$sliderinfo;
				$sdata['section5']=$pageinfo;
				$data['content'] = $this->load->view('App', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
		function career(){
				
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>5));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>6));
				$galleryinfo= $this->Foodmart_model->read_all('*', 'tblcareergallery', array('CareergalleryIsActive' =>1));
				$cirinfo= $this->Foodmart_model->read_all('*', 'tblcirculars', array('CircularsIsActive' =>1));
				$sdata['cirinfo']=$cirinfo;
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$sdata['Pagegallery']=$galleryinfo;
				$sdata['msg']='';
				if($this->input->method() === 'post'){
				$Name = $this->input->post('Name');
				$Email = $this->input->post('Email');
				$PhoneNo = $this->input->post('PhoneNo');
				$circular = $this->input->post('ApplyFor');
				$CoverLetter = $this->input->post('CoverLetter');
				$circularinfo= $this->Foodmart_model->read('*', 'tblcirculars', array('CircularsID' =>$circular));
				$postcir=$circularinfo->Post;
				
				$this->load->library('upload');
				$config['upload_path'] = './upload/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '30000';
				$config['image_width']= '4000';
				$config['image_height']= '4000';
				$this->upload->initialize($config);
					$attachment = $this->upload->do_upload('Attachment');
					$image1 = $this->upload->data();
					 if($attachment !=''){
					$config['image_library'] = 'gd2';
					$config['source_image'] = $this->upload->upload_path.$image1['file_name'];
					$config['new_image'] = 'upload/'.$image1['file_name'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 800;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					$datain['Attachment']                      = $image1['file_name'];
					 }
					 else{
					$datain['Attachment']                      ="";	 
						 }

					
				
				$datain['CareerUUID']      	   	   	   	   = GUID();
				$datain['Name']       				       = $Name;
				$datain['Email']    				       = $Email;
				$datain['PhoneNo']   	   			       = $PhoneNo;
			    $datain['ApplyFor']                        = $postcir;
				$datain['CoverLetter']                     = $CoverLetter;
				$datain['CareerIsActive']   	      	   = 1;
				$datain['UserIDInserted']           	   = 1;
				$datain['UserIDUpdated']           	       = 1;
				$datain['UserIDLocked']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$this->Foodmart_model->insert_data('tblcareer', $datain);
				$sdata['msg']="Application Submitted Successfully";
				}
				else{
					$sdata['msg']="";
					}
				$data['content'] = $this->load->view('Career', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function careerdetails($id){
				
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$pageinfo= $this->Foodmart_model->read('*', 'tblcirculars', array('CircularsID' =>$id,'CircularsIsActive'=>1));
				$sdata['PageQuery']=$pageinfo;
				
				$data['content'] = $this->load->view('job', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function applyforRider(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>5));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>6));
				$riderarea= $this->Foodmart_model->read_all('*', 'tblriderarea', array('RiderareaIsActive' =>1));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$sdata['listarea']=$riderarea;
				$sdata['msg']='';
				if($this->input->method() === 'post'){
				$Name = $this->input->post('Name');
				$areaid = $this->input->post('Area');
				$PhoneNo = $this->input->post('PhoneNo');
				$dlicence = $this->input->post('vehiclesname');
				$Nid = $this->input->post('Nid');
				$rnid = $this->input->post('rnid');
				$dob = $this->input->post('dob');
				$password = $this->input->post('password');
				$getarea= $this->Foodmart_model->read('*', 'tblriderarea', array('RiderareaID' =>$areaid));
				$Preferedarea =$getarea->RiderareaName;
				if($dlicence=='Cycle'){
					$vichletype=1;
					}
				else{
					$vichletype=0;
					}
				$this->load->library('upload');
				$config['upload_path'] = './upload/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '30000';
				$config['image_width']= '4000';
				$config['image_height']= '4000';
				$this->upload->initialize($config);
					$attachment = $this->upload->do_upload('picture');
					$image1 = $this->upload->data();
					 if($attachment !=''){
					$config['image_library'] = 'gd2';
					$config['source_image'] = $this->upload->upload_path.$image1['file_name'];
					$config['new_image'] = 'upload/'.$image1['file_name'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 200;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					$datarin['picture']                      = $image1['file_name'];
					$datain['UserPicture']                 = $image1['file_name'];
					 }
					 else{
					$datarin['picture']                      ="";	 
					$datain['UserPicture']                  = "";
						 }

				
				$datarin['RiderapplicantUUID']      	   = GUID();
				$datarin['Name']       				       = $Name;
				$datarin['Preferedarea']    			   = $Preferedarea;
				$datarin['PhoneNo']   	   			       = $PhoneNo;
			    $datarin['drivinglince']                   = $dlicence;
				$datarin['nid']                    		   = $Nid;
				$datarin['refnid']                         = $rnid;
				$datarin['dob']                    		   = $dob;
				$datarin['RiderapplicantIsActive']   	   = 1;
				$datarin['UserIDInserted']           	   = 1;
				$datarin['UserIDUpdated']           	   = 1;
				$datarin['UserIDLocked']           	       = 1;
				$datarin['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datarin['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datarin['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$this->Foodmart_model->insert_data('tblriderapplicant', $datarin);
				
				$datain['RiderlistUUID']      	   	       = GUID();
				$datain['RiderareaID']       		       = $areaid;
				$datain['RiderareaName']       		       = $Preferedarea;
				$datain['RiderName']    			       = $Name;
				$datain['phone']   	   			           = $PhoneNo;
				$datain['Rider_type']                      = "Flexible";
				$datain['vichletype']                      = $vichletype;
				$datain['nid']                    		   = $Nid;
				$datain['refnid']                          = $rnid;
				$datain['dateofbirth']                     = $dob;
				$datain['Isriderapply']   	   		       = "Yes";
				$datain['RiderlistIsActive']   	   		   = 0;
				$datain['UserIDInserted']           	   = 1;
				$datain['UserIDUpdated']           	       = 1;
				$datain['UserIDLocked']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$this->Foodmart_model->insert_data('tblriderlist', $datain);
				
				$sdata['msg']="অ্যাপ্লিকেশন সফলভাবে জমা দেওয়া হয়েছে, দয়া করে ফোন কলের জন্য অপেক্ষা করুন";
				//redirect('ApplyforRider');
				}
				else{
					$sdata['msg']="";
					}
				$data['content'] = $this->load->view('ApplyforRider', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			
			}
		function applyForPartner(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>2));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>5));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				if($this->input->method() === 'post'){
				$restname = $this->input->post('RestaurantName');
				$urlslug = $this->input->post('urlslug');
				$fullarddress = $this->input->post('Address1');
				$notes = $this->input->post('notes');
				$PhoneNo = $this->input->post('PhoneNo');
				$mobile = $this->input->post('UserPhoneNumber');
				$email = $this->input->post('EmailAdd');
				$OwnerName = $this->input->post('OwnerName');
				$Designation = $this->input->post('Designation');
				$aboutus = $this->input->post('aboutus');
				$area = $this->input->post('area');
				$city = $this->input->post('city');
				$speciality = $this->input->post('speciality');
				$Commission = $this->input->post('percent');
				$minordtotal = $this->input->post('minordtotal');
				$maxordtotal = $this->input->post('maxordtotal');
				$vat = $this->input->post('vat');
				$Restaurantm = $this->input->post('Restaurant');
				$homemade = $this->input->post('homemade');
				$partycenter = $this->input->post('partycenter');
				$catering = $this->input->post('catering');
				if(empty($Restaurantm)){
					$Restauranttype="0";
					}
				else{
					$Restauranttype=$Restaurantm;
					}
				if(empty($homemade)){
					$homemadetype="0";
					}
				else{
					$homemadetype=$homemade;
					}
				if(empty($partycenter)){
					$partycentertype="0";
					}
				else{
					$partycentertype=$partycenter;
					}
				if(empty($catering)){
					$cateringtype="0";
					}
				else{
					$cateringtype=$catering;
					}
				$Ownrider = $this->input->post('Ownrider');
				$reservation = $this->input->post('reservation');
				$collection = $this->input->post('collection');
				$menumanage = $this->input->post('menumanage');
				$UsersCategory = "3";
				$youpass=$this->input->post('password');
				$UserPassword = md5($youpass);
				$UserIsActive = 1;
				$marchand = "Merchant";
				$this->load->library('upload');
				$config['upload_path'] = './upload/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '30000';
				$config['image_width']= '4000';
				$config['image_height']= '4000';
				$this->upload->initialize($config);
				$attachment = $this->upload->do_upload('Attachment');
				$image1 = $this->upload->data();
				 if($attachment !=''){
				$config['image_library'] = 'gd2';
				$config['source_image'] = $this->upload->upload_path.$image1['file_name'];
				$config['new_image'] = 'upload/'.$image1['file_name'];
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 800;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$datain['Attachment']                      = $image1['file_name'];
				 }
				 else{
				$datain['Attachment']                      ="";	 
					 }
				$datain['UserUUID']      	   	   		   = GUID();
				$datain['ResOwnerName']       			   = $OwnerName;
				$datain['UserEmail']       			   	   = $email;
				$datain['marchantid']       			   = $urlslug;
				$datain['RestaurantName']    			   = $restname;
				$datain['PhoneNumber']   	   			   = $PhoneNo;
			    $datain['PhoneMobile']                     = $mobile;
				$datain['ResAddress']   	   			   = $fullarddress;
				$datain['Designation']       			   = $Designation;
				$datain['ServiceCharge']    			   = $Commission;
				$datain['notesforcusrest']   	   		   = $notes;
			    $datain['Speciality']                      = $speciality;
				$datain['MinOrder ']   	   			   	   = $minordtotal;
				$datain['maxorder']       			   	   = $maxordtotal;
				$datain['Vat']    			   	   		   = $vat;
				$datain['Area']       			   	   	   = $area;
				$datain['City']    			   	   		   = $city;
				$datain['isrestaurant']                    = $Restauranttype;
				$datain['ishomemade']   	   			   = $homemadetype;
				$datain['ispartycenter']       			   = $partycentertype;
				$datain['iscatering']    			   	   = $cateringtype;
				$datain['allowrider']   	   		       = $Ownrider;
			    $datain['AllowReservation']                = $reservation;
				$datain['acceptcollection']   	   		   = $collection;
				$datain['acceptmenu']       			   = $menumanage;
				$datain['UsersCategory']   	   			   = "3";
			    $datain['UserPassword']                    = $UserPassword;
				$datain['UserIsActive']   	   			   = $UserIsActive;
				$datain['AboutText']   	   			       = $aboutus;
				$datain['ismarchandcreated']   	   		   = $marchand;
				$datain['UserIDInserted']           	   = 1;
				$datain['UserIDUpdated']           	       = 1;
				$datain['UserIDLocked']           	       = 1;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$datain['DateLocked']   	   			   = date('Y-m-d H:i:s');
				$lastid=$this->Foodmart_model->insert_data('tbluser', $datain);
				//print_r($datain);
				$dataup['UserIDInserted']           	   = $lastid;
				$dataup['UserIDUpdated']           	       = $lastid;
				$dataup['UserIDLocked']           	       = $lastid;
				$this->Foodmart_model->update_info('tbluser', $dataup, 'UserID', $lastid);
				// Send SMS
				SendSMS('88'.$mobile, $SMS ="Great ! Your merchant account has beed created successfully! You may Login to your account and go to merchant profile to update menu. Thank you");
                $ToEmail=$email;
                $htmlContent=MarchantAc($email,$youpass);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to($ToEmail);
                $this->email->from('order@foodmart.com.bd','Foodmart');
                $this->email->subject('Create New Account');
                $this->email->message($htmlContent);
                $this->email->send();
				 echo "<script>
                        alert('Application Submitted Successfully.!!! We will Contact you as soon as possible!!!');
						window.location.href='".$this->config->base_url()."ApplyForPartner';
                     </script>";
				}
				else{
					
					}
				$data['content'] = $this->load->view('ApplyForPartner', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			
			
			}
		function pdfcontent(){
				$restname = $this->input->post('resname');
				$fullarddress = $this->input->post('fullarddress');
				$PhoneNo = $this->input->post('Phone');
				$mobile = $this->input->post('mobile');
				$email = $this->input->post('email');
				$slugurl = $this->input->post('resurl');
				$data1['UserEmail']=$email;
				$data1['RestaurantName']=$restname;
				$data1['fullarddress']=$fullarddress;
				$data1['PhoneMobile']=$mobile;
				$data1['PhoneNumber']=$PhoneNo;
				$data1['marchantid']=$slugurl;
				$userinfo=$this->Foodmart_model->check_user2($data1);
				count($userinfo);
				if(count($userinfo)>0){
					echo "404";
					}
				//$this->load->view('index', $data);
			}
		function mrachantlogin(){
			 if($this->session->userdata('UserID')== TRUE)
				{
					header("Location: ".$this->config->base_url()."MyProfile");
				}
				else{
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$data['content'] = $this->load->view('PartnerLogin', $data, TRUE);
        		$this->load->view('index', $data);
				}
			}
		function businessPartner(){
				
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>13));
				$pageinfo= $this->Foodmart_model->read('*', 'tblpagesettings', array('PageNo' =>9));
				$sdata['Slider']=$sliderinfo;
				$sdata['PageQuery']=$pageinfo;
				$data['content'] = $this->load->view('BusinessPartner', $sdata, TRUE);
        		$this->load->view('index', $data);
			
			}
		function tvmedia(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sdata['userID']=$currentid;
				$data['content'] = $this->load->view('Tvmedia', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
public function getthisarea(){
	$servicelocation = $this->input->post('serloc');
	 $allarea=$this->Foodmart_model->read_allgroup('*', 'tblservicearea', array('locationname'=>$servicelocation,'ServiceareaIsActive'=>1),'Name','ASC','Name');
	 foreach($allarea as $areas){
		 echo '<option value="'.$areas->Name.'">'.$areas->Name.'</option>';
		 }
	}
function alloffres(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sdata['userID']=$currentid;
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>17));
				$sdata['Slider']=$sliderinfo;
				$sdata['alldealsoffer']=$this->Foodmart_model->get_alloffers();
				$sdata['allcouponoffer']=$this->Foodmart_model->get_allcoupon();
				$data['content'] = $this->load->view('featureitem', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
function alloffresbyarea(){
				 $myarea= $this -> input -> post("areaname");					
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sdata['userID']=$currentid;
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>17));
				$sdata['Slider']=$sliderinfo;
				$sdata['alldealsoffer']=$this->Foodmart_model->get_alloffersbyarea($myarea);
				$this->load->view('loadoffer', $sdata);
			}
			
function checkarea(){
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sdata['userID']=$currentid;
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>17));
				$sdata['Slider']=$sliderinfo;
				$data['content'] = $this->load->view('testrestaurant', $sdata, TRUE);
        		$this->load->view('index', $data);
			}
function checkareabyarea(){
				 $myarea= $this -> input -> post("areaname");					
				 $currentid=$this->session->userdata('UserID');
				 $AreaCK= $this->input->cookie('AreaCK');
				 $LocationCK= $this->input->cookie('LocationCK'); 
				 $pageslug=$this->uri->segment(1);
				 if(empty($pageslug)){
					$pageslug="Home";
					}
				$data['seo']=$this->Foodmart_model->pageseo('MenuPage');
				$data['allLocation']=$this->Foodmart_model->Allocation();
				$data['allArea']=$this->Foodmart_model->Allarea();
				if($currentid!=''){
					$data['CurrentUser']=$this->Foodmart_model->Getsessionid($currentid);
					$data['Conversationsf']=$this->Foodmart_model->checkinbox($currentid);
					$data['chatinfo']=$this->Foodmart_model->Chatbox($currentid);
					$myid = $data['chatinfo']->UserID;
					$myinbox = $data['chatinfo']->InboxID;
					$data['Senderf']=$this->Foodmart_model->senderinfo($myid);
					$data['replayf']=$this->Foodmart_model->replayinfo($myinbox);
					foreach($data['replayf'] as $allreply){
						$senderid = $allreply->SendBy;
						$data['sender']=$this->Foodmart_model->senderf($senderid);
						}
					}
				$sdata['userID']=$currentid;
				$sliderinfo= $this->Foodmart_model->read('*', 'tblsliders', array('PageID' =>17));
				$sdata['Slider']=$sliderinfo;
				$sdata['alldealsoffer']=$this->Foodmart_model->get_testbyarea($myarea);
				$this->load->view('loadtestrest', $sdata);
			}	
  function sendjob(){
	  $orderinfoinfo= $this->Foodmart_model->fulltable('*', 'tbljobsend');
	  if(!empty($orderinfoinfo)){
	  foreach($orderinfoinfo as $order){
		 $orderid=$order->orderid;
		 //echo "select * FROM tbljobsend Where orderid='".$orderid."' Order By jobsendid ASC";
		  $allrider=$this->Foodmart_model->read_all('*', 'tbljobsend', array('orderid' => $orderid),'jobsendid','ASC');
		  $listrider="";
		  foreach($allrider as $singlerider){
			  $listrider.=$singlerider->riderid.",";
			  }
		  $listrider=rtrim($listrider,',');
		  //echo "select * FROM tblorder Where OrderID='50259' AND riderfirstsend='Sent' AND JobSendStatus='Cancel'";
		  $orderlist = $this->Foodmart_model->read('*', 'tblorder', array('OrderID' =>$orderid,'riderfirstsend'=>'Sent','JobSendStatus'=>'Cancel'));
		  if(!empty($orderlist)){
		  $myresInfo = $this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$orderlist->RestaurantID));
		  $myuserinfo=$this->Foodmart_model->read('*', 'tbluser', array('UserID'=>$orderlist->UserID));
		
		   $restid=$orderlist->RestaurantID;
		  $location = $this->Foodmart_model->read('*', 'tbllocation', array('RestaurantID' =>$restid));
		  $sqlrider="SELECT *,( 6371 * acos( cos( radians(".$location->Latitude.") ) * cos( radians( latitude_value ) ) * cos( radians( longitude_value ) - radians(".$location->Longitude.") ) + sin( radians(".$location->Latitude.") ) * sin( radians( latitude_value ) ) ) ) AS Distance FROM tblriderlist Where RiderlistID NOT IN(".$listrider.") AND Rider_type='Flexible' AND RiderlistIsActive=1 AND Rider_status=1 HAVING Distance < 2 ORDER BY Distance Limit 1";
		  $query_result=  $this->db->query($sqlrider);
          $result=$query_result->row();
		  if(!empty($result)){
		  if(!empty($result->Tokenno)){
			$arr=$result->Tokenno;
			}
			
		$totalorderthisrider="SELECT count(riderid) as totalorder FROM tblorder Where OrderStatus='Processing' AND riderid='".$result->RiderlistID."'";
		$resultriderorder = $this->db->query($totalorderthisrider);
		$countorder= $resultriderorder->row();
		if($countorder->totalorder<1){
		
		  $udata['riderid']=$result->RiderlistID;
		  $udata['riderstatus']="Sent";
		  $udata['JobSendStatus']="";
		  $udata['DateUpdated']=date('Y-m-d H:i:s');
		  $this->db->where('OrderID',$orderid);
		  $this->db->update('tblorder',$udata);
		  
		   $sendin['orderid'] 		= $orderid;
		   $sendin['riderid']  		= $result->RiderlistID;
		   $this->Foodmart_model->insert_data('tbljobsend', $sendin);
		   
		   //details
		   
		$orderid=$orderid;
		$amount=round($orderlist->Amount);
		$DeliveryAddress=$orderlist->DeliveryAddress;
		$DateInserted=$orderlist->DateInserted;
		$OrderStatus=$orderlist->OrderStatus;
		$RiderreceivingStatus=$orderlist->riderstatus;
		$RestaurantID=$orderlist->RestaurantID;
		$UserID=$orderlist->UserID;
		$totalcommision = ($amount*$myresInfo->Commission)/100;
		$valcal = $orderlist->Vatamount;
		$sercal = $orderlist->ServiceChargeAmount;
		$respay = ($amount+$valcal+$sercal)-$totalcommision;	
		$Pay_To_Restaurant=round($respay);
		$Pay_To_Customer=round($orderlist->GrandTotal);
		$RestaurantName=$myresInfo->RestaurantName;
		$ResAddress=$myresInfo->ResAddress;
		$PhoneMobile=$myresInfo->PhoneMobile;
		$Rest_Longitude=$location->Longitude;
		$Rest_Latitude=$location->Latitude;
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
			$message = json_encode($newmsg);	
				
		
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
			   $sendin['riderid']  		= $result->RiderlistID;
			   $this->Foodmart_model->insert_data('tbljobsend', $sendin);
			  }
		  }
		  }
		  
		  }
	  }
	  }	
  function changedate(){
	  	$cdate = $this->input->post('cdate');
		$mydates = date('m/d/Y');
		
		$currentdate=date('m-d-Y');
		if($cdate==$mydates){
			$curtime=date('Y-m-d H:i:s');
				$start2=strtotime($curtime);
				$start=strtotime('12:00 PM');
				$end=strtotime('10:00 PM');
				if($start2>$start){
					$start3=$start2;
					}
				else{
					$start3=$start;
					}
				$totaytime="";
				while($start3<$end) {
					$start3=ceil($start3/1800)*1800;
					$start3++;
					$gettime=date('Y-m-d H:i:s', $start3);
					$startfrom=date('h:i A', $start3);
					$startto = date("h:i A", strtotime($gettime . "+30 minutes"));
					$totaytime.='<option value="'.$startfrom.'-'.$startto.'">'.$startfrom.'-'.$startto.'</option>';
				}
				echo '<option value="" selected="selected">Select Time</option>';
				echo $totaytime;
			}
		else{
			
				$start=strtotime('12:00 PM');
				$end=strtotime('10:00 PM');
				$totaytime2="";
				while($start<$end) {
					$start=ceil($start/1800)*1800;
					$start++;
					$gettime=date('Y-m-d H:i:s', $start);
					$startfrom=date('h:i A', $start);
					$startto = date("h:i A", strtotime($gettime . "+30 minutes"));
					$totaytime2.='<option value="'.$startfrom.'-'.$startto.'">'.$startfrom.'-'.$startto.'</option>';
				}
				echo '<option value="" selected="selected">Select Time</option>';
				echo $totaytime2;
			
			}
	  }
  
  function riderinactive(){
	  	$allrider= $this->Foodmart_model->read_all('*', 'tblriderlist', array('RiderlistIsActive' =>1));
		/*$sqlrider="SELECT * FROM tblriderlist Where RiderlistIsActive=1";
		  $query_result=  $this->db->query($sqlrider);
          $allrider=$query_result->reselt();*/
		foreach($allrider as $singlerider){
			  	$currentime = date('h:i:s a');
				$deltime1 =strtotime($singlerider->DateUpdated);
				$Time1=date("h:i:s a", $deltime1);
				$startime =  strtotime($currentime);
				$ordetimec1 = strtotime($Time1);
				$subTime1 = $startime - $ordetimec1;
				$h1 = ($subTime1/(60*60))%24;
				$m1 = ($subTime1/60)%60;
			  	if($m1>72){
						$udata['Rider_status']=0;
						$this->Foodmart_model->update_info('tblriderlist', $udata, 'RiderlistID', $singlerider->RiderlistID);
					}
			  }
	  }	
  function mybirthday(){
	  $fromdate = date('Y-m-d');
	 $sqluser="SELECT * FROM tbluser Where UsersCategory=2 AND  DAY(DateBorn)=DAY('{$fromdate}') AND MONTH(DateBorn)=MONTH('{$fromdate}') AND UserIsActive=1";
	 $alluser= $this->db->query($sqluser)->result();
	
	  foreach($alluser as $singleuser){
		  $username =$singleuser->UserName; 
		  $userphone =$singleuser->PhoneMobile; 
		  SendSMS('88'.$userphone, $SMS ="Dear {$username}, Wish you a wonderful birthday and a year filled with health, happiness and success. Happy Birthday and best wishes. - www.foodmart.com.bd.");
		  }
	  }	
  function livechat()
	{
		$this->load->view("layout");
	}
	
function checkemail()
	{
$htmlContent=GetOrderTableForEmail('37262');
$config['mailtype'] = 'html';
$this->email->initialize($config);
$this->email->to('ainal2haque@gmail.com');
$this->email->from('order@foodmart.com.bd','Foodmart');
$this->email->subject('Test Email (HTML)');
$this->email->message($htmlContent);
$this->email->send();
	}
function updatelatlong(){
	 $latitude= $this->input->post("latitude");	
	 $longitude= $this->input->post("longitude");
	 $userid= $this->input->post("userid");
	 $dataup['customerlat']           	   = $latitude;
	 $dataup['customerlong']           	   = $longitude;
	 $this->Foodmart_model->update_info('tbluser', $dataup, 'UserID', $userid);
	}	
	/**
	 * This functions views company wall page
	 */
	 public function openmanager()
	{
		if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$data = array();
		$data['image'] ='image/placeholder.png';
		$data['placeholder'] ='image/placeholder.png';

		$thumb = 'placeholder.png';
		$data['thumb'] = $this->Foodmart_model->resize($thumb,100,100);
        //print_r($data);
		$this->load->view('addmanager',$data);
				}
	}
	public function falimanage() {
	if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$server = site_url();

		$filter_name = $this->input->get('filter_name');
		if (isset($filter_name)) {
			$filter_name = rtrim(str_replace('*', '', $filter_name), '/');
		} else {
			$filter_name = null;
		}

		// Make sure we have the correct directory
		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$directory = rtrim(DIR_IMAGE . 'application/' . str_replace('*', '', $directory), '/');
		} else {
			$directory = DIR_IMAGE . 'application';
		}
		$page = $this->input->get('page');
		if (isset($page)) {
			$page = $page;
		} else {
			$page = 1;
		}

		$directories = array();
		$files = array();

		$data['images'] = array();

		if (substr(str_replace('\\', '/', realpath($directory . '/')), 0, strlen(DIR_IMAGE . 'application')) == DIR_IMAGE . 'application') {
			// Get directories
			echo 'in';
			 $directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

			if (!$directories) {
				$directories = array();
			}

			// Get files
			$files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF,php,txt}', GLOB_BRACE);

			if (!$files) {
				$files = array();
			}
		}

		// Merge directories and files
		$images = array_merge($directories, $files);

		// Get total number of files and directories
		$image_total = count($images);

		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 16, 16);

		foreach ($images as $image) {
			$name = str_split(basename($image), 14);

			if (is_dir($image)) {
				$url = '';
				
				$target = $this->input->get('target');
				if (isset($target)) {
					$url .= '&target=' . $target;
				}
				$thumb = $this->input->get('thumb');
				if (isset($thumb)) {
					$url .= '&thumb=' . $thumb;
				}

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => site_url('foodmart/falimanage').'?directory=' .substr($image, strlen(DIR_IMAGE . 'application/')) . $url,
				);
			} elseif (is_file($image)) {
				$data['images'][] = array(
					'thumb' => $this->Foodmart_model->resize(substr($image, strlen(DIR_IMAGE)), 100, 100),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => $server. substr($image, strlen(DIR_IMAGE))
				);
			}
		}

		$data['heading_title'] = 'heading_title';

		$data['text_no_results'] = 'text_no_results';
		$data['text_confirm'] = 'text_confirm';

		$data['entry_search'] = 'entry_search';
		$data['entry_folder'] = 'entry_folder';

		$data['button_parent'] = 'button_parent';
		$data['button_refresh'] = 'button_refresh';
		$data['button_download'] = 'button_download';
		$data['button_upload'] = 'button_upload';
		$data['button_folder'] = 'button_folder';
		$data['button_delete'] = 'button_delete';
		$data['button_search'] = 'button_search';

		$data['token'] = 'token';
		
		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$data['directory'] = urlencode($directory);
		} else {
			$data['directory'] = '';
		}

		$filter_name = $this->input->get('filter_name');
		if (isset($filter_name)) {
			$data['filter_name'] = $filter_name;
		} else {
			$data['filter_name'] = '';
		}

		// Return the target ID for the file manager to set the value
		$target = $this->input->get('target');
		if (isset($target)) {
			$data['target'] = $target;
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		$thumb = $this->input->get('thumb');
		if (isset($thumb)) {
			$data['thumb'] = $thumb;
		} else {
			$data['thumb'] = '';
		}

		// Parent
		$url = '';

		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$pos = strrpos($directory, '/');

			if ($pos) {
				$url .= '&directory=' . urlencode(substr($directory, 0, $pos));
			}
		}

		$target = $this->input->get('target');
		if (isset($target)) {
			$url .= '&target=' . $target;
		}

		$thumb = $this->input->get('thumb');
		if (isset($thumb)) {
			$url .= '&thumb=' . $thumb;
		}

		$data['parent'] = site_url('foodmart/falimanage').'?token=token'. $url;

		// Refresh
		$url = '';

		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$url .= '&directory=' . urlencode($directory);
		}

		$target = $this->input->get('target');
		if (isset($target)) {
			$url .= '&target=' . $target;
		}

		$thumb = $this->input->get('thumb');
		if (isset($thumb)) {
			$url .= '&thumb=' . $thumb;
		}

		$data['refresh'] = site_url('foodmart/falimanage').'?token=token'.$url;

		$url = '';

		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$url .= '&directory=' . urlencode(html_entity_decode($directory, ENT_QUOTES, 'UTF-8'));
		}

		$filter_name = $this->input->get('filter_name');
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
		}
		$target = $this->input->get('target');
		if (isset($target)) {
			$url .= '&target=' . $target;
		}
		$thumb = $this->input->get('thumb');
		if (isset($thumb)) {
			$url .= '&thumb=' . $thumb;
		}

		//$pagination = new Pagination();
		//$pagination->total = $image_total;
		//$pagination->page = $page;
		//$pagination->limit = 16;
		//$pagination->url = site_url('common/filemanager').'?token=token'. $url . '&page={page}';
		//$data['pagination'] = $pagination->render();
		
		$config['base_url'] = site_url('foodmart/falimanage');
		$config['total_rows'] = $image_total;
		$config['per_page'] = 16;
		$config['page'] = $page;
		$config['url'] = $url;
		$data['pagination'] = $this->pagination($config);
       //print_r($data);
		$this->load->view('filemanager',$data);
	}
	}
	public function upload() {
	if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$json = array();
		
		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$directory = rtrim(DIR_IMAGE . 'application/' . $directory, '/');
		} else {
			$directory = DIR_IMAGE . 'application';
		}
		
		$config = array();
		$config['upload_path'] = $directory;
		$config['allowed_types'] = '*';
		//$config['max_size']      = '0';
		$config['overwrite']     = FALSE;

		$this->load->library('upload');

		$files = $_FILES;
		$total = count($files['file']['name']);
		unset($_FILES);
		
		for($i=0; $i< $total; $i++)
		{           
			$_FILES['file']['name']= $files['file']['name'][$i];
			$_FILES['file']['type']= $files['file']['type'][$i];
			$_FILES['file']['tmp_name']= $files['file']['tmp_name'][$i];
			$_FILES['file']['error']= $files['file']['error'][$i];
			$_FILES['file']['size']= $files['file']['size'][$i];    

			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('file'))
			{
				$json['error'] = $this->upload->display_errors();
			}
		}
		if(empty($json['error'])){
			$json['success'] = 'Successfull uploaded';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
	}
	
	public function folder() {
		if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url()."MyProfile");
				}
				else{
		//$this->load->language('common/filemanager');
		$json = array();
				
		//$json['server'] = $this->input->server('REQUEST_METHOD');
		


		// Check user has permission
		//if (!$this->user->hasPermission('modify', 'common/filemanager')) {
		//	$json['error'] = 'error_permission';
		//}

		// Make sure we have the correct directory
		$directory = $this->input->get('directory');
		if (isset($directory)) {
			$directory = rtrim(DIR_IMAGE . 'application/' . $directory, '/');
		} else {
			$directory = DIR_IMAGE . 'application';
		}

		// Check its a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_IMAGE . 'application')) != DIR_IMAGE . 'application') {
			$json['error'] = 'error_directory';
		}
			
			
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			// Sanitize the folder name
			$folder = basename(html_entity_decode($this->input->post('folder'), ENT_QUOTES, 'UTF-8'));

			$json['folder'] = $folder;
			// Validate the filename length
			if ((strlen($folder) < 3) || (strlen($folder) > 128)) {
				$json['error'] = 'error_folder';
			}

			// Check if directory already exists or not
			if (is_dir($directory . '/' . $folder)) {
				$json['error'] = 'error_exists';
			}
		}

		if (!isset($json['error'])) {
			mkdir($directory . '/' . $folder, 0777);
			chmod($directory . '/' . $folder, 0777);

			@touch($directory . '/' . $folder . '/' . 'index.html');

			$json['success'] = 'Direcory created';
		}

		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
	}

	public function download(){
		if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$json = array();
		$path = $this->input->post('path');
		if (isset($path)) {
			$paths = $path;
		} else {
			$paths = array();
		}
		
		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
			    $basepath=DIR_IMAGE.$path;
				$path = rtrim(DIR_IMAGE . $path, '/');
				$file = file_get_contents($basepath); 
$name = basename($basepath);
				// If path is just a file delete it
				if (is_file($path)) {
					//unlink($path);
					$this->load->helper('download');
					force_download($name, $file);
				}
			}

			$json['success'] = 'text_download';
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
		
		/*$picid = $_GET['picid'];
		$this->load->helper('download');
		$query2 ="select * from gallery Where gallery_id='".$picid."'";
		$result = mysql_query($query2)or die(mysql_error());
		$row = mysql_fetch_array( $result );
		$path = base_url()."upload/".$row['images'];
		$data = file_get_contents($path); // Read the file's contents
        $name = $row['images'];
		force_download($name, $data); */
		}	
	public function delete() {
		//$this->load->language('common/filemanager');
		 if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$json = array();

		// Check user has permission
		//if (!$this->user->hasPermission('modify', 'common/filemanager')) {
		//	$json['error'] = 'error_permission';
		//}

		$path = $this->input->post('path');
		if (isset($path)) {
			$paths = $path;
		} else {
			$paths = array();
		}

		// Loop through each path to run validations
		foreach ($paths as $path) {
			// Check path exsists
			if ($path == DIR_IMAGE . 'application' || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $path)), 0, strlen(DIR_IMAGE . 'application')) != DIR_IMAGE . 'application') {
				$json['error'] = 'error_delete';

				break;
			}
		}

		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$path = rtrim(DIR_IMAGE . $path, '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);

				// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = array();

					// Make path into an array
					$path = array($path . '*');

					// While the path array is still populated keep looping through
					while (count($path) != 0) {
						$next = array_shift($path);

						foreach (glob($next) as $file) {
							// If directory add to path array
							if (is_dir($file)) {
								$path[] = $file . '/*';
							}

							// Add the file to the files to be deleted array
							$files[] = $file;
						}
					}

					// Reverse sort the file array
					rsort($files);

					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);

						// If directory use the remove directory function
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
				}
			}

			$json['success'] = 'text_delete';
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
				}
	}
	public function pagination($data) {
		 if($this->session->userdata('UserID')== false)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
		$base_url = $data['base_url'];
		$total = $data['total_rows'];
		$per_page = $data['per_page'];
		$page = $data['page'];
		$url = $data['url'];
		$pages = intval($total/$per_page); if($total%$per_page != 0){$pages++;}
		$p="";
		for($i=1; $i<= $pages;$i++){
			$p .= '<a class="btn directory" href="'.$base_url.'?page='.$i.$url.'" >'.$i.'</a>';
		}
		return $p;
				}
	}
	function viewPost()
	{
		$this->load->view("chatbox");		
	}
	
	/**
	 * This function add post into DB
	 */
	function addPost() 
	{
	 	$postText =  $this -> input -> post("postText");	
		
		$rec = array('postText'=>$postText);
		
		$postId = $this->Foodmart_model->addPost($rec);
		
		$postData = $this->Foodmart_model->getPostById($postId);
		
		if ($postId > 0) {echo json_encode(array('status' => 'success', 'postData'=>$postData[0]));}
		else {echo json_encode(array('status' => 'error'));}
	}
	
	function getPosts()
	{
		$posts = $this->Foodmart_model->getPosts();	
		echo json_encode(array('posts'=>$posts));
	}
}
