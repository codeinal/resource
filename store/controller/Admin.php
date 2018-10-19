<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author linktech
 */
class Admin extends CI_Controller{
    public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Dhaka');
        $admin_id=$this->session->userdata('userid');
        if($admin_id!=NULL)
        {
            redirect('Super-Admin-Login-Check-Refresh','refresh');
        }
    }
    //put your code here
    public function index()
    {
        $this->load->view('admin/login/login');
    }
    public function AdminLoginCheck()
    {
        $this->load->model('Admin_model');
        $result=$this->Admin_model->adminLoginCheckInfo();
        $sdata=array();
        if($result)
        {
            $sdata['userid']=$result->uid;
            $sdata['username']=$result->user_login;
			$sdata['email']=$result->user_email;
            $sdata['role']=$result->user_status;
           redirect('Super-admin');
		   //echo "login s";
        }
        else
        {
            $sdata['exceptional']='Your Password Or Username Are Invalid ???';
		    $this->session->set_userdata($sdata);
            redirect('admin-panel',$sdata);
        }
    }
	
	public function emplogin()
    {
        $sdata['exceptional']='';
        $this->load->view('admin/login/login2', $sdata);
    }
    public function empLoginCheck()
    {
        $this->load->model('Admin_model');
        $result=$this->Admin_model->empLoginCheckInfo();
        $sdata=array();
        if($result)
        {
            $sdata['employeid']=$result->empid;
            $sdata['employename']=$result->empName;
			$sdata['employeemail']=$result->empemail;
            $sdata['role']=$result->user_status;
			$sdata['emptype']=$result->empdepartment;
            redirect('Employee-admin');
		   //echo "login s";
        }
        else
        {
           // echo 'Your Password Or Username Are Invalid ???';
        $sdata['exceptional']='Your Password Or Username Are Invalid ???';
         $this->session->set_userdata($sdata);
		   redirect('admin-login',$sdata);
        }
    }
}
