<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin_model
 *
 * @author linktech
 */
class Admin_Model extends CI_Model{
    //put your code here
    public function adminLoginCheckInfo()
    {
        $admin_email_address= $this->input->post('admin_email_address',TRUE);
        $admin_password= md5($this->input->post('admin_password',TRUE));
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('user_email',$admin_email_address);
        $this->db->where('user_pass',$admin_password);
        $this->db->where('status',1);
        $query_result=  $this->db->get();
        $result=$query_result->row();
		if($result){
			$sessiondata = array(
				'userid' =>$result->uid,
				'username' =>$result->user_login,
				'email' =>$result->user_email,
				'role' =>$result->user_status
			);
			//print_r($sessiondata);
			$this->session->set_userdata($sessiondata);
			}
        return $result;
    }
    public function empLoginCheckInfo()
    {
        $admin_email_address= $this->input->post('admin_email_address',TRUE);
        $admin_password= md5($this->input->post('admin_password',TRUE));
        $this->db->select('*');
        $this->db->from('tbl_employee');
        $this->db->where('empemail',$admin_email_address);
        $this->db->where('emppassword',$admin_password);
        $this->db->where('empstatus',1);
        $query_result=  $this->db->get();
        $result=$query_result->row();
		if($result){
			$myid=$result->empid;
			$workingtime = $result->workingtime;
			$logtime=explode('-',$workingtime);
			$logiintime = $logtime[0];
			$logouttime = $logtime[1];
			$weekend = $result->weekend;
			$newTime = date("h:i A",strtotime($logiintime." -30 minutes"));
			$newlogoutTime = date("h:i A",strtotime($logouttime." +15 minutes"));
			$actualtime=date('h:i A');
			$sortactualtime = strtotime($actualtime)."<br>";
			$currentday=date('l');
			$sortlogin = strtotime($newTime)."<br>";
			$sortlogout = strtotime($newlogoutTime)."<br>";
				if(($sortactualtime > $sortlogin) && ($sortactualtime<$sortlogout) &&($currentday!=$weekend)){
					$emdata['emid']=$myid;
					$emdata['intime']=$actualtime;
					$emdata['outtime']="";
					$emdata['DateInserted']=date('Y-m-d H:i:s');
					$this->db->insert('tbl_attendness',$emdata);
					
					$sessiondata = array(
				'employeid' =>$result->empid,
				'employename' =>$result->empName,
				'employeemail' =>$result->empemail,
				'emptype' =>$result->empdepartment,
				'role' =>$result->user_status
			);
					$this->session->set_userdata($sessiondata);
			 		return $result;
				}
				else{
					return false;
					}
			
			}
       
    }    
}
