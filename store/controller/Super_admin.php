<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Super_admin
 *
 * @author linktech
 */
class Super_Admin extends CI_Controller{
    public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Dhaka');
		$admin_id="";
		if($this->session->userdata('userid')==false){
			 $admin_id=$this->session->userdata('employeid');
			}
		else if($this->session->userdata('employeid')==false){
			$admin_id=$this->session->userdata('userid');
			}
        if($admin_id==NULL)
        {
            redirect('Admin-Login-Check-And-Refresh','refresh');
        }
    }
    //put your code here
    public function index()
    {
        $data=array();
        $data['title']='Dashboard';
		$data['totalregister']=$this->Super_admin_model->registercustomer();
		$data['totalpamount']=$this->Super_admin_model->totalpamountamount();
		$data['allsaleprice']=$this->Super_admin_model->saleamount(2);
		$data['currentstock']=$this->Super_admin_model->fullstock();
		$data['soldstock']=$this->Super_admin_model->stockliststatus(2);
		$data['processstock']=$this->Super_admin_model->stockliststatus(1);
		$data['orderstock']=$this->Super_admin_model->stocklistorder(3);
		$allsellproductlist=$this->Super_admin_model->sellproductlist(2);
		$allpurchaseprice=0;
		$allsellprice=0;
		foreach($allsellproductlist as $plist){
						$getpricelist =  $this->Super_admin_model->getpurchaseprice($plist->ProductID,$plist->psize,$plist->pcolor);
						if(!empty($getpricelist)){
							$totalpurchase=$getpricelist->purchase_rate*$plist->Quantity;
							$totalsell=$getpricelist->sell_rate*$plist->Quantity;
							//$getrate=$this->Super_admin_model->purchaseamountinsaleproduct($getpricelist);
							$allpurchaseprice=$allpurchaseprice+$totalpurchase;
						    $allsellprice=$allsellprice+$totalsell;
							}
			}
		$data['getallpruchaseinsale']=$allpurchaseprice;
        $data['content']=$this->load->view('admin/dashboard/dashboard',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addSlidertype()
    {
        $data=array();
        $data['title']='Add Slider Type';
        $data['slidertype_info']=  $this->Super_admin_model->selectAllSlidertypeInfo();
        $data['content']=$this->load->view('admin/slider/add_slidertype_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveSlidertype()
    {
        $this->Super_admin_model->saveSlidertypeInfo();
        $sdata=array();
        $sdata['message']='Slide Type Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-SliderType');
    }
	public function editSlidertypeAllInfo($id)
    {
        $slider_id=  $id;
        $data=array();
        $data['slidertype_info_by_id']=$this->Super_admin_model->selectAllSlidertypeInfoById($slider_id);
        $data['title']='Edit Slider';
		$data['content']=$this->load->view('admin/slider/edit_slidertype_form',$data,TRUE);
         $this->load->view('admin/master/master',$data);
    }
    public function updateSlidertypeInfoById()
    {
        $this->Super_admin_model->updateSliderAlltypeInfoById();
        $sdata=array();
        $sdata['message']='Slide Type Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-SliderType');
    }
	public function viewSlider()
    {
        $data=array();
        $data['title']='View Slider';
        $data['slider_info']=  $this->Super_admin_model->selectAllSliderInfo();
        $data['content']=$this->load->view('admin/slider/view_slider',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addSliderImage()
    {
        $data=array();
        $data['title']='Add Slider';
		$data['all_type_info']=  $this->Super_admin_model->read_all('*', 'tbl_slider_type','stype_id','delation_status');
        $data['slider_info']=  $this->Super_admin_model->selectAllSliderInfo();
        $data['content']=$this->load->view('admin/slider/add_slider_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveSliderImage()
    {
        $this->Super_admin_model->saveSliderImageInfo();
        $sdata=array();
        $sdata['message']='Slide Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-Slider-Form');
    }
    public function updateActivationStatusById($slider_id)
    {
        $this->Super_admin_model->updateActivationStatusInfoById($slider_id);
        redirect('view-Slider');
    }
    public function updateDeactivationStatusById($slider_id)
    {
        $this->Super_admin_model->updateDeactivationStatusInfoById($slider_id);
        redirect('view-Slider');
    }
    public function deleteSliderInfoById($slider_id)
    {
        $this->Super_admin_model->deleteSliderInfoById($slider_id);
        redirect('view-Slider');
    }
    public function editSliderAllInfo($id)
    {
        $slider_id=  $id;
        $data=array();
		$data['all_type_info']=  $this->Super_admin_model->read_all('*', 'tbl_slider_type','stype_id','delation_status');
        $data['all_slider_info_by_id']=$this->Super_admin_model->selectAllSliderInfoById($slider_id);
        $data['title']='Edit Slider';
		$data['content']=$this->load->view('admin/slider/edit_slider_form',$data,TRUE);
         $this->load->view('admin/master/master',$data);
    }
    public function updateSliderInfoById()
    {
        $this->Super_admin_model->updateSliderAllInfoById();
        $sdata=array();
        $sdata['message']='Slide Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('view-Slider');
    }
	
	public function ViewGallery()
    {
        $data=array();
        $data['title']='View Gallery Image';
        $data['gallery_info']=  $this->Super_admin_model->selectAllGalleryInfo();
        $data['content']=$this->load->view('admin/gallery/view_gallery',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addPhotoGallery()
    {
        $data=array();
        $data['title']='Add Gallery Image';
        $data['gallery_info']=  $this->Super_admin_model->selectAllGalleryInfo();
        $data['content']=$this->load->view('admin/gallery/add_gallery_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function savePhotoGalleryImage()
    {
        $this->Super_admin_model->saveGalleryImageInfo();
        $sdata=array();
        $sdata['message']='Photo Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-Photogallery-Form');
    }
  
    public function deletePhotoGalleryInfoById($slider_id)
    {
        $this->Super_admin_model->deleteGalleryInfoById($slider_id);
        redirect('view-gallery');
    }
    public function editPhotoGalleryAllInfo($id)
    {
        $slider_id=  $id;
        $data=array();
        $data['all_slider_info_by_id']=$this->Super_admin_model->selectAllGalleryInfoById($slider_id);
        $data['title']='Edit Gattery';
		$data['content']=$this->load->view('admin/gallery/edit_gallery_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updatePhotoGalleryInfoById()
    {
        $this->Super_admin_model->updateGalleryAllInfoById();
        $sdata=array();
        $sdata['message']='Photo Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('view-gallery');
    }

    public function logout()
    {
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('user_login');
		$this->session->sess_destroy();
        $sdata=array();
        $sdata['message']='You Are Successfully Logout !!!';
        $this->session->set_userdata($sdata);
        redirect('Admin');
    }
	//page create
	public function viewpages()
    {
        $data=array();
        $data['title']='View All Page';
        $data['page_info']=  $this->Super_admin_model->pages();
        $data['content']=$this->load->view('admin/setting/view_pages',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addpage()
    {
        $data=array();
        $data['title']='Add Slider Type';
        $data['pageinfo_info']=   $this->Super_admin_model->read_all('*', 'tbl_pages','pageid','');
        $data['content']=$this->load->view('admin/setting/add_page_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function savepage()
    {
        $sdata=array();
		$pagename= $this->input->post('name');
		$ins['pagename']           = $this->input->post('name');
		$ins['status']             = 1;
		$result=$this->Super_admin_model->read('*', 'tbl_pages', array('pagename' => $pagename));
		if(!empty($result)){
        $sdata['message']='This Page is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_pages', $ins);
        $sdata['message']='Page Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Page');
    }
	public function editpageInfo($id)
    {
        $data=array();
        $data['page_info_by_id']=$this->Super_admin_model->read('*', 'tbl_pages', array('pageid' => $id));
        $data['title']='Edit Page';
		$data['content']=$this->load->view('admin/setting/edit_page_form',$data,TRUE);
         $this->load->view('admin/master/master',$data);
    }
    public function updatepageInfoById()
    {
        $sdata=array();
		$pageid=  $this->input->post('pageid');
        $pagename= $this->input->post('name');
		$ins['pagename']           = $this->input->post('name');
		$result=$this->Super_admin_model->read('*', 'tbl_pages', array('pagename' => $pagename));
		$total=count($result);
		if($total>1){
        $sdata['message']='This Page is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->update_date('tbl_pages', $ins, 'pageid', $pageid);
        $sdata['message']='Page Update Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Page');
    }
	
	public function addpagecontent()
    {
        $data=array();
        $data['title']='Add Content';
		$data['allpages']= $this->Super_admin_model->read_all('*', 'tbl_pages','pageid','');
        $data['content']=$this->load->view('admin/setting/add_content',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function savepagecontent()
    {
        $sdata=array();
		$pageid= $this->input->post('pagesname');
		$ins['pagesid']           = $this->input->post('pagesname');
		$ins['ptitle']           	= $this->input->post('title');
		$ins['pcontent']            = $this->input->post('descrip');
		$ins['status']              = 1;
		$result=$this->Super_admin_model->checkpages($pageid);
		if($result>0){
        $sdata['Error']='This Page Content is exits please  choose another Or edit This page!!!';
		$this->session->set_userdata($sdata);
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_pagecontent', $ins);
        $sdata['message']='Page Content Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Page-Content');
    }
	public function editpagecontentInfo($id)
    {
        $data=array();
		$data['allpages']= $this->Super_admin_model->read_all('*', 'tbl_pages','pageid','');
        $data['page_info_by_id']=$this->Super_admin_model->read('*', 'tbl_pagecontent', array('pagecontentid' => $id));
        $data['title']='Edit Content';
		$data['content']=$this->load->view('admin/setting/edit_content_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updatepagecontentInfoById()
    {
        $sdata=array();
		$pagecontentid=  $this->input->post('page_id');
        $pageid= $this->input->post('pagesname');
		$ins['pagesid']           = $this->input->post('pagesname');
		$ins['ptitle']           	= $this->input->post('title');
		$ins['pcontent']            = $this->input->post('descrip');
		$result=$this->Super_admin_model->checkpages($pageid);
		if($result>1){
        $sdata['Error']='This Page Content is exits please  choose another Or edit This page!!!';
		$this->session->set_userdata($sdata);
		}
		else{
		$insert_ID = $this->Super_admin_model->update_date('tbl_pagecontent', $ins, 'pagecontentid', $pagecontentid);
        $sdata['message']='Page Content Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Content-Manage');
    }
	public function deletepagecontentInfoById($pcontentid)
    {
	   $this->Super_admin_model->deleteitem('tbl_pagecontent', 'pagecontentid', $pcontentid);
       redirect('Content-Manage');
    }
	//Faq
	public function faqpage()
    {
        $data=array();
        $data['title']='View Faq Page';
        $data['page_info']=  $this->Super_admin_model->faqpages();
        $data['content']=$this->load->view('admin/setting/view_faq',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addfaq()
    {
        $data=array();
        $data['title']='Add Content';
		$data['allpages']= $this->Super_admin_model->read_all('*', 'tbl_pages','pageid','');
        $data['content']=$this->load->view('admin/setting/add_faq',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function savefaq()
    {
        $sdata=array();
		$pageid= 2;
		$ins['pagesid']           	= 2;
		$ins['ptitle']           	= $this->input->post('title');
		$ins['pcontent']            = $this->input->post('descrip');
		$ins['status']              = 1;
		$result=$this->Super_admin_model->checkpages($pageid);
		if($result>0){
        $sdata['Error']='This Page Content is exits please  choose another Or edit This page!!!';
		$this->session->set_userdata($sdata);
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_pagecontent', $ins);
        $sdata['message']='Page Content Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Faq');
    }
	public function editfaqInfo($id)
    {
        $data=array();
		$data['allpages']= $this->Super_admin_model->read_all('*', 'tbl_pages','pageid','');
        $data['page_info_by_id']=$this->Super_admin_model->read('*', 'tbl_pagecontent', array('pagecontentid' => $id));
        $data['title']='Edit Content';
		$data['content']=$this->load->view('admin/setting/edit_faq',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updatefaqInfoById()
    {
        $sdata=array();
		$pagecontentid=  $this->input->post('page_id');
        $pageid= 2;
		$ins['pagesid']           = 2;
		$ins['ptitle']           	= $this->input->post('title');
		$ins['pcontent']            = $this->input->post('descrip');
		$result=$this->Super_admin_model->checkpages($pageid);
		if($result>1){
        $sdata['Error']='This Page Content is exits please  choose another Or edit This page!!!';
		$this->session->set_userdata($sdata);
		}
		else{
		$insert_ID = $this->Super_admin_model->update_date('tbl_pagecontent', $ins, 'pagecontentid', $pagecontentid);
        $sdata['message']='Page Content Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Faqiew');
    }
	public function deletefaqInfoById($pcontentid)
    {
	   $this->Super_admin_model->deleteitem('tbl_pagecontent', 'pagecontentid', $pcontentid);
       redirect('Faqiew');
    }
	public function editcontact()
    {
        $data=array();
		$id=1;
        $data['setting_info']=$this->Super_admin_model->read('*', 'tbl_setting', array('setting_id' => $id));
        $data['title']='Edit Information';
		$data['content']=$this->load->view('admin/setting/setting',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	 public function updacontactinfo()
    {
        $sdata=array();
		$id=1;
		$ins['address1']           	= $this->input->post('address1');
		$ins['email1']            	= $this->input->post('email1');
		$ins['phone1']            	= $this->input->post('Phone1');
		$insert_ID = $this->Super_admin_model->update_date('tbl_setting', $ins, 'setting_id', $id);
        $sdata['message']='Contact Info Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Edit-contact');
    }
	
	//Partner Logo
	public function partnerpage()
    {
        $data=array();
        $data['title']='View Partner Page';
        $data['partner_info']=  $this->Super_admin_model->read_all('*', 'tbl_partner_logo','partnerid','');
        $data['content']=$this->load->view('admin/setting/view_partner',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addpartner()
    {
        $data=array();
        $data['title']='Add Partner Logo';
        $data['content']=$this->load->view('admin/setting/add_partner',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function savepartner()
    {
        $sdata=array();
		$ins['title']           	= $this->input->post('title');
		$ins['description']            = $this->input->post('descrip');
		$ins['status']              = 1;
		$config['upload_path']          = 'uploads/partnerlogo/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('partnerlogo'))
		{
				$error = array('error' => $this->upload->display_errors());
				$ins['logo']='';
		}
		else
		{
				$fdata =$this->upload->data();
       		    //$this->resizeImage($fdata['file_name']);
				$config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => false,
				    'width' => 250,
				    'height' => 250
				);
				$this->load->library('image_lib', $config1);
				
				$this->image_lib->resize();
		}
		$ins['logo']=$fdata['file_name'];

		$insert_ID = $this->Super_admin_model->insert_data('tbl_partner_logo', $ins);
        $sdata['message']='Partner Logo Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-partner');
    }
	public function editpartnerInfo($id)
    {
        $data=array();
        $data['page_info_by_id']=$this->Super_admin_model->read('*', 'tbl_partner_logo', array('partnerid' => $id));
        $data['title']='Edit Partner Logo';
		$data['content']=$this->load->view('admin/setting/edit_partner',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updatepartnerInfoById()
    {
        $sdata=array();
		$partnerid=  $this->input->post('partner_id');
		$ins['title']           	   = $this->input->post('title');
		$ins['description']            = $this->input->post('descrip');
		$ins['status']                  = 1;
		$config['upload_path']          = 'uploads/partnerlogo/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		
		$result2=$this->Super_admin_model->read('*', 'tbl_partner_logo', array('partnerid' => $partnerid));
		$image=$this->upload->do_upload('partnerlogo');
		
		if($image != ""){
			if($result2->logo){
				unlink("uploads/partnerlogo/".$result2->logo);
			}
		
		$fdata =$this->upload->data();
		   $config1=array(
			'source_image' => $fdata['full_path'],
			'new_image' => $fdata['file_path'],
			'maintain_ratio' => TRUE,
			'create_thumb' => false,
			'width' => 240,
			'height' => 170
		);
		$this->load->library('image_lib', $config1);
		$this->image_lib->resize();
		$ins['logo']=$fdata['file_name'];
		}
		else 
		{
		   $ins['logo']=$result2->logo;
		}
		print_r($ins);
		$insert_ID = $this->Super_admin_model->update_date('tbl_partner_logo', $ins, 'partnerid', $partnerid);
        $sdata['message']='Partner Logo Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-partner');
    }
	public function deletepartnerInfoById($pcontentid)
    {
	   $this->Super_admin_model->deleteitem('tbl_partner_logo', 'partnerid', $pcontentid);
       redirect('View-partner');
    }
	
	//Department section
	public function Viewdepartment()
    {
        $data=array();
        $data['title']='View Department';
        $data['all_department_info']=  $this->Super_admin_model->read_all('*', 'tbl_department','departmentid','');
        $data['content']=$this->load->view('admin/Employeeinfo/viewdepartment',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	
    public function Adddepartment()
    {
		$data=array();
        $data['title']='Add Department';
        $data['all_department_info']=  $this->Super_admin_model->read_all('*', 'tbl_department','departmentid','');
        $data['content']=$this->load->view('admin/Employeeinfo/adddepartment',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function savedepartment()
    {
		$sdata=array();
		$departmentname= $this->input->post('department_name');
		$ins['Departmentname']           = $this->input->post('department_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_department', array('Departmentname' => $departmentname));
		if(!empty($result)){
        $sdata['message']='This Department is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_department', $ins);
        $sdata['message']='Department Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Department');
		
    }
    public function deletedepartmentInfoById($department)
    {
	   $this->Super_admin_model->deleteitem('tbl_department', 'departmentid', $department);
       redirect('View-Department');
    }
    
    public function editdepartmentAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Department';
        $department=  $id;
        $data['department_info']= $this->Super_admin_model->read('*', 'tbl_department', array('departmentid' => $department));
		$data['content']=$this->load->view('admin/Employeeinfo/editdepartment',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updatedepartmentInfoById()
    {
		$sdata=array();
		$department=  $this->input->post('department_id',TRUE);
		$ins['Departmentname']           = $this->input->post('department_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_department', array('departmentid' => $department));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Department is exits please  choose another!!!';
		}
		else{
        $insert_ID = $this->Super_admin_model->update_date('tbl_department', $ins, 'departmentid', $department);
        $sdata['exc']='Department Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Department');
		}
    }
	//Employee
	public function Viewemployee()
    {
		$data=array();
        $data['title']='Employee List';
        $data['all_employee_info']=  $this->Super_admin_model->read_all('*', 'tbl_employee','empid','');
        $data['content']=$this->load->view('admin/Employeeinfo/viewemployee',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function Addemployee()
    {
		$data=array();
        $data['title']='Add Employee';
        $data['all_employee_info']=  $this->Super_admin_model->read_all('*', 'tbl_employee','empid','');
		$data['all_department_info']=  $this->Super_admin_model->read_all('*', 'tbl_department','departmentid','');
        $data['content']=$this->load->view('admin/Employeeinfo/addemployee',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveemployee()
    {
		$sdata=array();
		$passwprd=md5($this->input->post('password'));
		$ins['empName']           					= $this->input->post('employee_name');
		$ins['empemail']           					= $this->input->post('email');
		$ins['empdepartment']         				= $this->input->post('Department');
		$ins['empdesignation']           			= $this->input->post('Designation');
		$ins['joindate']           					= $this->input->post('joindate');
		$ins['empphone']           					= $this->input->post('Phone');
		$ins['emppassword']           				= $passwprd;
		$ins['emptotalleaveallowed']           		= $this->input->post('leave');
		$ins['weekend']           					= $this->input->post('weekend');
		$ins['workingtime']           				= $this->input->post('workinghours');
		$ins['DateInserted']= date('Y-m-d H:i:s');
		$ins['empstatus']		   =  $this->input->post('activation_status',TRUE);
		$config['upload_path']          = 'uploads/employee_images/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('employee_images'))
		{
				$error = array('error' => $this->upload->display_errors());
				$ins['empimage']='';
		}
		else
		{
				$fdata =$this->upload->data();
       		    //$this->resizeImage($fdata['file_name']);
				$config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => false,
				    'width' => 240,
				    'height' => 170
				);
				$this->load->library('image_lib', $config1);
				
				$this->image_lib->resize();
		}
		$ins['empimage']=$fdata['file_name'];
		$insert_ID = $this->Super_admin_model->insert_data('tbl_employee', $ins);
        $sdata['message']='Employee Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-Employee');
    }
   
	public function updateemployeeActivationStatus($empid)
    {
       
	    $ins['menustatus']    		= "0";
	   $this->Super_admin_model->update_date('tbl_employee', $ins, 'empid', $empid);
       redirect('View-Employee');
    }
    public function updateemployeeDeactivationStatus($empid)
    {
       
	    $ins['menustatus']    		= "1";
	   $this->Super_admin_model->update_date('tbl_employee', $ins, 'empid', $empid);
       redirect('View-Employee');
    }
    public function deleteemployeeInfoById($empid)
    {
		$ins['delation_status']    		= "1";
		$result2=$this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $empid));
		unlink("uploads/employee_images/".$result2->empimage);
		$this->Super_admin_model->deleteitem('tbl_employee', 'empid', $empid);
       	redirect('View-Employee');
    }
   
    public function editemployeeAllInfo($empid)
    {
        
		$data=array();
		$data['title']='Edit Employee';
		$data['employee_info']= $this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $empid));
		$data['all_department_info']=  $this->Super_admin_model->read_all('*', 'tbl_department','departmentid','');
		$data['content']=$this->load->view('admin/Employeeinfo/editemployee',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateemployeeInfoById()
    {
		$sdata=array();
		$empid=  $this->input->post('emp_id',TRUE);
		$passwprd=md5($this->input->post('password'));
		$ins['empName']           					= $this->input->post('employee_name');
		$ins['empemail']           					= $this->input->post('email');
		$ins['empdepartment']         				= $this->input->post('Department');
		$ins['empdesignation']           			= $this->input->post('Designation');
		$ins['joindate']           					= $this->input->post('joindate');
		$ins['empphone']           					= $this->input->post('Phone');
		$ins['weekend']           					= $this->input->post('weekend');
		$ins['workingtime']           				= $this->input->post('workinghours');
		$ins['emptotalleaveallowed']           		= $this->input->post('leave');
		$ins['empstatus']		   =  $this->input->post('activation_status',TRUE);
		
				$config['upload_path']          = 'uploads/employee_images/';
				$config['allowed_types']        = '*';
				$config['max_size']             = 100000;
				$this->load->library('upload', $config);
				
				$result2=$this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $empid));
                $image=$this->upload->do_upload('employee_images');
				
                if($image != ""){
                    if($result2->empimage){
                        unlink("uploads/employee_images/".$result2->empimage);
            		}
                
         		$fdata =$this->upload->data();
                   $config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => false,
				    'width' => 240,
				    'height' => 170
				);
				$this->load->library('image_lib', $config1);
               	$this->image_lib->resize();
        		$ins['empimage']=$fdata['file_name'];
                }
                else 
                {
                   $ins['empimage']=$result2->empimage;
                }
		if($passwprd==''){
			$passwprd=$result2->emppassword;
			}
		else{
			$passwprd=$passwprd;
			}
		$ins['emppassword']           				= $passwprd;
        $insert_ID = $this->Super_admin_model->update_date('tbl_employee', $ins, 'empid', $empid);
        $sdata['exc']='Employee Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Employee');
    }
	//leave Manage
	public function Viewleave()
    {
		$data=array();
        $data['title']='Application List';
		if($this->session->userdata('userid')==false){
			$empid=$this->session->userdata('employeid');
        	$data['all_leaves']=  $this->Super_admin_model->read_allinfo('*', 'tbl_leavemanage','leaveid','emplid',$empid);
			}
		else if($this->session->userdata('employeid')==false){
        	$data['all_leaves']=  $this->Super_admin_model->read_all('*', 'tbl_leavemanage','leaveid','');
			}
        $data['content']=$this->load->view('admin/Employeeinfo/viewleavestatus',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addleave()
    {
		if($this->session->userdata('employeid')==true){
		$empid=$this->session->userdata('employeid');
		$data=array();
        $data['title']='Add Application';
		$data['userinfo']=$this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $empid));
		$data['totalleavetaken']=$this->Super_admin_model->leave_count('tbl_leavemanage','emplid',$empid,'IsApprove',1);
		$data['all_employee_info']=  $this->Super_admin_model->read_all('*', 'tbl_employee','empid','');
        $data['content']=$this->load->view('admin/Employeeinfo/addleaveapp',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		}
		else{
			redirect('Super-admin');
			}
		
    }
	public function reliverleave()
    {
		$data=array();
        $data['title']='Reliever Application List';
		//$ins['relievar_status'] = "4";
		//$empid=$this->session->userdata('employeid');
        //$insert_ID = $this->Super_admin_model->update_date('tbl_leavemanage', $ins, 'reliverid', $empid);
		$data['all_leaves']=  $this->Super_admin_model->read_relievernotice();
        $data['content']=$this->load->view('admin/Employeeinfo/viewrelievetatus',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveeleave()
    {
		$sdata=array();
		$mytotalleave= $this->input->post('leavetotal');
		$leavegiven= $this->input->post('leavegiven');
		$fromdate= $this->input->post('fromdate');
		$todate= $this->input->post('todate');
		$days = (strtotime($todate) - strtotime($fromdate)) / (60 * 60 * 24);
        $Total =$days+1;
		$alreadytake=$leavegiven+$Total;
		if($mytotalleave>$alreadytake){		
		$passwprd=md5($this->input->post('password'));
		$ins['emplid']           					= $this->input->post('UserID');
		$ins['startdate1']         					= $this->input->post('fromdate');
		$ins['Enddate2']           					= $this->input->post('todate');
		$ins['reliverid']           				= $this->input->post('relievar');
		$ins['applicationtype']           			= $this->input->post('aptype');
		$ins['leavetype']           				= $this->input->post('leavetype');
		$ins['reason']           					= $this->input->post('descrip');
		$ins['numberofdays']           				= $Total;
		$ins['DateInserted']						= date('Y-m-d H:i:s');
		$ins['IsApprove']		   					=  0;
		$insert_ID = $this->Super_admin_model->insert_data('tbl_leavemanage', $ins);
        $sdata['message']='Application Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-Leavea-Application');
		}
		else{
			$sdata['error']='Your Leave Exced/Finish to your Total Leave!!!';
			$this->session->set_userdata($sdata);
			redirect('Add-Leavea-Application');
			}
    }
	public function aplicationreq(){
				if($this->session->userdata('userid')==true){
				$leaveid= $this->input->post('leaveid');
				$dataup['IsApprove']= $this->input->post('status');
				$insert_ID = $this->Super_admin_model->update_date('tbl_leavemanage', $dataup, 'leaveid', $leaveid);
				echo "done";
				}
				else{
					redirect('Super-admin');
					}
		}
	public function relieverapprove(){
				if($this->session->userdata('employeid')==true){
				$leaveid= $this->input->post('leaveid');
				$dataup['relievar_status']= $this->input->post('status');
				$insert_ID = $this->Super_admin_model->update_date('tbl_leavemanage', $dataup, 'leaveid', $leaveid);
				echo "done";
				}
				else{
					redirect('Super-admin');
					}
		}
//Attendness Section
	public function Viewattendness(){
		if($this->session->userdata('employeid')==true){
		$empid=$this->session->userdata('employeid');
		$data=array();
        $data['title']='View Attendness';
		$data['userinfo']=$this->Super_admin_model->read('*', 'tbl_attendness', array('emid' => $empid));
        $data['content']=$this->load->view('admin/Employeeinfo/viewattend',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		}
		else if($this->session->userdata('userid')==true){
			$todadate=date('Y-m-d');
			$data=array();
        	$data['title']='View Attendness';
			$data['todayattend_info']= $this->Super_admin_model->alldatalist('*', 'tbl_attendness','attendid','DATE(DateInserted)',$todadate,'emid');
			$data['all_attend_info']=  $this->Super_admin_model->read_all('*', 'tbl_employee','empid','');
			$data['content']=$this->load->view('admin/Employeeinfo/viewattend',$data,TRUE);
        	$this->load->view('admin/master/master',$data);
			}
		}
	public function checkattendness(){
		$data['emid']= $this->input->post('empid');
		$data['fromdate']= $this->input->post('fromdate');
		$data['todate']= $this->input->post('todate');
		$data['todayattend_info']=$this->Super_admin_model->attendlist($data);
		$this->load->view('admin/Employeeinfo/checkattend',$data);
		}
	//Branch section
	public function ViewBranch()
    {
       
	    $data=array();
        $data['title']='View Branch';
        $data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
        $data['content']=$this->load->view('admin/productinfo/viewbranch',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	
    public function AddBranch()
    {
		$data=array();
        $data['title']='Add Branch';
        $data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
        $data['content']=$this->load->view('admin/productinfo/addbranch',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveBranch()
    {
		$sdata=array();
		$branchname= $this->input->post('branch_name');
		$ins['Branchname']           = $this->input->post('branch_name');
		$ins['Address']           	 = $this->input->post('address');
		$ins['email']          		 = $this->input->post('email');
		$ins['phone']           	 = $this->input->post('Phone');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_branch', array('Branchname' => $branchname));
		if(!empty($result)){
        $sdata['message']='This Branch is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_branch', $ins);
        $sdata['message']='Branch Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Branch');
		
    }
    public function updateBranchActivationStatus($branch_id)
    {
       
	   $ins['status']    		= "0";
	   $this->Super_admin_model->update_date('tbl_branch', $ins, 'branchid', $branch_id);
       redirect('View-Branch');
    }
    public function updateBranchDeactivationStatus($branch_id)
    {
       
	    $ins['status']    		= "1";
	   $this->Super_admin_model->update_date('tbl_branch', $ins, 'branchid', $branch_id);
       redirect('View-Branch');
    }
    public function deleteBranchInfoById($branch_id)
    {
	   $this->Super_admin_model->deleteitem('tbl_branch', 'branchid', $branch_id);
       redirect('View-Branch');
    }
    
    public function editBranchAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Branch';
        $branchid=  $id;
        $data['branch_info']= $this->Super_admin_model->read('*', 'tbl_branch', array('branchid' => $branchid));
		$data['content']=$this->load->view('admin/productinfo/editbranch',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateBranchInfoById()
    {
		$sdata=array();
		$branchid_id=  $this->input->post('branchid_id',TRUE);
		$ins['Branchname']           = $this->input->post('branch_name');
		$ins['Address']           	 = $this->input->post('address');
		$ins['email']          		 = $this->input->post('email');
		$ins['phone']           	 = $this->input->post('Phone');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_branch', array('branchid' => $branchid_id));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Branch is exits please  choose another!!!';
		}
		else{
        $insert_ID = $this->Super_admin_model->update_date('tbl_branch', $ins, 'branchid', $branchid_id);
        $sdata['exc']='Branch Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Branch');
		}
    }
	//Color section
	public function ViewColor()
    {
        $data=array();
        $data['title']='View Color';
        $data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['content']=$this->load->view('admin/productinfo/viewcolor',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	
    public function AddColor()
    {
		$data=array();
        $data['title']='Add color';
        $data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['content']=$this->load->view('admin/productinfo/addcolor',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveColor()
    {
		$sdata=array();
		$colorname=$this->input->post('color_name');
		$ins['colorname']           = $this->input->post('color_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_color', array('colorname' => $colorname));
		if(!empty($result)){
        $sdata['message']='This Color is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_color', $ins);
        $sdata['message']='Color Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Color');
		
    }
    public function updateColorActivationStatus($colorid)
    {
       
	   $ins['status']    		= "0";
	   $this->Super_admin_model->update_date('tbl_color', $ins, 'colorid', $colorid);
       redirect('View-Color');
    }
    public function updateColorDeactivationStatus($colorid)
    {
       
	    $ins['status']    		= "1";
	   $this->Super_admin_model->update_date('tbl_color', $ins, 'colorid', $colorid);
       redirect('View-Color');
    }
    public function deleteColorInfoById($colorid)
    {
	   $this->Super_admin_model->deleteitem('tbl_color', 'colorid', $colorid);
       redirect('View-Color');
    }
    
    public function editColorAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Color';
        $colorid=  $id;
        $data['color_info']= $this->Super_admin_model->read('*', 'tbl_color', array('colorid' => $colorid));
		$data['content']=$this->load->view('admin/productinfo/editcolor',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateColorInfoById()
    {
		$sdata=array();
		$colorid=  $this->input->post('color_id',TRUE);
		$ins['colorname']           = $this->input->post('color_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_color', array('colorid' => $colorid));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Color is exits please  choose another!!!';
		}
		else{
        $insert_ID = $this->Super_admin_model->update_date('tbl_color', $ins, 'colorid', $colorid);
        $sdata['exc']='Color Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Color');
		}
    }
		//Size section
	public function ViewSize()
    {
        $data=array();
        $data['title']='View Size';
        $data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
        $data['content']=$this->load->view('admin/productinfo/viewsize',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	
    public function AddSize()
    {
		$data=array();
        $data['title']='Add Size';
        $data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
        $data['content']=$this->load->view('admin/productinfo/addsize',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveSize()
    {
		$sdata=array();
		$sizename=$this->input->post('size_name');
		$ins['itemsize']           = $this->input->post('size_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_size', array('itemsize' => $sizename));
		if(!empty($result)){
        $sdata['message']='This Size is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_size', $ins);
        $sdata['message']='Size Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Size');
		
    }
    public function updateSizeActivationStatus($sizeid)
    {
       
	   $ins['status']    		= "0";
	   $this->Super_admin_model->update_date('tbl_size', $ins, 'itemsizeid', $sizeid);
       redirect('View-Size');
    }
    public function updateSizeDeactivationStatus($sizeid)
    {
       
	    $ins['status']    		= "1";
	   $this->Super_admin_model->update_date('tbl_size', $ins, 'itemsizeid', $sizeid);
       redirect('View-Size');
    }
    public function deleteSizeInfoById($sizeid)
    {
	   $this->Super_admin_model->deleteitem('tbl_size', 'itemsizeid', $sizeid);
       redirect('View-Size');
    }
    
    public function editSizeAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Size';
        $sizeid=  $id;
        $data['size_info']= $this->Super_admin_model->read('*', 'tbl_size', array('itemsizeid' => $sizeid));
		$data['content']=$this->load->view('admin/productinfo/editsize',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateSizeInfoById()
    {
		$sdata=array();
		$sizeid=  $this->input->post('size_id',TRUE);
		$ins['itemsize']           = $this->input->post('size_name');
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_size', array('itemsizeid' => $sizeid));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Size is exits please  choose another!!!';
		}
		else{
        $insert_ID = $this->Super_admin_model->update_date('tbl_size', $ins, 'itemsizeid', $sizeid);
        $sdata['exc']='Size Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Size');
		}
    }
	
	
	//Category section
	public function ViewCategory()
    {
        $data=array();
        $data['title']='VIEW Category';
        $data['all_category_info']=  $this->Super_admin_model->read_allrecord('*', 'tbl_menucategory', 'menucatid','delation_status','0','parentid','0');
        $data['content']=$this->load->view('admin/category/view_category',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	
    public function AddCategory()
    {
		$data=array();
        $data['title']='Add Category';
	
        $data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
        $data['content']=$this->load->view('admin/category/add_category',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveCategory()
    {
		$sdata=array();
		$catname=$this->input->post('category_name');
		$ins['mecatname']           = $this->input->post('category_name');
		$nameslug = str_replace('&', ' ', $catname);
		$nameslug = str_replace('(', ' ', $nameslug);
		$nameslug = str_replace(')', ' ', $nameslug);
		$nameslug = str_replace("'", '', $nameslug);
		$nameslug = preg_replace('/\s+/', '-',$nameslug);
		$nameslug = rtrim($nameslug, '-');
		$ins['catslug']           = $nameslug;
		$parentid= $this->input->post('parentcategory');
		if(!empty($parentid)){
		    $parentid=$parentid;
		}
		else{
		     $parentid=0;
		}
		$ins['parentid']=  $parentid;
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$ins['catdate']        		= date('Y-m-d H:i:s');
		$result=$this->Super_admin_model->read('*', 'tbl_menucategory', array('catslug' => $nameslug));
		if(!empty($result)){
        $sdata['message']='This Category is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_menucategory', $ins);
        $sdata['message']='Category Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Category');
		
    }
    public function updateCategoryActivationStatus($company_id)
    {
       
	    $ins['status']    		= "0";
	   $this->Super_admin_model->update_date('tbl_menucategory', $ins, 'menucatid', $company_id);
       redirect('View-Category');
    }
    public function updateCategoryDeactivationStatus($company_id)
    {
       
	    $ins['status']    		= "1";
	   $this->Super_admin_model->update_date('tbl_menucategory', $ins, 'menucatid', $company_id);
       redirect('View-Category');
    }
    public function deleteCategoryInfoById($company_id)
    {
		
		$ins['delation_status']    		= "1";
		$this->Super_admin_model->update_date('tbl_menucategory', $ins, 'menucatid', $company_id);
       redirect('View-Category');
    }
    
    public function editCategoryAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Category';
        $company_id=  $id;
		$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
        $data['category_info']= $this->Super_admin_model->read('*', 'tbl_menucategory', array('menucatid' => $company_id));
		$data['content']=$this->load->view('admin/category/edit_category_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateCategoryInfoById()
    {
		$sdata=array();
		$category_id=  $this->input->post('category_id',TRUE);
		$catname=$this->input->post('category_name');
		$ins['mecatname']           = $this->input->post('category_name');
		$nameslug = str_replace('&', ' ', $catname);
		$nameslug = str_replace('(', ' ', $nameslug);
		$nameslug = str_replace(')', ' ', $nameslug);
		$nameslug = str_replace("'", '', $nameslug);
		$nameslug = preg_replace('/\s+/', '-',$nameslug);
		$nameslug = rtrim($nameslug, '-');
		$ins['catslug']           = $nameslug;
		$parentid= $this->input->post('parentcategory');
		if(!empty($parentid)){
		    $parentid=$parentid;
		}
		else{
		     $parentid=0;
		}
		$ins['parentid']=  $parentid;
		$ins['status']=  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_menucategory', array('menucatid' => $category_id));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Category is exits please  choose another!!!';
		}
		else{
        $insert_ID = $this->Super_admin_model->update_date('tbl_menucategory', $ins, 'menucatid', $category_id);
        $sdata['exc']='category Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Category');
		}
    }
	//Products
	public function Viewproduct()
    {
		$data=array();
        $data['title']='Product List';
	    //$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
        $data['all_item_info']=  $this->Super_admin_model->read_allitems();
        $data['content']=$this->load->view('admin/products/view_product',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function Addproduct()
    {
		$data=array();
        $data['title']='Add Products';
	   // $data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
        $data['all_item_info']=  $this->Super_admin_model->read_allitems();
        $data['content']=$this->load->view('admin/products/add_product',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveproduct()
    {
		$sdata=array();
		$item=$this->input->post('ProductName');
		$ins['productname']           				= $this->input->post('ProductName');
		$ins['productdesc']         				= $this->input->post('descrip');
		$ins['product_purchaseprice']           	= $this->input->post('productprice');
		$ins['product_sellprice']           		= $this->input->post('productsellprice');
		$vat= $this->input->post('pvat');
		$discount= $this->input->post('discount');
		$isnew=$this->input->post('new');
		$isfeature=$this->input->post('featured');
		if(!empty($isnew)){
			$isnew=1;
			}
		else{
			$isnew=0;
			}
		if(!empty($isfeature)){
			$isfeature=1;
			}
		else{
			$isfeature=0;
			}
		if(!empty($vat)){
			$vat=$vat;
			}
		else{
			$vat=0;
			}
		if(!empty($discount)){
			$discount=$discount;
			}
		else{
			$discount=0;
			}
		$ins['vat']           						= $vat;
		$ins['discount']           					= $discount;
		$ins['newproduct']           				= $isnew;
		$ins['featureproduct']           			= $isfeature;
		$ins['DateInserted']= date('Y-m-d H:i:s');
		$item = str_replace('&', ' ', $item);
		$item = str_replace('(', ' ', $item);
		$item = str_replace(')', ' ', $item);
		$item = str_replace("'", '', $item);
		$nameslug = preg_replace('/\s+/', '-',$item);
		$nameslug = rtrim($nameslug, '-');
		//$nameslug = str_replace(' ', '-', $item);
		$ins['pruductslug']           = $nameslug;
		$ins['status']		   =  $this->input->post('activation_status',TRUE);
		$config['upload_path']          = 'uploads/products_images/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('product_image'))
		{
				$error = array('error' => $this->upload->display_errors());
				$ins['product_image']='';
		}
		else
		{
				$fdata =$this->upload->data();
       		    //$this->resizeImage($fdata['file_name']);
				$config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => TRUE,
				    'thumb_marker' => '_thumb',
				    'width' => 240,
				    'height' => 280
				);
				$this->load->library('image_lib', $config1);
				
				$this->image_lib->resize();
		}
		$ins['productimage']=$fdata['file_name'];
		$result=$this->Super_admin_model->read('*', 'tbl_products', array('pruductslug' => $nameslug));
		if(!empty($result)){
        $sdata['message']='This products is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_products', $ins);
        $sdata['message']='Product Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-product');
    }
   
	public function updateproductActivationStatus($menu_id)
    {
       
	    $ins['status']    		= "0";
	   $this->Super_admin_model->update_date('tbl_products', $ins, 'productid', $menu_id);
       redirect('View-product');
    }
    public function updateproductDeactivationStatus($menu_id)
    {
       
	    $ins['status']    		= "1";
	   $this->Super_admin_model->update_date('tbl_products', $ins, 'productid', $menu_id);
       redirect('View-product');
    }
    public function deleteproductInfoById($menu_id)
    {
		$ins['delation_status']    		= "1";
		$this->Super_admin_model->update_date('tbl_products', $ins, 'productid', $menu_id);
		$result2=$this->Super_admin_model->read('*', 'tbl_products', array('productid' => $menu_id));
		$path_info = pathinfo($result2->productimage);
		$ext=$path_info['extension']; 
		$withoutext = pathinfo($result2->productimage, PATHINFO_FILENAME);
		$getimg=$withoutext."_thumb.".$ext;
		unlink("uploads/products_images/".$result2->productimage);
		unlink("uploads/products_images/".$getimg);
		$this->Super_admin_model->update_date('tbl_galleryimg', $ins, 'gallerid', $menu_id);
		$galleryimage=$this->Super_admin_model->readgimage($menu_id);
		foreach($galleryimage as $image){
			$path_info2 = pathinfo($image->imagethumb);
			$ext2=$path_info2['extension']; 
			$withoutext2 = pathinfo($image->imagethumb, PATHINFO_FILENAME);
			$getimg2=$withoutext."_thumb.".$ext;
			unlink("uploads/products_images/".$image->imagethumb);
			unlink("uploads/products_images/".$getimg);
			}
       redirect('View-product');
    }
   
    public function editproductAllInfo($id)
    {
        
		$data=array();
		$data['title']='Edit Products';
        $menu_id=  $id;
        $data['item_info']= $this->Super_admin_model->readitem($menu_id);
		$data['content']=$this->load->view('admin/products/edit_product_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateproductInfoById()
    {
		$sdata=array();
		$menu_id=  $this->input->post('menu_id',TRUE);
		$item=$this->input->post('ProductName');
		$ins['productname']           			= $this->input->post('ProductName');
		$ins['productdesc']           			= $this->input->post('descrip');
		$ins['product_purchaseprice']           = $this->input->post('productprice');
		$ins['product_sellprice']           	= $this->input->post('productsellprice');
		$ins['vat']           					= $this->input->post('pvat');
		$ins['discount']           				= $this->input->post('discount');
		$isnew=$this->input->post('new');
		$isfeature=$this->input->post('featured');
		if(!empty($isnew)){
			$isnew=1;
			}
		else{
			$isnew=0;
			}
		if(!empty($isfeature)){
			$isfeature=1;
			}
		else{
			$isfeature=0;
			}
		$ins['newproduct']           				= $isnew;
		$ins['featureproduct']           			= $isfeature;
		$ins['DateInserted']= date('Y-m-d H:i:s');
		$item = str_replace('&', ' ', $item);
		$item = str_replace('(', ' ', $item);
		$item = str_replace(')', ' ', $item);
		$item = str_replace("'", '', $item);
		$nameslug = preg_replace('/\s+/', '-',$item);
		$nameslug = rtrim($nameslug, '-');
		//$nameslug = str_replace(' ', '-', $item);
		$ins['pruductslug']           = $nameslug;
		$ins['status']		   =  $this->input->post('activation_status',TRUE);
		$result=$this->Super_admin_model->read('*', 'tbl_products', array('pruductslug' => $nameslug));
		$total=count($result);
		if($total>1){
        $sdata['exc']='This Product is exits please  choose another!!!';
		}
		else{
				$config['upload_path']          = 'uploads/products_images/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
				$result2=$this->Super_admin_model->read('*', 'tbl_products', array('productid' => $menu_id));
                $image=$this->upload->do_upload('product_image');
				$path_info = pathinfo($result2->productimage);
				$ext=$path_info['extension']; 
				$withoutext = pathinfo($result2->productimage, PATHINFO_FILENAME);
				$getimg=$withoutext."_thumb.".$ext;
				
                if($image != ""){
                    if($result2->productimage){
                        unlink("uploads/products_images/".$result2->productimage);
						unlink("uploads/products_images/".$getimg);
            }
                
                        $fdata =$this->upload->data();
                         $config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => TRUE,
				    'thumb_marker' => '_thumb',
				    'width' => 240,
				    'height' => 280
				);
				$this->load->library('image_lib', $config1);
				
                        $this->image_lib->resize();
        	$ins['productimage']=$fdata['file_name'];
                }
                else 
                {
                   $ins['productimage']=$result2->productimage;
                }
        $insert_ID = $this->Super_admin_model->update_date('tbl_products', $ins, 'productid', $menu_id);
        $sdata['exc']='Product Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-product');
		}
    }
	//product Images
	public function Viewimages()
    {
		$data=array();
        $data['title']='View Product Images';
	    $data['all_proimages_info']=  $this->Super_admin_model->read_productallimage();
        $data['content']=$this->load->view('admin/productsimages/view_images',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function Addimage($id)
    {
		$data=array();
		$data['pid']=$id;
        $data['title']='Add Product Image';
	    $data['all_instavid_info']=  $this->Super_admin_model->read_all('*', 'tbl_galleryimg','gallerimgid','delation_status');
        $data['content']=$this->load->view('admin/productsimages/add_image',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveimage()
    {
		$sdata=array();
		$pid=$this->input->post('menu_id');
		$ins['gallerid']           = $this->input->post('menu_id');
		$ins['imagethumb']         = $this->input->post('item_name');
		$config['upload_path']          = 'uploads/products_images/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('product_image'))
		{
				$error = array('error' => $this->upload->display_errors());
				$ins['imagethumb']='';
		}
		else
		{
				$fdata =$this->upload->data();
				$config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => TRUE,
				    'thumb_marker' => '_thumb',
				    'width' => 240,
				    'height' => 343
				);
				$this->load->library('image_lib', $config1);
				
				$this->image_lib->resize();
				$ins['imagethumb']=$fdata['file_name'];
		}
		
		$insert_ID = $this->Super_admin_model->insert_data('tbl_galleryimg', $ins);
        $sdata['message']='Product Images Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-image/'.$pid);
    }
	
    public function deleteimageInfoById($menu_id)
    {
		$ins['delation_status']    		= "1";
		$this->Super_admin_model->update_date('tbl_galleryimg', $ins, 'gallerimgid', $menu_id);
		$result2=$this->Super_admin_model->read('*', 'tbl_galleryimg', array('gallerimgid' => $menu_id));
		$path_info = pathinfo($result2->imagethumb);
				$ext=$path_info['extension']; 
				$withoutext = pathinfo($result2->imagethumb, PATHINFO_FILENAME);
				$getimg=$withoutext."_thumb.".$ext;
		 unlink("uploads/products_images/".$result2->imagethumb);
		 unlink("uploads/products_images/".$getimg);
       redirect('View-images');
    }
   
    public function editimageAllInfo($id)
    {
        
		$data=array();
		$data['title']='Edit Product Image';
        $menu_id=  $id;
		
		$data['item_info']=  $this->Super_admin_model->read('*', 'tbl_galleryimg', array('gallerimgid' => $menu_id));
		$data['content']=$this->load->view('admin/productsimages/edit_image_form',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateimageInfoById()
    {
		$sdata=array();
		$menu_id=  $this->input->post('menu_id',TRUE);
		$config['upload_path']          = 'uploads/products_images/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
				
				$result2=$this->Super_admin_model->read('*', 'tbl_galleryimg', array('gallerimgid' => $menu_id));
				$path_info = pathinfo($result2->imagethumb);
				$ext=$path_info['extension']; 
				$withoutext = pathinfo($result2->imagethumb, PATHINFO_FILENAME);
				$getimg=$withoutext."_thumb.".$ext;
		 		unlink("uploads/products_images/".$getimg);
                $image=$this->upload->do_upload('product_image');
                if($image != ""){
                    if($result2->imagethumb){
                        unlink("uploads/products_images/".$result2->imagethumb);
						unlink("uploads/products_images/".$getimg);
            }
                
                        $fdata =$this->upload->data();
                       $config1=array(
					'source_image' => $fdata['full_path'],
                    'new_image' => $fdata['file_path'],
					'maintain_ratio' => TRUE,
					'create_thumb' => TRUE,
				    'thumb_marker' => '_thumb',
				    'width' => 240,
				    'height' => 343
				);
				$this->load->library('image_lib', $config1);
				
                        $this->image_lib->resize();
        	$ins['imagethumb']=$fdata['file_name'];
                }
                else 
                {
                   $ins['imagethumb']=$result2->imagethumb;
                }
        $insert_ID = $this->Super_admin_model->update_date('tbl_galleryimg', $ins, 'gallerimgid', $menu_id);
        $sdata['exc']='Product Image Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-images');
    }
	//Stock section
	public function ViewStockmanage()
    {
        $data=array();
        $data['title']='View Stock';
		/*$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['all_stock_info']=  $this->Super_admin_model->allstock();*/
		
        $data['content']=$this->load->view('admin/stockmanage/viewstock',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	// Start datagrid example
	public function ViewStocktest()
    {
        $data=array();
        $data['title']='View Stock';
        $data['content']=$this->load->view('admin/stockmanage/viewstocktest',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function getEventDatatable(){
	$getdata = $this->Super_admin_model->allstock();
	$data = array();
	$i=0;
	foreach ($getdata as $value)
	{
		$i++;
		 $row = array();
		 $row[] = $i;
		 $row[] = $value->productname;
		 $row[] = $value->mecatname;
		 $row[] = $value->Branchname;
		 $row[] = $value->itemsize;
		 $row[] = $value->colorname;
		 $row[] = $value->pquantity;
		 $row[] = '<a href="'.base_url().'Update-Stockproduct/'.$value->stockid.'" title="Edit"><i class="material-icons md-24">&#xE8F4;</i></a><a href="'.base_url().'Delete-Stockproduct-Information/'.$value->stockid.'" onClick="return confirm(\'Are you sure you want to Delete this?\');" class="uk-margin-left" title="Delete"><i class="material-icons md-24">&#xE872;</i></a>';
		 $data[] = $row;
	}

	$output = array(
		"data" => $data,
	);

	echo json_encode($output);}
	
	
	
	
	// End datagrid example
	
	
    public function AddStockmanage()
    {
		$data=array();
        $data['title']='Add Stock';
		$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['content']=$this->load->view('admin/stockmanage/addstock',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveStockmanage()
    {
		if($this->session->userdata('userid')==false){
			 $loginids=$this->session->userdata('employeid');
			}
		else if($this->session->userdata('employeid')==false){
			$loginids=$this->session->userdata('userid');
			}
		$sdata=array();
		$ins['catids']           =  $this->input->post('category');
		$ins['proid']			 =  $this->input->post('product',TRUE);
		$ins['brid']			 =  $this->input->post('branch',TRUE);
		$ins['psizeid']			 =  $this->input->post('itemsize',TRUE);
		$ins['pcolorid'] 		 =  $this->input->post('pcolor',TRUE);
		$ins['purchase_rate']	 =  $this->input->post('productprice',TRUE);
		$ins['sell_rate']		 =  $this->input->post('productsellprice',TRUE);
		$ins['pquantity']		 =  $this->input->post('quantity',TRUE);
		$ins['staus']			 =  $this->input->post('activation_status',TRUE);
		$ins['empployee']		 =  $loginids;
		$ins['reemarks'] 		 =  $this->input->post('descrip',TRUE);
		$ins['DateInserted']	 =  date('Y-m-d H:i:s');
		//print_r($ins);
		$insert_ID = $this->Super_admin_model->insert_data('tbl_productstock', $ins);
        $sdata['message']='Stock Save Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-Stockproduct');
		
    }
    public function deleteStockmanageInfoById($stockid)
    {
	   $this->Super_admin_model->deleteitem('tbl_productstock', 'stockid', $stockid);
       redirect('View-Stockproduct');
    }
    
    public function editStockmanageAllInfo($id)
    {
		$data=array();
		$data['title']='Edit Size';
		$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_menucategory','menucatid','delation_status');
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['stock_info']= $this->Super_admin_model->read('*', 'tbl_productstock', array('stockid' => $id));
		$data['content']=$this->load->view('admin/stockmanage/editstock',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }

    public function updateStockmanageInfoById()
    {
		if($this->session->userdata('userid')==false){
			 $loginids=$this->session->userdata('employeid');
			}
		else if($this->session->userdata('employeid')==false){
			$loginids=$this->session->userdata('userid');
			}
		$sdata=array();
		$stockid=  $this->input->post('stock_id',TRUE);
		$ins['catids']           =  $this->input->post('category');
		$ins['proid']			 =  $this->input->post('product',TRUE);
		$ins['brid']			 =  $this->input->post('branch',TRUE);
		$ins['psizeid']			 =  $this->input->post('itemsize',TRUE);
		$ins['pcolorid'] 		 =  $this->input->post('pcolor',TRUE);
		$ins['purchase_rate']	 =  $this->input->post('productprice',TRUE);
		$ins['sell_rate']		 =  $this->input->post('productsellprice',TRUE);
		$ins['pquantity']		 =  $this->input->post('quantity',TRUE);
		$ins['staus']			 =  $this->input->post('activation_status',TRUE);
		$ins['empployee']		 =  $loginids;
		$ins['reemarks'] 		 =  $this->input->post('descrip',TRUE);
		$ins['DateInserted']	 =  date('Y-m-d H:i:s');
        $insert_ID = $this->Super_admin_model->update_date('tbl_productstock', $ins, 'stockid', $stockid);
        $sdata['exc']='Stock Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Stockproduct');
    }
	
	public function getproprice(){
		$proid           = $this->input->post('product');
		$result=$this->Super_admin_model->read('*', 'tbl_products', array('productid' => $proid));
		echo json_encode($result);
		}
	//Admin Setings 
	 public function Viewsetting()
    {
       
		$data=array();
		$data['title']='Update User Information';
		if($this->session->userdata('userid')==false){
			   $setting_id=  $admin_id=$this->session->userdata('employeid');
		$data['setting_info']=   $this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $setting_id));
		$data['content']=$this->load->view('admin/setting/edit_setting_form2',$data,TRUE);
			}
		else if($this->session->userdata('employeid')==false){
		$setting_id=  $admin_id=$this->session->userdata('userid');
		$data['setting_info']=   $this->Super_admin_model->read('*', 'tbl_user', array('uid' => $setting_id));
		$data['content']=$this->load->view('admin/setting/edit_setting_form',$data,TRUE);
			}
        $this->load->view('admin/master/master',$data);
    }

    public function updatesettingInfoById()
    {
        
		$sdata=array();
		$setting_id=  $this->input->post('settingid',TRUE);
		$ins['display_name']        = $this->input->post('disname');
		if($this->input->post('password')==""){
			
			}
		else{
		$ins['user_pass']        	= md5($this->input->post('password'));
		}
		
        $insert_ID = $this->Super_admin_model->update_date('tbl_user', $ins, 'uid', $setting_id);
        $sdata['exc']='Setting Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Setting');
    }
	public function updatesettingempInfoById()
    {
        
		$sdata=array();
		$setting_id=  $this->input->post('settingid',TRUE);
		$ins['empName']        = $this->input->post('Name');
		$ins['empemail']        = $this->input->post('email');
		$ins['empphone']        = $this->input->post('phone');
		if($this->input->post('password')==""){
			
			}
		else{
		$ins['emppassword']        	= md5($this->input->post('password'));
		}
		$config['upload_path']          = 'uploads/employee_images/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100000;
		$this->load->library('upload', $config);
		
		$result2=$this->Super_admin_model->read('*', 'tbl_employee', array('empid' => $setting_id));
		$image=$this->upload->do_upload('employee_images');
		
		if($image != ""){
			if($result2->empimage){
				unlink("uploads/employee_images/".$result2->empimage);
			}
		
		$fdata =$this->upload->data();
		   $config1=array(
			'source_image' => $fdata['full_path'],
			'new_image' => $fdata['file_path'],
			'maintain_ratio' => TRUE,
			'create_thumb' => false,
			'width' => 240,
			'height' => 170
		);
		$this->load->library('image_lib', $config1);
		$this->image_lib->resize();
		$ins['empimage']=$fdata['file_name'];
		}
		else 
		{
		   $ins['empimage']=$result2->empimage;
		}
				
        $insert_ID = $this->Super_admin_model->update_date('tbl_employee', $ins, 'empid', $setting_id);
		$sessiondata = array(
				'employename' =>$this->input->post('Name'),
				'employeemail' =>$this->input->post('email'),
			);
        $sdata['exc']='Setting Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-Setting');
    }
	//Order section
	public function manageorder(){
		$data=array();
        $data['title']='Manage Order';
        $data['content']=$this->load->view('admin/orderinfo/manageorder',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		}
	public function OrderDatatable(){
	$getdata = $this->Super_admin_model->read_allorder();
	$data = array();
	$i=0;
	foreach ($getdata as $value)
	{
		 $i++;
		 $status="";
		 if($value->status==0){
			$status="Pending";
			}
		else if($value->status==1){
			$status="Processing";
			}
		else if($value->status==2){
			$status="Complete";
			}
		else if($value->status==3){
			$status="Cancel";
			}
		else if($value->status==4){
			$status="Viewed";
			}
		 $row = array();
		 $row[] = $i;
		 $row[] = $value->orderid;
		 $row[] = $value->DateInserted;
		 $row[] = $value->grandtotal;
		 $row[] = $status;
		 $row[] = '<a href="'.base_url().'Check-Order/'.$value->orderid.'" title="View Order"><i class="material-icons md-24">&#xE8F4;</i></a>';
		 $data[] = $row;
	}

	$output = array(
		"data" => $data,
	);
	//print_r($output);
	echo json_encode($output);
	}
	public function ordercheck($id)
    {
        $data=array();
        $data['title']='Order Information';
		if($this->session->userdata('userid')==false){
			$setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
		$ins['status']  = "4";
		$ins['ShownBy']  = $setting_id;
		$data['orderview_info']=  $this->Super_admin_model->read_singleorder($id);
		$data['orderinfo'] =  $this->Super_admin_model->read('*', 'tbl_orders', array('orderid' => $id));
		if( $data['orderinfo']->status==0){
			 $this->Super_admin_model->update_date('tbl_orders', $ins, 'orderid', $id);
			}
		$data['customer_info']=$this->Super_admin_model->read('*', 'tbl_customer', array('custid' => $data['orderinfo']->customerid));
		$data['shipping_info']=$this->Super_admin_model->read('*', 'tbl_delivaryaddress', array('shipaddressid' => $data['orderinfo']->shippingid));
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
		$data['rider_info']=  $this->Super_admin_model->read_allrecord('*', 'tbl_employee','empid','empdepartment',5,'empstatus',1);

        $data['content']=$this->load->view('admin/orderinfo/checkorder',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function Vieworder()
    {
        $data=array();
        $data['title']='View Order list';
        $data['all_todayorder_info']=  $this->Super_admin_model->read_alltodayorder();
		$data['all_order_info']=  $this->Super_admin_model->read_allorder();
        $data['content']=$this->load->view('admin/orderinfo/vieworderlist',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function ordersingleview($id)
    {
        $data=array();
        $data['title']='View Order';
		$data['orderview_info']=  $this->Super_admin_model->read_singleorder($id);
		$data['orderinfo'] =  $this->Super_admin_model->read('*', 'tbl_orders', array('orderid' => $id));
		$data['customer_info']=$this->Super_admin_model->read('*', 'tbl_customer', array('custid' => $data['orderinfo']->customerid));
		$data['shipping_info']=$this->Super_admin_model->read('*', 'tbl_delivaryaddress', array('shipaddressid' => $data['orderinfo']->shippingid));
        $data['content']=$this->load->view('admin/orderinfo/orderdetails',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	//CRM Section
	
	public function Incommingorder()
    {
        $data=array();
        $data['title']='View Incomming Order';
        $data['orderinfo']=  $this->Super_admin_model->read_allincommingorder();
        $data['content']=$this->load->view('admin/orderinfo/incomming',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function incomingordercount(){
		echo $orderplace=  $this->Super_admin_model->record_order();
		}
	public function pendingorder()
    {
        $data=array();
        $data['title']='View Pending Order';
        $data['orderinfo']=  $this->Super_admin_model->read_allpendingorder();
        $data['content']=$this->load->view('admin/orderinfo/pending',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function mypendingorder()
    {
        $data=array();
        $data['title']='View Pending Order';
		if($this->session->userdata('userid')==false){
			   $setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
        $data['orderinfo']=  $this->Super_admin_model->read_allpendingorderbyid($setting_id);
        $data['content']=$this->load->view('admin/orderinfo/pendingmy',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function editdelivary($id)
    {
        $data=array();
        $data['title']='View Order Incomming Order';
		if($this->session->userdata('userid')==false){
			$setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
		$ins['status']  = "4";
		$ins['ShownBy']  = $setting_id;
		$data['orderview_info']=  $this->Super_admin_model->read_singleorder($id);
		$data['orderinfo'] =  $this->Super_admin_model->read('*', 'tbl_orders', array('orderid' => $id));
		if( $data['orderinfo']->status==0){
			 $this->Super_admin_model->update_date('tbl_orders', $ins, 'orderid', $id);
			}
		$data['customer_info']=$this->Super_admin_model->read('*', 'tbl_customer', array('custid' => $data['orderinfo']->customerid));
		$data['shipping_info']=$this->Super_admin_model->read('*', 'tbl_delivaryaddress', array('shipaddressid' => $data['orderinfo']->shippingid));
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
		$data['rider_info']=  $this->Super_admin_model->read_allrecord('*', 'tbl_employee','empid','empdepartment',5,'empstatus',1);

        $data['content']=$this->load->view('admin/orderinfo/orderedit',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function checkquantity(){
			$data['pid']=$this->input->post('pid');
			$data['branchid']=$this->input->post('branch');
			$data['psize']=$this->input->post('itemsize');
			$data['pcolor']=$this->input->post('pcolor');
			echo $getqty=$this->Super_admin_model->checkqty($data);
		}
	public function checkquantitycart(){
			$data['pid']=$this->input->post('pid');
			$data['psize']=$this->input->post('itemsize');
			$data['pcolor']=$this->input->post('pcolor');
			$getqty=$this->Super_admin_model->checkqty2($data);
			echo json_encode($getqty);
		}
	public function submitdeliver(){
		if($this->session->userdata('userid')==false){
			$setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
		$delvartime=$this->input->post('delvartime');
		$branch=$this->input->post('branch');
		$orderstatus=$this->input->post('orderstatus');
		$rider=$this->input->post('rider');
		$orderid=$this->input->post('orderid');
		$sentqty=$this->input->post('sentqty');
		$productinfo =  $this->Super_admin_model->read_singleorder( $orderid);
		$i=0;
		$updateord['status']=$orderstatus;
		$updateord['remarks']=$this->input->post('remarks');
		$updateord['delivarytime']=$delvartime;
		$this->Foodmartstore_model->update_info('tbl_orders', $updateord, 'orderid', $orderid);
		if($orderstatus==1){
		foreach($productinfo as $product){
					$receiveqty=$product->receivedqty;
					$receivetotal=$receiveqty+$sentqty[$i];
					$updatereceive['receivedqty']=$receiveqty+$sentqty[$i];
					$orderdetails=$product->orderdetailsid;
					$this->Foodmartstore_model->update_info('tbl_orderdetails', $updatereceive, 'orderdetailsid', $orderdetails);
					$odreqty=$product->Quantity;
					if($odreqty>$receivetotal){
						$mydata['ispartial']=1;
						$this->Foodmartstore_model->update_info('tbl_orders', $mydata, 'orderid', $orderid);
						}
					
					
					$insertdetails['chOrdID']       		   	       = $orderid;
					$insertdetails['chProductID']       		   	   = $product->ProductID;
					$insertdetails['chbranch']       		   	       = $branch;
					$insertdetails['chPrice']       		           = $product->Price;
					$insertdetails['chpsize']    			   	       = $product->psize;
					$insertdetails['chpcolor']                         = $product->pcolor;
					$insertdetails['chDiscount']   	      	           = $product->Discount;
					$insertdetails['chpvat']   	      	               = $product->pvat;
					$insertdetails['chQuantity']   	      	   		   = $sentqty[$i];
					$insertdetails['delvtime']   	      	   		   = $delvartime;
					$insertdetails['chrider']   	      	   		   = $rider;
					$insertdetails['UserIDInserted']   	      		   = $setting_id;
					$insertdetails['UserIDUpdated']   	      		   = $setting_id;
					$insertdetails['UserIDLocked']   	      		   = $setting_id;
					$insertdetails['DateInserted']   	      		   = date('Y-m-d H:i:s');
					$insertdetails['DateUpdated']   	      		   = date('Y-m-d H:i:s');
					$insertdetails['DateLocked']   	      		   	   = date('Y-m-d H:i:s');
					if($sentqty[$i]>0){
						$deliverystock =  $this->Super_admin_model->deliveredqty($product->ProductID,$branch,$product->psize,$product->pcolor);
						if(!empty($deliverystock)){
							$stockid=$deliverystock->stockid;
							$stockqty=$deliverystock->pquantity;
							$updatestockqty=$stockqty-$sentqty[$i];
							$stockupdate['pquantity']=$updatestockqty;
							$this->Foodmartstore_model->update_info('tbl_productstock', $stockupdate, 'stockid', $stockid);
							}
						
						$this->Foodmartstore_model->insert_data('tbl_chalan', $insertdetails);
					}
			
			$i++;
			}
		}
		if($orderstatus==2){
			
			}
		if($orderstatus==3){
			
			}
		}
	public function deliveredorder()
    {
        $data=array();
        $data['title']='View Complete Order';
        $data['orderinfo']=  $this->Super_admin_model->read_alldeliveredorder();
        $data['content']=$this->load->view('admin/orderinfo/complete',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function cancelorder()
    {
        $data=array();
        $data['title']='View Cancel Order';
        $data['orderinfo']=  $this->Super_admin_model->read_allcancelorder();
        $data['content']=$this->load->view('admin/orderinfo/cancel',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function partial()
    {
        $data=array();
        $data['title']='View Incomplete Order';
        $data['orderinfo']=  $this->Super_admin_model->read_allpartialorder();
        $data['content']=$this->load->view('admin/orderinfo/partial',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addneworder()
    {
        $data=array();
        $data['title']='Place a Order';
       	$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');

        $data['content']=$this->load->view('admin/orderinfo/addorder',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addtocartitem(){
		$productID=$this->input->post('ProductID');
		$qty=$this->input->post('qty');
		$size=$this->input->post('size');
		$color=$this->input->post('color');
		$price=$this->input->post('price');
		$productinfo= $this->Super_admin_model->readgroupproduct($productID);
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
				  $this->load->view('admin/orderinfo/cartview');
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
			$this->load->view('admin/orderinfo/cartview');
		}
		}
	public function updatecartitem(){
		$currentid=$this->session->userdata('UserID');
		$cartID=$this->input->post('CartID');
		$productqty=$this->input->post('qty');
			$data = array(
				'rowid'=>$cartID,
				'qty'=>$productqty
				);
				$this->cart->update($data);
			$this->load->view('admin/orderinfo/cartview');
		}
	public function deleteItemadmin(){
		$cartID=$this->input->post('CartID');
		$data = array(
				'rowid'   => $cartID,
				'qty'     => 0
			);
		$this->cart->update($data);
		$this->load->view('admin/orderinfo/cartview');
	}
	public function placeneworder(){
			$name=$this->input->post('name');
			$email=$this->input->post('email');
			$phone=$this->input->post('Phone');
			$country=$this->input->post('password');
			$address=$this->input->post('Address');
			$shipping=$this->input->post('shipping-method');
			$paymentmethod=$this->input->post('payment-method');
			$fullname=$name;
			
				$password=$this->input->post('password');
				$datain['cus_firstname']       			   = $name;
				$datain['cus_lastname']       			   = "crm";
				$datain['cus_password']    				   = md5($password);
			    $datain['cus_email']                       = $email;
				$datain['cus_phone']   	      		   	   = $phone;
				$datain['DateInserted']   	   		       = date('Y-m-d H:i:s');
				$datain['DateUpdated']   	   			   = date('Y-m-d H:i:s');
				$insert_ID = $this->Super_admin_model->insert_data('tbl_customer', $datain);
				$currentid=$insert_ID;
				
			//Shipping address Save
			$insertshipping['userid']       			   = $currentid;
			$insertshipping['firstname']       			   = $name;
			$insertshipping['lastname']       			   = "";
			$insertshipping['email']    				   = $email;
			$insertshipping['phone']                       = $phone;
			$insertshipping['address']   	      		   = $address;
			$insertshipping['DateInserted']   	   		   = date('Y-m-d H:i:s');
			$insertshipping_ID = $this->Super_admin_model->insert_data('tbl_delivaryaddress', $insertshipping);
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
			$orderid_ID = $this->Super_admin_model->insert_data('tbl_orders', $insertsorder);
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
					$this->Super_admin_model->insert_data('tbl_orderdetails', $insertdetails);
					$tabletr.='<tr>
					<td>'.$item['name'].'</td>
					<td>'.$item['qty'].'</td>
					<td>'.$item['price'].'</td>
					<td>'.$item['price']*$item['qty'].'</td>
					</tr>';
				}
			}
			$sdata['message']='Order Placed Successfully!!';
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
			$this->session->set_userdata($sdata);
			redirect('Addnew-order');
		
		}
	//Sms
	 public function Smsmanager()
		{
			$data=array();
			$data['title']='Sms Manage';
			$data['customer_info']=$this->Super_admin_model->read_allgroupby('*', 'tbl_customer','custid','cus_phone');
			$data['content']=$this->load->view('admin/smsemail/smsmanage',$data,TRUE);
			$this->load->view('admin/master/master',$data);
		}
	public function Emailmanager()
		{
			$data=array();
			$data['title']='Email Manage';
			$data['customer_info']=$this->Super_admin_model->read_allgroupby('*', 'tbl_customer','custid','cus_email');
			$data['content']=$this->load->view('admin/smsemail/emailmanage',$data,TRUE);
			$this->load->view('admin/master/master',$data);
		}
	
	public function sendsmsemailtext()
		{
			$this->load->library('excel');
			$sms=$this->input->post('sms');
			$smsoremail=$this->input->post('smsoremailtype');
			$tytpe=$this->input->post('type');
			$file=$this->input->post('fileUpload');
        	if($tytpe==0){
            $path = 'uploads/xlsfiles/';
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|jpg|png';
            $config['remove_spaces'] = TRUE;
            //$this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('fileUpload')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array('Name', 'Email','Contact_NO');
            $makeArray = array('Name' => 'Name', 'Email' => 'Email', 'Contact_NO' => 'Contact_NO');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    } else {
                        
                    }
                }
            }
            $data = array_diff_key($makeArray, $SheetDataKey);
           
            if (empty($data)) {
                $flag = 1;
            }
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $addresses = array();
                    $Name = $SheetDataKey['Name'];
                    $email = $SheetDataKey['Email'];
                    $contactNo = $SheetDataKey['Contact_NO'];
                    $YourName = filter_var(trim($allDataInSheet[$i][$Name]), FILTER_SANITIZE_STRING);
                    $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_EMAIL);
                    $contactNo = filter_var(trim($allDataInSheet[$i][$contactNo]), FILTER_SANITIZE_STRING);
					
                    $fetchData[] = array('first_name' => $YourName, 'email' => $email, 'contact_no' => "0".$contactNo);
					
					if($smsoremail=="sms"){  
						SendSMS("880".$contactNo,$SMS =$sms);
						echo "SMS Send Successfully";
					}
				else{
					// mail user their information
					$subject="Foodmart Store Inquery";
					$email=$email;
					$webmaster = 'foodmartstore.com';
					$youremail = 'ainalce@gmail.com';
					$subject = $subject;
					$name = $YourName;
		   $config = Array(        
                'mailtype'  => 'html', 
                'charset'   => 'utf-8',
                'wordwrap' => TRUE
            );
			$this->email->initialize($config);
            $this->email->from($youremail, 'Foodmartstore');
			$data['message'] = $sms;
			$data['name'] = $name;
            $this->email->to($email);  
            $this->email->subject($subject); 
            $body = $this->load->view('admin/smsemail/sendmail',$data,TRUE);
            $this->email->message($body);   
            $this->email->send();
			echo "Email Send Successfully";
					}
                }
				
                /* 
				$data['employeeInfo'] = $fetchData;
                $this->import->setBatchImport($fetchData);
                $this->import->importData();*/
            } else {
                echo "Please import correct file";
            }
			}
			else{
				$phonumberlist=$this->input->post('userpnone');
				$customerlist=$this->input->post('customername');
				$emaillist=$this->input->post('customeremail');
				$totallist=count($phonumberlist);
				//$emailtext="<p>".$customerlist."</p>".$sms;
				
					if($smsoremail=="sms"){ 
					for($i=0;$i<$totallist;$i++){
					// Send SMS
					SendSMS("88".$phonumberlist[$i],$SMS =$sms);
					}
					echo "SMS Send Successfully";
					}
					else{
						// mail user their information
						for($i=0;$i<$totallist;$i++){
						$subject="Foodmart Store Inquery";
						$email=$emaillist[$i];
						$webmaster = 'foodmartstore.com';
						$youremail = 'ainalce@gmail.com';
						$subject = $subject;
						$name = $customerlist[$i];
						
						
			   $config = Array(        
					'mailtype'  => 'html', 
					'charset'   => 'utf-8',
					'wordwrap' => TRUE
				);
				$this->email->initialize($config);
				$this->email->from($youremail, 'Foodmartstore');
				$data['message'] = $sms;
				$data['name'] = $name;
				$this->email->to($emaillist[$i]);  
				$this->email->subject($subject); 
				$body = $this->load->view('admin/smsemail/sendmail',$data,TRUE);
				$this->email->message($body);   
				$this->email->send();
						}
						echo "Email Send Successfully";
					}
				}
		}
	//Accounts
	
	public function addheadcategory()
    {
       $data=array();
        $data['title']='Add Head Category';
        $data['headcat_info']=  $this->Super_admin_model->read_all('*', 'tbl_headcate','headcatid','');
        $data['content']=$this->load->view('admin/accounts/add_headcategory',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function saveheadcategory()
    {
        $sdata=array();
		$pagename= $this->input->post('hname');
		$ins['headcatname']           = $this->input->post('hname');
		$ins['headcattype']           = $this->input->post('cattype');
		$ins['status']             = 1;
		$ins['DateInserted']       = date('Y-m-d H:i:s');
		$result=$this->Super_admin_model->read('*', 'tbl_headcate', array('headcatname' => $pagename));
		if(!empty($result)){
        $sdata['message']='This Category is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_headcate', $ins);
        $sdata['message']='Head Category Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Head-category');
    }
	public function deletedeheadcatById($department)
    {
	   $this->Super_admin_model->deleteitem('tbl_headcate', 'headcatid', $department);
       redirect('Add-Head-category');
    }
	public function editheadcategoryInfo($id)
    {
        $data=array();
        $data['info_by_id']=$this->Super_admin_model->read('*', 'tbl_headcate', array('headcatid' => $id));
        $data['title']='Edit Head Category';
		$data['content']=$this->load->view('admin/accounts/edit_headcategory',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updateheadcategoryInfoById()
    {
        $sdata=array();
		$pageid=  $this->input->post('editid');
        $pagename= $this->input->post('hname');
		$ins['headcatname']           = $this->input->post('hname');
		$ins['headcattype']           = $this->input->post('cattype');
		$ins['status']             = 1;
		$result=$this->Super_admin_model->read('*', 'tbl_headcate', array('headcatname' => $pagename));
		$total=count($result);
		if($total>1){
        $sdata['message']='This Category is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->update_date('tbl_headcate', $ins, 'headcatid', $pageid);
        $sdata['message']='Head Category Update Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Head-category');
    }
	
	public function addhead()
    {
       $data=array();
        $data['title']='Add Head';
		$data['headcat_info']=  $this->Super_admin_model->read_all('*', 'tbl_headcate','headcatid','');
        $data['head_info']=  $this->Super_admin_model->allhead('tbl_headcate.headcatname,tbl_head.*', 'tbl_head','tbl_headcate','tbl_head.headid','tbl_headcate.headcatid','tbl_head.headcat');
        $data['content']=$this->load->view('admin/accounts/add_head',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function savehead()
    {
        $sdata=array();
		$pagename= $this->input->post('hname');
		$ins['headcat']            = $this->input->post('headcategory');
		$ins['headname']           = $this->input->post('hname');
		$ins['status']             = $this->input->post('isactive');
		$ins['DateInserted']       = date('Y-m-d H:i:s');
		$result=$this->Super_admin_model->read('*', 'tbl_head', array('headname' => $pagename));
		if(!empty($result)){
        $sdata['message']='This Head is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->insert_data('tbl_head', $ins);
        $sdata['message']='Head Save Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Head');
    }
	public function deletedeheadById($department)
    {
	   $this->Super_admin_model->deleteitem('tbl_head', 'headid', $department);
       redirect('Add-Head');
    }
	public function editheadInfo($id)
    {
        $data=array();
		$data['headcat_info']=  $this->Super_admin_model->read_all('*', 'tbl_headcate','headcatid','');
        $data['info_by_id']=$this->Super_admin_model->read('*', 'tbl_head', array('headid' => $id));
        $data['title']='Edit Head';
		$data['content']=$this->load->view('admin/accounts/edit_head',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updateheadInfoById()
    {
        $sdata=array();
		$pageid=  $this->input->post('editid');
        $pagename= $this->input->post('hname');
		$ins['headcat']            = $this->input->post('headcategory');
		$ins['headname']           = $this->input->post('hname');
		$ins['status']             = $this->input->post('isactive');
		$result=$this->Super_admin_model->read('*', 'tbl_head', array('headname' => $pagename));
		$total=count($result);
		if($total>1){
        $sdata['message']='This Head is exits please  choose another!!!';
		}
		else{
		$insert_ID = $this->Super_admin_model->update_date('tbl_head', $ins, 'headid', $pageid);
        $sdata['message']='Head Update Successfully !!!';
        $this->session->set_userdata($sdata);
		}
        redirect('Add-Head');
    }
	public function viewexpenceincome()
    {
        $data=array();
        $data['title']='View expence/income';
		$data['all_expenceincome']=  $this->Super_admin_model->allhead('tbl_head.headname,tbl_expenceincome.*', 'tbl_expenceincome','tbl_head','tbl_expenceincome.expenceincomeid','tbl_head.headid','tbl_expenceincome.headids');
        $data['content']=$this->load->view('admin/accounts/viewexpenceincome',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function addexpenceincome()
    {
        $data=array();
        $data['title']='Add expence/income';
		$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_headcate','headcatid','');
        $data['content']=$this->load->view('admin/accounts/addexpenceincome',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function saveexpenceincome()
    {
        $sdata=array();
		$ins['headcatid']            = $this->input->post('headcategory');
		$ins['headids']            	 = $this->input->post('headlist');
		$ins['expenceincometype']    = $this->input->post('exintype');
		$ins['expenceincomedate']    = $this->input->post('paymentdate');
		$ins['voucherno']            = $this->input->post('Voucher');
		$ins['amount']               = $this->input->post('Amount');
		$ins['remarks']              = $this->input->post('descrip');
		$ins['adminapprove']         = 1;
		$ins['staus']               = 1;
		$ins['DateInserted']         = date('Y-m-d H:i:s');
		$insert_ID = $this->Super_admin_model->insert_data('tbl_expenceincome', $ins);
        $sdata['message']='Expense/income Saved Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('Add-expenceincome');
    }
	public function deletexpenceincomeId($department)
    {
	   $this->Super_admin_model->deleteitem('tbl_expenceincome', 'expenceincomeid', $department);
       redirect('View-expenceincome');
    }
	public function editexpenceincome($id)
    {
        $data=array();
		$data['info_by_id']=$this->Super_admin_model->read('*', 'tbl_expenceincome', array('expenceincomeid' => $id));
		$catid=$data['info_by_id']->headcatid;
		$data['all_category_info']=  $this->Super_admin_model->read_all('*', 'tbl_headcate','headcatid','');
        $data['all_head']=$this->Super_admin_model->allheadbyid('*', 'tbl_head','headid','headcat',$catid);
        $data['title']='Edit Expence/income';
		$data['content']=$this->load->view('admin/accounts/editexpenceincome',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
    public function updatexpenceincomeId()
    {
        $sdata=array();
		$pageid=  $this->input->post('editid');
        $ins['headcatid']            = $this->input->post('headcategory');
		$ins['headids']            	 = $this->input->post('headlist');
		$ins['expenceincometype']    = $this->input->post('exintype');
		$ins['expenceincomedate']    = $this->input->post('paymentdate');
		$ins['voucherno']            = $this->input->post('Voucher');
		$ins['amount']               = $this->input->post('Amount');
		$ins['remarks']              = $this->input->post('descrip');
		$insert_ID = $this->Super_admin_model->update_date('tbl_expenceincome', $ins, 'expenceincomeid', $pageid);
        $sdata['message']='Expense?income Update Successfully !!!';
        $this->session->set_userdata($sdata);
        redirect('View-expenceincome');
    }
	public function expenseincomereport(){
		$data=array();
        $data['title']='Expense Income Report';
        $data['content']=$this->load->view('admin/report/expenseincomereport',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		}
	public function getexpensereport(){
		 $fromdate=$this->input->post('formdate');
		 $pieces = explode(",", $fromdate);
		 $monthnumber = $pieces[0];
		 $year =$pieces[1];
		 
		$newtitledate=$year.'-'.$monthnumber.'-01';
		$data['lastday']=date('t',strtotime($newtitledate));
		$lastday = date('t',strtotime($newtitledate));
		$data['firstdate']=date($year.'-'.$monthnumber.'-01');
		$firstdate = date($year.'-'.$monthnumber.'-01');
		$data['lastdate']=date($year.'-'.$monthnumber.'-'.$lastday);
		$lastdate = date($year.'-'.$monthnumber.'-'.$lastday);
		$datediff = strtotime($lastdate) - strtotime($firstdate);
		$datediff = floor($datediff/(60*60*24));
		$data['datediff'] = $datediff;
		$first_date=strtotime($newtitledate);
		$data['first_date'] = strtotime($newtitledate);
		$second_date= strtotime('-1 day', $first_date);
		$data['second_date'] = strtotime('-1 day', $first_date);
		$data['fast_dateprev'] = date('Y-m-01', $second_date);
		$data['last_dateprev'] = date('Y-m-d', $second_date);
		$data['showdate'] =date("F, Y", strtotime($newtitledate));
		$data['all_expence']=$this->Super_admin_model->expencelist();
		$data['all_income']=$this->Super_admin_model->incomelist();
		$this->load->view('admin/report/viewexinreport',$data);
		}
	public function gethead(){
		 $headcat=$this->input->post('cathead');
		 $allhead=$this->Super_admin_model->allheadbyid('*', 'tbl_head','headid','headcat',$headcat);
		 $fullist='<option value="">Select Head</option>';
		 foreach($allhead as $headlist){
				$fullist.='<option value="'.$headlist->headid.'">'.$headlist->headname.'</option>';
			 }
		 echo $fullist;
		}
	public function getexpenceincome(){
		 $fromdate=$this->input->post('formdate');
		 $todate=$this->input->post('todate');
		 $data['all_expenceincome']=$this->Super_admin_model->searchbydate('tbl_head.headname,tbl_expenceincome.*', 'tbl_expenceincome','tbl_expenceincome.expenceincomeid','tbl_expenceincome.expenceincomedate',$fromdate,$todate,'tbl_head','tbl_head.headid','tbl_expenceincome.headids');
		 $this->load->view('admin/accounts/searchdata',$data);
		}
	public function onlineprofit(){
		$data=array();
        $data['title']='Online Profit';
        $data['content']=$this->load->view('admin/report/onlineprofit',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		
		}
	public function getonlineprofit(){
		$startdate            = $this->input->post('formdate');
		$enddate              = $this->input->post('todate');
		$data['fromdate']	  =$startdate;
		$data['todate']		  =$enddate;
		$data['monthyear'] = date("F d, Y", strtotime($startdate))." to ".date("F d, Y", strtotime($enddate));
		$data['allonlineprofit']=$this->Super_admin_model->onlineprofit($startdate,$enddate);
		$this->load->view('admin/report/viewonlineprofit',$data);
		}
	public function sellreport(){
		$data=array();
        $data['title']='Sell Report';
		$data['all_product_info']=  $this->Super_admin_model->read_all('*', 'tbl_products','productid','delation_status');
		$data['all_branch_info']=  $this->Super_admin_model->read_all('*', 'tbl_branch','branchid','');
		$data['all_size_info']=  $this->Super_admin_model->read_all('*', 'tbl_size','itemsizeid','');
		$data['all_color_info']=  $this->Super_admin_model->read_all('*', 'tbl_color','colorid','');
        $data['content']=$this->load->view('admin/report/sellreport',$data,TRUE);
        $this->load->view('admin/master/master',$data);
		}
	public function getsellreport(){
		$startdate            = $this->input->post('formdate');
		$enddate              = $this->input->post('todate');
		$productid            = $this->input->post('productid');
		$branch              = $this->input->post('branch');
		$itemsize            = $this->input->post('itemsize');
		$pcolor              = $this->input->post('pcolor');
		$data['fromdate']	  =$startdate;
		$data['todate']		  =$enddate;
		$data['productid']	  =$productid;
		$data['branch']		  =$branch;
		$data['itemsize']	  =$itemsize;
		$data['pcolor']		  =$pcolor;
		
		$data['monthyear'] = date("F d, Y", strtotime($startdate))." to ".date("F d, Y", strtotime($enddate));
		$data['sellreports']=$this->Super_admin_model->sellreport($data);
		$this->load->view('admin/report/viewsellreport',$data);
		}
	 //Inventory
    public function inventory()
    {
        $data=array();
        $data['title']='Inventory';
		$data['currentstock']=$this->Super_admin_model->fullstock();
		$data['soldstock']=$this->Super_admin_model->stockliststatus(2);
		$data['processstock']=$this->Super_admin_model->stocklistprocess();
		$data['orderstock']=$this->Super_admin_model->stocklistorder(3);
		$allsellproductlist=$this->Super_admin_model->sellproductlist(2);
		$allpurchaseprice=0;
		$allsellprice=0;
		foreach($allsellproductlist as $plist){
						$getpricelist =  $this->Super_admin_model->getpurchaseprice($plist->ProductID,$plist->psize,$plist->pcolor);
						if(!empty($getpricelist)){
							$totalpurchase=$getpricelist->purchase_rate*$plist->Quantity;
							$totalsell=$getpricelist->sell_rate*$plist->Quantity;
							//$getrate=$this->Super_admin_model->purchaseamountinsaleproduct($getpricelist);
							$allpurchaseprice=$allpurchaseprice+$totalpurchase;
						    $allsellprice=$allsellprice+$totalsell;
							}
			}
		$data['getallpruchaseinsale']=$allpurchaseprice;
		$data['allsaleprice']=$this->Super_admin_model->saleamount(2);
        $data['content']=$this->load->view('admin/dashboard/inventory',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
	public function alltock()
    {
        $data=array();
        $data['title']='All Stock';
		$data['allstock']=$this->Super_admin_model->allstocklist();
        $data['content']=$this->load->view('admin/stockmanage/allstock',$data,TRUE);
        $this->load->view('admin/master/master',$data);
    }
}
