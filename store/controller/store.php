<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foodmartstore extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	  public function __construct() {
        parent::__construct();
		$this->load->library('facebook');
        date_default_timezone_set('Asia/Dhaka');
    }

	public function index()
	{
	  $data['title']="Welcome to Foodmart Store";
	  $currentid=$this->session->userdata('CusUserID');
	  $data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
	  $data['CurrentUser']=$currentid;
	  $data['slider_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','1');
	  $data['slider_bottom']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','2');
	  $data['slider_hot']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','12');
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['feature_prod']=  $this->Foodmartstore_model->featured();
		$data['new_prod']=  $this->Foodmartstore_model->newproduct();
		$data['best_prod']=  $this->Foodmartstore_model->bestsalerproduct();
        $data['content']=$this->load->view('fontpage',$data,TRUE);
		$this->load->view('index',$data);
	}
	public function quickview(){
		$pid=$this->input->post('pid');
		$currentid=$this->session->userdata('CusUserID');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['CurrentUser']=$currentid;
		$data['quickview']=  $this->Foodmartstore_model->readgroup($pid);
		$data['avaragerating']=$this->Foodmartstore_model->read_avarage('tbl_cusreview','reviewrate','pid',$pid);
		$data['productgallery']=  $this->Foodmartstore_model->read_all('*', 'tbl_galleryimg','gallerimgid','delation_status','gallerid',$pid);
		$this->load->view('quickview',$data);
		}
	public function category($id)
	{
		$currentid=$this->session->userdata('CusUserID');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['CurrentUser']=$currentid;
		$getcatid=  $this->input->post('catid',TRUE);
		$minprice=  $this->input->post('minprice',TRUE);
		$maxprice=  $this->input->post('maxprice',TRUE);
		$filterdata['category']= "";
		$filterdata['color']= "";
		
		$data['title']="FoodmartStore Category";
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$categoryid = $this->Foodmartstore_model->read('menucatid', 'tbl_menucategory', array('catslug' => $id));
		if(!empty($categoryid)){
		$data['catidwithpro']=$categoryid->menucatid;
		$data['sluginfo']=$id;
		$data['cat_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid',$categoryid->menucatid);
		//$data['categoryproduct']=  $this->Foodmartstore_model->read_all_catproduct('*', 'tbl_menuitem','menuid','delation_status','catids',$categoryid->menucatid);
		$this->load->library('pagination');
        $config['base_url'] = base_url()."Foodmartstore/category/".$id;
        //$config['total_rows'] = $this->db->count_all('tbl_menuitem');
		$config['total_rows'] =$this->Foodmartstore_model->record_count($categoryid->menucatid);
        $config['per_page'] = '9';
        $config['full_tag_open']='<ul class="pagination pagination-md">';
        $config['full_tag_close']='</ul>';
		$config['first_link'] = false;
		$config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = '<i class="icofont icofont-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
		$config['prev_link'] = '<i class="icofont icofont-long-arrow-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
		$config['last_link'] =false;
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
		$curpage = floor(($this->uri->segment(4)/$config['per_page']) + 1); 
		$result_start = ($curpage - 1) * $config['per_page'] + 1;
		if ($result_start == 0) $result_start= 1;
		
		$result_end = $result_start+$config['per_page']-1;
		if ($result_end < $config['per_page'])
    	$result_end = $config['per_page'];  
		else if ($result_end > $config['total_rows'])
    	$result_end = $config['total_rows'];
		$data['startn']=$result_start;
		$data['endn']=$result_end;
		$data['totalp']=$config['total_rows'];
		
		//$config['uri_segment'] = 3;
		//$page = ( $this->uri->segment( 3 ) ) ? $this->uri->segment( 3 ) : 0;
		 $maxmin = $this->Foodmartstore_model->readmaxmin($categoryid->menucatid);
		 if(empty($maxmin->Minp)){
			 $data['maxprice']=  0;
		     $data['minprice']=  0;
			 $minprice=0;
			 $maxprice=0;
			 }
	    else{
		$data['maxprice']=  $maxmin->Maxp;
		$data['minprice']=  $maxmin->Minp;
			$minprice=$data['minprice'];
			$maxprice=$data['maxprice'];
		}
        
        $this->pagination->initialize($config);
        $data['categoryproduct'] = $this->Foodmartstore_model->read_all_catproduct($config['per_page'], $this->uri->segment(4),$categoryid->menucatid,$minprice,$maxprice,$filterdata);
		
		$data['content']=$this->load->view('categorypage',$data,TRUE);
		$this->load->view('index',$data);
		}
		else{
			header("Location: ".$this->config->base_url());
			}
	}
	public function search()
	{
		$currentid=$this->session->userdata('CusUserID');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['CurrentUser']=$currentid;
		$id= $this->input->post('searchr',TRUE);
		$getcatid=  $this->input->post('catid',TRUE);
		$minprice=  $this->input->post('minprice',TRUE);
		$maxprice=  $this->input->post('maxprice',TRUE);
		$filterdata['category']= "";
		$filterdata['color']= "";
		
		$data['title']="FoodmartStore Category";
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$categoryid = $this->Foodmartstore_model->readsearch($id);
		if(!empty($categoryid)){
		$data['cat_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','','');
        $data['categoryproduct'] = $this->Foodmartstore_model->readsearch($id);
		
		$data['content']=$this->load->view('topsearchresult',$data,TRUE);
		$this->load->view('index',$data);
		}
		else{
			header("Location: ".$this->config->base_url());
			}
	}
	public function pricefilter()
	{
		$currentid=$this->session->userdata('CusUserID');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['CurrentUser']=$currentid;
		$id=  $this->input->post('catid',TRUE);
		$minprice=  $this->input->post('minprice',TRUE);
		$maxprice=  $this->input->post('maxprice',TRUE);
		$filterdata['category']= $this->input->post('category',TRUE);
		$filterdata['color']= $this->input->post('color',TRUE);
		$data['title']="FoodmartStore Category";
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$categoryid = $this->Foodmartstore_model->read('menucatid', 'tbl_menucategory', array('catslug' => $id));
		if(!empty($categoryid)){
		$data['catidwithpro']=$categoryid->menucatid;
		$data['sluginfo']=$id;
		$data['cat_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid',$categoryid->menucatid);
		//$data['categoryproduct']=  $this->Foodmartstore_model->read_all_catproduct('*', 'tbl_menuitem','menuid','delation_status','catids',$categoryid->menucatid);
		$this->load->library('pagination');
        $config['base_url'] = base_url()."Foodmartstore/category/".$id;
        //$config['total_rows'] = $this->db->count_all('tbl_menuitem');
		$config['total_rows'] =$this->Foodmartstore_model->record_count2($categoryid->menucatid,$maxprice,$minprice,$filterdata);
        $config['per_page'] = '9';
        $config['full_tag_open']='<ul class="pagination pagination-md">';
        $config['full_tag_close']='</ul>';
		$config['first_link'] = false;
		$config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = '<i class="icofont icofont-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
		$config['prev_link'] = '<i class="icofont icofont-long-arrow-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
		$config['last_link'] =false;
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
		$curpage = floor(($this->uri->segment(4)/$config['per_page']) + 1); 
		$result_start = ($curpage - 1) * $config['per_page'] + 1;
		if ($result_start == 0) $result_start= 1;
		
		$result_end = $result_start+$config['per_page']-1;
		if ($result_end < $config['per_page'])
    	$result_end = $config['per_page'];  
		else if ($result_end > $config['total_rows'])
    	$result_end = $config['total_rows'];
		$data['startn']=$result_start;
		$data['endn']=$result_end;
		$data['totalp']=$config['total_rows'];
		
		//$config['uri_segment'] = 3;
		//$page = ( $this->uri->segment( 3 ) ) ? $this->uri->segment( 3 ) : 0;
		 $maxmin = $this->Foodmartstore_model->readmaxmin($categoryid->menucatid);
		$data['maxprice']=  $maxmin->Maxp;
		$data['minprice']=  $maxmin->Minp;
		if(empty($minprice)){
			$minprice=$data['minprice'];
			}
	    else{
			$minprice=$minprice;
			}
		if(empty($maxprice)){
			$maxprice=$data['maxprice'];
			}
	    else{
			$maxprice=$maxprice;
			}
        
        $this->pagination->initialize($config);
        $data['categoryproduct'] = $this->Foodmartstore_model->read_all_catproduct($config['per_page'], $this->uri->segment(4),$categoryid->menucatid,$minprice,$maxprice,$filterdata);
		//print_r($data['categoryproduct']);
		$this->load->view('searchfilter',$data);
		}
		else{
			header("Location: ".$this->config->base_url());
			}
			
	}
	public function details($id,$page="",$limit="")
	{
		$currentid=$this->session->userdata('CusUserID');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['CurrentUser']=$currentid;
		$data['title']="FoodmartStore Product Details";
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$productinfo = $this->Foodmartstore_model->productdetails($id);
		if(!empty($productinfo)){
		$proid=$productinfo->proid;
		$catid=$productinfo->catids;
		$data['totalreview']=$this->Foodmartstore_model->record_allrows($proid,'pid','tbl_cusreview');
		$data['allreviews']=$this->Foodmartstore_model->read_all('*', 'tbl_cusreview','reviewid','status','pid',$proid);
		$data['avaragerating']=$this->Foodmartstore_model->read_avarage('tbl_cusreview','reviewrate','pid',$proid);
		$data['related_prod']=  $this->Foodmartstore_model->relatedproduct($proid,$catid);
		$data['related_prodcount']=  $this->Foodmartstore_model->relatedproductcount($proid,$catid);
		$mycats= explode(',', $catid);
		$allcatname='';
		foreach($mycats as $mycatid){
			$catinfo=$this->Foodmartstore_model->read('*', 'tbl_menucategory', array('menucatid' => $mycatid));
			$allcatname.=$catinfo->mecatname.",";
		}
		$data['allcategory']=rtrim($allcatname,',');
		$data['pgallery']=  $this->Foodmartstore_model->read_all('*', 'tbl_galleryimg','gallerimgid','delation_status','gallerid',$proid);
		$data['productdata']=$productinfo;
        $data['content']=$this->load->view('product-details',$data,TRUE);
		$this->load->view('index',$data);
		}
		else{
			header("Location: ".$this->config->base_url());
			}
	}
	public function submitreview(){
				$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
				$reviewrate=round($this->input->post('input-3'));
				$proid=$this->input->post('prid');
				$userid=$this->input->post('userid');
				$existsreview = $this->Foodmartstore_model->read('*', 'tbl_cusreview', array('pid' => $proid,'reviewuserid'=>$userid));
				if(!$existsreview){
				$datain['reviewrate']       			   = $reviewrate;
				$datain['pid']       			   		   = $this->input->post('prid');
				$datain['reviewuserid']    				   = $this->input->post('userid');
			    $datain['rname']                       	   = $this->input->post('fname');
				$datain['reviewnotes']   	      		   = $this->input->post('comment');
				$datain['email']   	   		               = $this->input->post('email');
				$datain['review_date']   	   			   = date('Y-m-d H:i:s');
				$this->Foodmartstore_model->insert_data('tbl_cusreview', $datain);
				$data['totalreview']=$this->Foodmartstore_model->record_allrows($proid,'pid','tbl_cusreview');
				$data['allreviews']=$this->Foodmartstore_model->read_all('*', 'tbl_cusreview','reviewid','status','pid',$proid);
				$data['productdata']=$this->Foodmartstore_model->readgroup($proid);
				$this->load->view('reviewlist',$data);
				}
				else{
					echo "404";
					}
		}
	public function profile(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		if($this->session->userdata('CusUserID')!=FALSE){
			$data['title']="FoodmartStore User Profile";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['profileinfo']=$this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' => $currentid));
			$data['content']=$this->load->view('profile',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
			header("Location: ".$this->config->base_url());
			}
		}
	public function profileupdate(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		if($this->session->userdata('CusUserID')!=FALSE){
			$data['title']="FoodmartStore User Profile";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['userinfo']=$this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' => $currentid));
			$data['content']=$this->load->view('profileupdate',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
			header("Location: ".$this->config->base_url());
			}
		}
	public function profileupdatesubmit(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$currentid=$this->session->userdata('CusUserID');
			$fname=$this->input->post('fname');
			$lname=$this->input->post('lname');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$Postcode=$this->input->post('Postcode');
			$city=$this->input->post('city');
			$ins['cus_firstname']=$fname;
			$ins['cus_lastname']=$lname;
			$ins['cus_email']=$email;
			$ins['cus_phone']=$phone;
			$ins['zipcode']=$Postcode;
			$ins['cityname']=$city;
			
			$result=$this->Foodmartstore_model->read('*', 'tbl_customer', array('cus_email' => $email));
			$total=count($result);
			if($total>1){
				echo "404";
			//echo 'This Product is exits please  choose another!!!';
			}
			else{
				$config['upload_path']          = 'uploads/profile/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
				$result2=$this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' => $currentid));
                $image=$this->upload->do_upload('profileimg');
				$path_info = pathinfo($result2->profileimg );
				$ext=$path_info['extension']; 
				$withoutext = pathinfo($result2->profileimg, PATHINFO_FILENAME);
				$getimg=$withoutext."_thumb.".$ext;
				
                if($image != ""){
                    if($result2->profileimg){
					    unlink("uploads/profile/".$result2->profileimg);
						unlink("uploads/profile/".$getimg);
            		}
                
					$fdata =$this->upload->data();
					$config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => TRUE,
				    'thumb_marker' => '_thumb',
				    'width' => 330,
				    'height' => 471
				);
				    $this->load->library('image_lib', $config1);
                    $this->image_lib->resize();
        			$ins['profileimg']=$fdata['file_name'];
                }
                else 
                {
                   $ins['profileimg']=$result2->profileimg;
                }
                $this->Foodmartstore_model->update_date('tbl_customer', $ins, 'custid', $currentid);
			}
			
		}
	public function vieworderinlist(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		if($this->session->userdata('CusUserID')!=FALSE){
			$data['title']="FoodmartStore Order List";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['all_orders']=  $this->Foodmartstore_model->allorderlist();
			$data['content']=$this->load->view('orderlistview',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
			header("Location: ".$this->config->base_url());
			}
		}
	public function singleorder($id){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		if($this->session->userdata('CusUserID')!=FALSE){
			$data['title']="FoodmartStore Order List";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['all_orders']=  $this->Foodmartstore_model->singleorder($id);
			$data['content']=$this->load->view('ordersingle',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
			header("Location: ".$this->config->base_url());
			}
		}
	public function signup(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			if($this->session->userdata('CusUserID')==FALSE){
			$data['title']="FoodmartStore User Registration Page";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['content']=$this->load->view('register',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
			header("Location: ".$this->config->base_url());
			}
		}
	public function registration(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$Firstname = $this->input->post('Firstname');
			$Lastname = $this->input->post('Lastname');
			$email = $this->input->post('EmailAddress');
			$phonenumber = $this->input->post('UserPhoneNumber');
			$pass1 = $this->input->post('Userpassword');
		
		//$this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
        $this->form_validation->set_rules('Firstname','Firstname', 'required|trim');
		$this->form_validation->set_rules('Lastname','Lastname', 'required|trim');
		$this->form_validation->set_rules('EmailAddress','User Email', 'required|valid_email|trim|xss_clean|is_unique[tbl_customer.cus_email]');
		$this->form_validation->set_rules('UserPhoneNumber','Phone Number', 'required|trim|xss_clean|is_unique[tbl_customer.cus_phone]');
        $this->form_validation->set_rules('Userpassword','Password', 'required|trim|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
		$fulname=$this->input->post('Firstname')." ".$this->input->post('Lastname');
        $password = md5($pass1);
			if($this->form_validation->run()==FALSE)
			{
				echo validation_errors();
			}
			else{
				$datain['cus_firstname']       			   = $this->input->post('Firstname');
				$datain['cus_lastname']       			   = $this->input->post('Lastname');
				$datain['cus_password']    				   = $password;
			    $datain['cus_email']                       = $this->input->post('EmailAddress');
				$datain['cus_phone']   	      		   	   = $this->input->post('UserPhoneNumber');
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Foodmartstore_model->insert_data('tbl_customer', $datain);
				if($insert_ID > 0){ 
				$usersession = $this->Foodmartstore_model->read('custid,cus_firstname,cus_lastname,cus_email', 'tbl_customer', array('custid' => $insert_ID));
					$sessiondata = array(
					'CusUserID' =>$usersession->custid,
					'cusfname' =>$usersession->cus_firstname,
					'cuslname' =>$usersession->cus_lastname,
					'CustomerEmail' =>$usersession->cus_email
					);
				$this->session->set_userdata($sessiondata);
				$udata['DateUpdated']=date('Y-m-d H:i:s');
				$this->Foodmartstore_model->update_info('tbl_customer', $udata, 'custid', $insert_ID);
		 		}
		
			// sent welcome email.
			$ToEmail=$_POST["EmailAddress"];
			$htmlContent="Hi {$fulname},
					<br><br>
					
					Thank you for joining with foodmartstore. We take great pleasure in welcoming you to foodmartstore family.
					Now you can order your Accessories from Our favorite Store. We serve more the 500+ Product to your doorsteps. You get to enjoy Product quality from your zone. We provide you excellent delivery service with good quality. Our intention is to achieve your 100% satisfaction. 
					<br><br>
					Support Team<br>
					Foodmartstore <br>
					www.foodmartstore.com  <br>
					Hotline:01793111121<br>
				";
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->to($ToEmail);
			$this->email->from('info@foodmartstore.com','FoodmartStore');
			$this->email->subject('Product order Information');
			$this->email->message($htmlContent);
			$this->email->send();
			echo '<div class="alert alert-success">
  <strong>Success!</strong> Your Registration Successfully Completed.
</div>
';
			}
			
		}
	public function login(){
			$data['title']="FoodmartStore User Login";
			$currentid=$this->session->userdata('CusUserID');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['content']=$this->load->view('login',$data,TRUE);
			$this->load->view('index',$data);
		}
	public function loginsubmit(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
            $username = $this->input->post('Email');
            $password = md5($this->input->post('pass1'));
            $cek = $this->Foodmartstore_model->loginUser($username, $password);
            if($cek <> 0)
            {
				$myid = $this->session->userdata('CusUserID');
				echo  "Success";
            }
            else
            {
              echo "404";
			   
            }
        
    }
	public function changepass(){
			$data['title']="Change Password";
			$currentid=$this->session->userdata('CusUserID');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['content']=$this->load->view('changepass',$data,TRUE);
			$this->load->view('index',$data);
		}
		
	public function changepasssubmit(){
			if($this->session->userdata('CusUserID')== FALSE)
				{
					header("Location: ".$this->config->base_url());
				}
				else{
					 $currentid=$this->session->userdata('CusUserID');
					 $userinfo= $this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' => $currentid));
					 $oldPass=$this->input->post('OldPassword');
					 $newPass=$this->input->post('NewPassword');
					 $aouth= $this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' =>  $currentid,'cus_password'=>md5($oldPass)));
					 if(count($aouth)>0){
					 $passupdate['cus_password'] 	= md5($newPass);
					 $this->Foodmartstore_model->update_info('tbl_customer', $passupdate, 'custid', $currentid);
					 echo 1;
					 }
					}
		}
	public function logincheckout(){
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
            $username = $this->input->post('Email');
            $password = md5($this->input->post('pass1'));
            $cek = $this->Foodmartstore_model->loginUser($username, $password);
            if($cek <> 0)
            {
				$myid = $this->session->userdata('CusUserID');
				echo  "Success";
            }
            else
            {
              echo "404";
			   
            }
        
    }
	public function logout(){
		$myid = $this->session->userdata('CusUserID');
		$this->session->unset_userdata('CusUserID');
		$this->session->unset_userdata('cusfname');
		$this->session->unset_userdata('cuslname');
		$this->session->unset_userdata('CustomerEmail');
		//$this->session->sess_destroy();
		header("Location: ".$this->config->base_url());
	}
	public function addtocart(){
		$currentid=$this->session->userdata('UserID');
		$productID=$this->input->post('ProductID');
		$qty=$this->input->post('qty');
		$size=$this->input->post('size');
		$color=$this->input->post('color');
		$price=$this->input->post('price');
		$productinfo= $this->Foodmartstore_model->readgroup($productID);
		$productName=$productinfo->productname;
		$discount=$productinfo->discount;
		$vat=$productinfo->vat;
		$mainprice=$productinfo->sell_rate;
		$myid=$productID."_".$size;
		$vatprice=0;
		$discountprice=0;
		if($discount>0){
			$discountprice=$price*$discount/100;
		}
		if($vat>0){
			$vatprice=$price*$vat/100;
		}
		$nitprice=($price+$vatprice)-$discountprice;
		if(count($this->cart->contents())>0){
				  foreach ($this->cart->contents() as $item){
                        if($item['id']==$myid){
							$data = array(
								'rowid'=>$item['rowid'],
								'qty'=>$item['qty']+1
								);
								
								$this->cart->update($data);
							}
							else{
								$insert_data = array(
								'id' 	      => $myid,
						        'proid' 	  => $productID,
								'name' 		  => $productName,
								'price' 	  => $price,
								'actualprice' => $price,
								'discount'    => $discount,
								'size'        => $size,
								'color'    	  => $color,
								'vat'         => $vat,
								'qty'         => $qty
							);
							$this->cart->insert($insert_data);
								}
							
				  }
				  $this->load->view('cartviewpop');
			}
		else{
			$insert_data = array(
						'id' 	      => $myid,
						'proid' 	  => $productID,
						'name' 		  => $productName,
						'price' 	  => $price,
						'actualprice' => $mainprice,
						'discount'    => $discount,
						'size'        => $size,
						'color'    	  => $color,
						'vat'         => $vat,
						'qty'         => $qty
					);
			$this->cart->insert($insert_data);
			$this->load->view('cartviewpop');
		}
		}
	public function updatecart(){
		$currentid=$this->session->userdata('UserID');
		$cartID=$this->input->post('CartID');
		$productqty=$this->input->post('qty');
			$data = array(
				'rowid'=>$cartID,
				'qty'=>$productqty
				);
				$this->cart->update($data);
			$this->load->view('cartviewupdate');
		}
	public function deleteItem(){
		$cartID=$this->input->post('CartID');
		$data = array(
				'rowid'   => $cartID,
				'qty'     => 0
			);
		$this->cart->update($data);
		$this->load->view('cartviewpop');
	}
	public function deleteItemcartpage(){
		$cartID=$this->input->post('CartID');
		$data = array(
				'rowid'   => $cartID,
				'qty'     => 0
			);
		$this->cart->update($data);
		$this->load->view('cartviewupdate');
	}
	public function cartclear(){
		$this->cart->destroy();
	}
	public function cart(){
			$data['title']="FoodmartStore User Login";
			$currentid=$this->session->userdata('CusUserID');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['content']=$this->load->view('cart',$data,TRUE);
			$this->load->view('index',$data);
		}
	public function checkout(){
			$data['title']="FoodmartStore User Login";
			$currentid=$this->session->userdata('CusUserID');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			if($currentid!=""){
				$data['userinfo']= $this->Foodmartstore_model->read('*', 'tbl_customer', array('custid' => $currentid));
				$data['usershipping']= $this->Foodmartstore_model->read2('*', 'tbl_delivaryaddress','shipaddressid', array('userid' => $currentid));
				}
			
			if(count($this->cart->contents())>0){
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['content']=$this->load->view('checkout',$data,TRUE);
			$this->load->view('index',$data);
			}
			else{
				header("Location: ".$this->config->base_url());
				}
		}
	public function confirm(){
			$currentid=$this->session->userdata('CusUserID');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$fname=$this->input->post('fname');
			$lname=$this->input->post('lname');
			$email=$this->input->post('email');
			$phone=$this->input->post('phone');
			$country=$this->input->post('country');
			$address=$this->input->post('address');
			$Postcode=$this->input->post('Postcode');
			$city=$this->input->post('city');
			$create=$this->input->post('create-ac');
			$shipping=$this->input->post('shipping-method');
			$order_note=$this->input->post('order-note');
			$paymentmethod=$this->input->post('payment-method');
			
			$fullname=$fname." ".$lname;
			
			if($create!="1"){
				$password=$this->input->post('password');
				$datain['cus_firstname']       			   = $fname;
				$datain['cus_lastname']       			   = $lname;
				$datain['cus_password']    				   = md5($password);
			    $datain['cus_email']                       = $email;
				$datain['cus_phone']   	      		   	   = $phone;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Foodmartstore_model->insert_data('tbl_customer', $datain);
				$currentid=$insert_ID;
				if($insert_ID > 0){ 
				$usersession = $this->Foodmartstore_model->read('custid,cus_firstname,cus_lastname,cus_email', 'tbl_customer', array('custid' => $insert_ID));
					$sessiondata = array(
					'CusUserID' =>$usersession->custid,
					'cusfname' =>$usersession->cus_firstname,
					'cuslname' =>$usersession->cus_lastname,
					'CustomerEmail' =>$usersession->cus_email
					);
				$this->session->set_userdata($sessiondata);
				$udata['DateUpdated']=date('Y-m-d H:i:s');
				}
			}
			//Shipping address Save
			$insertshipping['userid']       			   = $currentid;
			$insertshipping['firstname']       			   = $fname;
			$insertshipping['lastname']       			   = $lname;
			$insertshipping['email']    				   = $email;
			$insertshipping['phone']                       = $phone;
			$insertshipping['city']   	      		   	   = $city;
			$insertshipping['country']   	      		   = $country;
			$insertshipping['zip']   	      		   	   = $Postcode;
			$insertshipping['address']   	      		   = $address;
			$insertshipping['DateInserted']   	   		   = date('Y-m-d H:i:s');
			$insertshipping_ID = $this->Foodmartstore_model->insert_data('tbl_delivaryaddress', $insertshipping);
			//cart information 
			$subtotal=0;
			if($this->cart->contents()>0){
				$discount=0;
				$discountamount=0;
				$vat=0;
				$vatamount=0;
				foreach ($this->cart->contents() as $item){
				$discountprice=$item['price']*$item['discount']/100;
				$vatprice=$item['price']*$item['vat']/100;
				$discount=$discount+$item['discount'];
				$discountamount=$discountamount+$discountprice;
				$vat=$vat+$item['vat'];
				$vatamount=$vatamount+$vatprice;
				$subtotal=$subtotal+$item['price']*$item['qty'];	
				}
			}
			$grandtotal=$subtotal+$vatamount-$discountamount;
			
			//Order Info save
			$insertsorder['customerid']       		   	       = $currentid;
			$insertsorder['subtotal']       		           = $subtotal;
			$insertsorder['grandtotal']    			   	       = $grandtotal;
			$insertsorder['vat']                               = $vat;
			$insertsorder['servicecharge']   	      	       = "";
			$insertsorder['shippingmethod']   	      	   	   = $shipping;
			$insertsorder['shippingid']   	      		       = $insertshipping_ID;
			$insertsorder['paymentmethod']   	      		   = $paymentmethod;
			$insertsorder['orderdate']   	      		       = date('Y-m-d H:i:s');
			$insertsorder['status']   	      		           = "0";
			$insertsorder['UserIDInserted']   	      		   = $currentid;
			$insertsorder['UserIDUpdated']   	      		   = $currentid;
			$insertsorder['UserIDLocked']   	      		   = $currentid;
			$insertsorder['DateInserted']   	      		   = date('Y-m-d H:i:s');
			$insertsorder['DateUpdated']   	      		       = date('Y-m-d H:i:s');
			$insertsorder['DateLocked']   	      		   	   = date('Y-m-d H:i:s');
			$orderid_ID = $this->Foodmartstore_model->insert_data('tbl_orders', $insertsorder);
			$tabletr='';
			if($this->cart->contents()>0){
				foreach ($this->cart->contents() as $item){
					$insertdetails['OrdID']       		   	           = $orderid_ID;
					$insertdetails['ProductID']       		   	       = $item['proid'];
					$insertdetails['Price']       		               = $item['price'];
					$insertdetails['psize']    			   	           = $item['size'];
					$insertdetails['pcolor']                           = $item['color'];
					$insertdetails['Discount']   	      	           = $item['discount'];
					$insertdetails['pvat']   	      	               = $item['vat'];
					$insertdetails['Quantity']   	      	   		   = $item['qty'];
					$insertdetails['UserIDInserted']   	      		   = $currentid;
					$insertdetails['UserIDUpdated']   	      		   = $currentid;
					$insertdetails['UserIDLocked']   	      		   = $currentid;
					$insertdetails['DateInserted']   	      		   = date('Y-m-d H:i:s');
					$insertdetails['DateUpdated']   	      		   = date('Y-m-d H:i:s');
					$insertdetails['DateLocked']   	      		   	   = date('Y-m-d H:i:s');
					$this->Foodmartstore_model->insert_data('tbl_orderdetails', $insertdetails);
					$tabletr.='<tr>
					<td>'.$item['name'].'</td>
					<td>'.$item['qty'].'</td>
					<td>'.$item['price'].'</td>
					<td>'.$item['price']*$item['qty'].'</td>
					</tr>';
				}
			}
			$tabledata='
			<table class="table" border="1">
							<thead class="thead-inverse">
								<tr>
									<th>Product Name</th>
									<th>Quantity</th>
									<th>Price</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
                            	'.$tabletr.'
								<tr><td colspan="4">Total:'.$grandtotal.'</td>
								</tr>							
							</tbody>
						</table>
			';
			$ToEmail=$email;
			$htmlContent="Hi {$fullname},
					<br><br>
					
					Thank you for You Order. Your order Details is:.<br/>
					{$tabledata}
					<br><br>
					Support Team<br>
					Foodmartstore <br>
					www.foodmartstore.com  <br>
					Hotline:01793111121<br>
				";
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->to($ToEmail);
			$this->email->from('info@foodmartstore.com','FoodmartStore');
			$this->email->subject('Product order Information');
			$this->email->message($htmlContent);
			$this->email->send();
			
			$this->cart->destroy();
			echo $orderid_ID;
		}
	public function Ordersubmit($id){
			$data['title']="FoodmartStore Order Success";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$data['content']=$this->load->view('ordersubmit',$data,TRUE);
			$this->load->view('index',$data);
		}	
	public function contact(){
			$data=array();
			$data['title']="Contact Us";
			$currentid=$this->session->userdata('CusUserID');
			$data['CurrentUser']=$currentid;
			$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
			$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
			$data['branches']=$this->Foodmartstore_model->read_all('*', 'tbl_branch','branchid','','','');
			$data['content']=$this->load->view('contact',$data,TRUE);
			$this->load->view('index',$data);
		}
	public function contactfrm()
        {
			$username =$this->input->post('name',TRUE);
			 $email = $this->input->post('email',TRUE);
			  $phone = $this->input->post('phone',TRUE);
			   $subject = $this->input->post('subject',TRUE);
			 $message2 = $this->input->post('message',TRUE);
			 $body = "Name:".$username."<br>"."Email:".$email."<br>"."Phone:".$phone."<br><br>"."Subject:".$subject."<br><br>".$message2;
 
			$to = "foodmartbd@gmail.com";
		   $subject = $setingdata->consubject;
		
		
		
		$emailfrom = $email;
   
   $message = $body;                       
   $header = "From:'".$emailfrom."' \r\n";
   $header = "Cc:'".$emailfrom."' \r\n";
   $header .= "MIME-Version: 1.0\r\n";
   $header .= "Content-type: text/html\r\n";
   $retval = mail ($to,$subject,$message,$header);
    if( $retval == true )
  {
    "";
   }
  else{
    "";
  }
			 echo $sdata['message']='Message Send Successfully !!!';
          redirect('contact?send=success');
        }
//genar Pages
	public function Aboutus(){
		$data['title']="About";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_about']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','4');
		$data['about_content']=$this->Foodmartstore_model->read('*', 'tbl_pagecontent', array('pagesid' => 1));
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('aboutus',$data,TRUE);
		$this->load->view('index',$data);
		}
	public function Faq(){
		$data['title']="Faq";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_faq']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','5');
		$data['faq_content']=$this->Foodmartstore_model->read_all('*', 'tbl_pagecontent','pagecontentid','','pagesid','2');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('faq',$data,TRUE);
		$this->load->view('index',$data);
		}
	public function privacy(){
		$data['title']="Privacy-Policy";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_privacy']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','7');
		$data['Privacy_content']=$this->Foodmartstore_model->read('*', 'tbl_pagecontent', array('pagesid' => 4));
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('privacy',$data,TRUE);
		$this->load->view('index',$data);
		}
	public function career(){
		$data['title']="Career";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_career']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','6');
		$data['career_content']=$this->Foodmartstore_model->read('*', 'tbl_pagecontent', array('pagesid' => 6));
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('career',$data,TRUE);
		$this->load->view('index',$data);
		}
	public function terms(){
		$data['title']="Terms and Condition";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_terms']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','8');
		$data['terms_content']=$this->Foodmartstore_model->read('*', 'tbl_pagecontent', array('pagesid' => 5));
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('terms',$data,TRUE);
		$this->load->view('index',$data);
		}
  public function businesspartner(){
		$data['title']="Business Partner";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_partner']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','9');
		$data['our_partner']=  $this->Foodmartstore_model->read_all('*', 'tbl_partner_logo','partnerid','','','');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('ourclients',$data,TRUE);
		$this->load->view('index',$data);
		}
	
 public function becomeapartner(){
		$data['title']="Business Partner";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_partner']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','11');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('applypartner',$data,TRUE);
		$this->load->view('index',$data);
		}
 public function submitpartner()
        {
		$businessname =$this->input->post('businessname',TRUE);
		$email = $this->input->post('email',TRUE);
		$phone = $this->input->post('phone',TRUE);
		$representname = $this->input->post('representname',TRUE);
		$insertdetails['businessname']       		   	   = $businessname;
		$insertdetails['email']       		       		   = $email;
		$insertdetails['phonenumber']    			   	   = $phone;
		$insertdetails['representative']                   = $representname;
		$insertdetails['status']   	      		   		   = "0";
		$insertdetails['entrydate']   	      		   	   = date('Y-m-d H:i:s');
		$this->Foodmartstore_model->insert_data('tbl_partner', $insertdetails);
		
		$message2="Thany You For your interest.We will Contact You as soon as possible.";
		$body = "Business Name:".$businessname."<br>"."Email:".$email."<br>"."Phone:".$phone."<br><br>"."Representative Name:".$representname."<br><br>".$message2;
		
		$to = "foodmartbd@gmail.com";
		$subject ="Become A Foddmartstore Partner";
		$emailfrom = $email;
   
	   $message = $body;                       
	   $header = "From:'".$emailfrom."' \r\n";
	   $header = "Cc:'".$emailfrom."' \r\n";
	   $header .= "MIME-Version: 1.0\r\n";
	   $header .= "Content-type: text/html\r\n";
	   $retval = mail ($to,$subject,$message,$header);
		if( $retval == true )
	  {
		"";
	   }
	  else{
		"";
	  }
		$sdata['message']='Message Send Successfully !!!';
		$this->session->set_userdata($sdata);
		redirect('Partnar-registration');
	}
 	public function whychoose(){
		$data['title']="Why Choose";
		$currentid=$this->session->userdata('CusUserID');
		$data['CurrentUser']=$currentid;
		$data['menu_info']=  $this->Foodmartstore_model->read_all('*', 'tbl_menucategory','menucatid','delation_status','parentid','0');
		$data['slider_whychoose']=  $this->Foodmartstore_model->read_all('*', 'tbl_slider','slid','delation_status','Sltypeid','10');
		$data['contactinfo']=$this->Foodmartstore_model->read('*', 'tbl_setting', array('setting_id' => 1));
		$data['content']=$this->load->view('whychoose',$data,TRUE);
		$this->load->view('index',$data);
		}
}
