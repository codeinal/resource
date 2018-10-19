<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Super_admin_model
 *
 * @author linktech
 */
class Super_Admin_Model extends CI_Model{
    //put your code here
     
		
	public function check_user_email_address($text)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('user_login',$text);
        $query_result=$this->db->get();
        $result=$query_result->row();
        return $result;
    }
	public function saveSlidertypeInfo()
    {
        $data=array();
		$data['STypeName']=  $this->input->post('stype',TRUE);
        $this->db->insert('tbl_slider_type',$data);
    }
    public function selectAllSlidertypeInfo()
    {
        $this->db->select('*');
        $this->db->from('tbl_slider_type');
        $this->db->order_by('stype_id','desc');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
    }
	public function selectAllSlidertypeInfoById($slider_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_slider_type');
        $this->db->where('stype_id',$slider_id);
        $query_result=  $this->db->get();
        $result=$query_result->row();
        return $result;
    }
    public function updateSliderAlltypeInfoById()
    {
        $data=array();
        $slider_id= $this->input->post('slidertype_id',TRUE);
		$data['STypeName']=  $this->input->post('stype',TRUE);
        $this->db->where('stype_id',$slider_id);
        $this->db->update('tbl_slider_type',$data);
    }
	
    public function saveSliderImageInfo()
    {
        $data=array();
		$data['Sltypeid']=  $this->input->post('s_type',TRUE);
		$data['title']=  $this->input->post('stitle',TRUE);
		$data['subtitle']=  $this->input->post('ssubtitle',TRUE);
		$data['slink']=  $this->input->post('link',TRUE);
//        $data['slider_image']=  $this->input->post('slider_image',TRUE);
          /*
         * ---------start image upload------------
         */ 
                $config['upload_path']          = 'uploads/slider_images/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
                if (! $this->upload->do_upload('slider_image'))
                {
                       $error = array('error' => $this->upload->display_errors());
                        $data['image']='';
                }
                else
                {
                        $fdata =$this->upload->data();
                        $config1=array(
                            'source_image' => $fdata['full_path'],
                            'new_image' => $fdata['file_path'],
                            'maintain_ratio' => TRUE,
                            'height' => 600
                        );
                        $this->load->library('image_lib', $config1);
                        $this->image_lib->resize();
						$data['image']=$fdata['file_name'];
                }
        $data['status']=  $this->input->post('activation_status',TRUE);
        /*
         * ---------start image upload------------
         */
        $this->db->insert('tbl_slider',$data);
    }
    public function selectAllSliderInfo()
    {
        $this->db->select('*');
        $this->db->from('tbl_slider');
        $this->db->where('delation_status',0);
        $this->db->order_by('slid','desc');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
    }
    public function updateActivationStatusInfoById($slider_id)
    {
        $this->db->where('slid',$slider_id);
        $this->db->set('status',0);
        $this->db->update('tbl_slider');
    }
    public function updateDeactivationStatusInfoById($slider_id)
    {
        $this->db->where('slid',$slider_id);
        $this->db->set('status',1);
        $this->db->update('tbl_slider');
    }
    public function deleteSliderInfoById($slider_id)
    {
        $this->db->where('slid',$slider_id);
        $this->db->set('delation_status',1);
        $sql="select * from tbl_slider where slid='$slider_id'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row(); 
        unlink("uploads/slider_images/$result->image");
        
        $this->db->update('tbl_slider');
    }
	public function deleteitem($tablename,$fieldname,$item_id)
    {
		$this->db->where($fieldname, $item_id);
   		$del=$this->db->delete($tablename); 
		return $del;
    }
    public function selectAllSliderInfoById($slider_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_slider');
        $this->db->where('slid',$slider_id);
        $query_result=  $this->db->get();
        $result=$query_result->row();
        return $result;
    }
    public function updateSliderAllInfoById()
    {
        $data=array();
		$data['Sltypeid']=  $this->input->post('s_type',TRUE);
        $slider_id= $this->input->post('slider_id',TRUE);
        $data['title']=  $this->input->post('stitle',TRUE);
		$data['subtitle']=  $this->input->post('ssubtitle',TRUE);
		$data['slink']=  $this->input->post('link',TRUE);
//        $data['slider_image']=  $this->input->post('slider_image',TRUE);
          /*
         * ---------start image upload------------
         */ 
                $config['upload_path']          = 'uploads/slider_images/';
                $config['allowed_types']        = '*';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
                
                $sql="select * from tbl_slider where slid='$slider_id'";
                $query_result=  $this->db->query($sql);
                $result=$query_result->row(); 
                
                $image=$this->upload->do_upload('slider_image');

                if($image != ""){
                    if($result->image){
                        unlink("uploads/slider_images/$result->image");
            }
                
                        $fdata =$this->upload->data();
                        $config1=array(
                            'source_image' => $fdata['full_path'],
                            'new_image' => $fdata['file_path'],
                            'maintain_ratio' => true,
                        );
                        $this->load->library('image_lib', $config1);
                        $this->image_lib->resize();
                
        $data['image']=$fdata['file_name'];
                }
                else 
                {
                   $data['image']=$result->image;
                }
        $data['status']=  $this->input->post('activation_status',TRUE);
        /*
         * ---------start image upload------------
         */
        
        $this->db->where('slid',$slider_id);
        $this->db->update('tbl_slider',$data);
    }
	
   public function saveGalleryImageInfo()
    {
        $data=array();
          /*
         * ---------start image upload------------
         */ 
                $config['upload_path']          = 'uploads/gallery_images/';
                $config['allowed_types']        = 'gif|jpg|png|jpeg';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('slider_image'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        $data['imagethumb']='';
                }
                else
                {
                        $fdata =$this->upload->data();
                        $config1=array(
                            'source_image' => $fdata['full_path'],
                            'new_image' => $fdata['file_path'],
                            'maintain_ratio' => TRUE,
                            'height' => 600
                        );
                        $this->load->library('image_lib', $config1);
                        $this->image_lib->resize();
                }
        $data['gfeatureimg']=$fdata['file_name'];
		$data['gstatus ']=  $this->input->post('activation_status',TRUE);
        $this->db->insert('tbl_gallery',$data);
    }
    public function selectAllGalleryInfo()
    {
		 $sql="select * from tbl_gallery where delation_status=0 order by gallerid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
    }
    public function deleteGalleryInfoById($slider_id)
    {
        $this->db->where('gallerid',$slider_id);
        $this->db->set('delation_status',1);
        $sql="select * from tbl_gallery where gallerid='$slider_id'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row(); 
        unlink("uploads/gallery_images/$result->gfeatureimg");
        $this->db->update('tbl_gallery');
    }
    public function selectAllGalleryInfoById($slider_id)
    {
		 $sql="select * from tbl_gallery where  gallerid='".$slider_id."' AND delation_status=0 order by gallerid Desc";
		  $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
    }
    public function updateGalleryAllInfoById()
    {
        $data=array();
        $slider_id= $this->input->post('slider_id',TRUE);
//        $data['slider_image']=  $this->input->post('slider_image',TRUE);
          /*
         * ---------start image upload------------
         */ 
                $config['upload_path']          = 'uploads/gallery_images/';
                $config['allowed_types']        = 'gif|jpg|png|jpeg';
                $config['max_size']             = 100000;
                $this->load->library('upload', $config);
                
                $sql="select * from tbl_gallery where gallerid='$slider_id'";
                $query_result=  $this->db->query($sql);
                $result=$query_result->row(); 
                
                $image=$this->upload->do_upload('slider_image');

                if($image != ""){
                    if($result->gfeatureimg){
                        unlink("uploads/gallery_images/$result->gfeatureimg");
            }
                
                        $fdata =$this->upload->data();
                        $config1=array(
                            'source_image' => $fdata['full_path'],
                            'new_image' => $fdata['file_path'],
                            'maintain_ratio' => true,
                        );
                        $this->load->library('image_lib', $config1);
                        $this->image_lib->resize();
                
        $data['gfeatureimg']=$fdata['file_name'];
                }
                else 
                {
                   $data['gfeatureimg']=$result->gfeatureimg;
                }
		$data['gstatus']=  $this->input->post('activation_status',TRUE);
        $this->db->where('gallerid',$slider_id);
        $this->db->update('tbl_gallery',$data);
    }
	//category
	public function insert_data($table, $data)
		{
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}
		 public function update_date($table, $data, $field_name, $field_value)
			{
				$this->db->where($field_name, $field_value);
				$this->db->update($table, $data);
				return $this->db->affected_rows();
			}
		 public function read($select_items, $table, $where_array)
			{
			   
				$this->db->select($select_items);
				$this->db->from($table);
				foreach ($where_array as $field => $value) {
					$this->db->where($field, $value);
				}
				return $this->db->get()->row();
			}
		public function read_all($select_items, $table, $orderby,$delitem="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($delitem!=""){
			$this->db->where($delitem,0);
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function allhead($select_items, $table1,$table2, $orderby,$table2id,$table1id)
		{
			$this->db->select($select_items);
			$this->db->from($table1);
			$this->db->join($table2, $table1id.' = '.$table2id,'left');
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function allheadbyid($select_items, $table1,$orderby,$fieldid,$fieldvalue)
		{
			$this->db->select($select_items);
			$this->db->from($table1);
			if($fieldid!=""){
			$this->db->where($fieldid,$fieldvalue);
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function searchbydate($select_items, $table1,$orderby,$fieldname,$fielval1,$fieldvalue,$table2,$table2id,$table1id)
		{
			$this->db->select($select_items);
			$this->db->from($table1);
			$this->db->join($table2, $table1id.' = '.$table2id,'left');
			if($fieldname!=""){
			$this->db->where($fieldname." BETWEEN '".$fielval1 ."' AND '". $fieldvalue."'");
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function read_allgroupby($select_items, $table, $orderby,$groupby="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			$this->db->group_by($groupby);
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function alldatalist($select_items, $table, $orderby,$fieldname="",$fieldval="",$groupby="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($fieldname!=""){
			$this->db->where($fieldname,$fieldval);
			}
			$this->db->order_by($orderby,'ASC');
			$this->db->group_by($groupby);
			return $this->db->get()->result();
		}
		public function attendlist($fieldval)
		{
			$empid=$fieldval['emid'];
			$startdate=$fieldval['fromdate'];
			$enddate=$fieldval['todate'];
		$sql="select * from tbl_attendness where emid='".$empid."' AND DATE(DateInserted) Between '".$startdate."' AND '".$enddate."' Group BY DATE(DateInserted) order by attendid Asc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		}
		public function read_allinfo($select_items, $table, $orderby,$fieldname="",$fielvalue="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($fieldname!=""){
			$this->db->where($fieldname,$fielvalue);
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function read_allrecord($select_items, $table, $orderby,$fieldname="",$fielvalue="",$fieldname2="",$fieldvalue2="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($fieldname!=""){
			$this->db->where($fieldname,$fielvalue);
			}
			if($fieldname2!=""){
			$this->db->where($fieldname2,$fieldvalue2);
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		public function leave_count($tablename,$fieldname,$fieldvalue,$fieldname2,$fieldvalue2) {
        $this->db->from($tablename);
        $this->db->where($fieldname, $fieldvalue);
		$this->db->where($fieldname2, $fieldvalue2);
		$this->db->where('applicationtype', 1);
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return "0";
		
    }
		public function pages()
		{
		$sql="select tbl_pages.pagename,tbl_pagecontent.* from tbl_pagecontent Left Join tbl_pages ON tbl_pages.pageid=tbl_pagecontent.pagesid where pagesid!=2 order by tbl_pages.pagename Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		}
		public function faqpages()
		{
		$sql="select tbl_pages.pagename,tbl_pagecontent.* from tbl_pagecontent Left Join tbl_pages ON tbl_pages.pageid=tbl_pagecontent.pagesid where pagesid=2 order by tbl_pages.pagename Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		}
		public function checkpages($id)
		{ 
		$sql="select tbl_pagecontent.* from tbl_pagecontent Where pagesid!='2' AND pagesid='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->num_rows();
		if( $result > 0 ) {
            return $result;
        }
        return "0";
		}
		public function readcat($id)
		{
		$sql="select * from tbl_menucategory where catslug='".$id."' AND delation_status=0 order by menucatid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		}
		 public function readitem($id)
			{
				$sql="select * from tbl_products where productid='".$id."' AND delation_status=0 order by productid Desc";
		  $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
			}
		public function read_allitems()
		{
			 $sql="select * from tbl_products where delation_status=0 order by productid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
		}
		public function readgimage($id)
		{
		$sql="select * from tbl_galleryimg where gallerid='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
		}
	
		public function read_productallimage(){
				$sql="select tbl_galleryimg.*,tbl_products.productname from tbl_galleryimg left join tbl_products on tbl_products.productid=tbl_galleryimg.gallerid where tbl_galleryimg.delation_status=0 order by tbl_galleryimg.gallerimgid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
	

		public function allstock(){
		$sql="select tbl_color.colorname,tbl_size.itemsize,tbl_branch.Branchname,tbl_menucategory.mecatname,tbl_products.productname,tbl_productstock.* from tbl_productstock left join tbl_products on tbl_products.productid=tbl_productstock.proid Left Join tbl_menucategory ON tbl_menucategory.menucatid=tbl_productstock.catids Left Join tbl_branch ON tbl_branch.branchid=tbl_productstock.brid Left Join tbl_size ON tbl_size.itemsizeid=tbl_productstock.psizeid Left Join tbl_color ON tbl_color.colorid=tbl_productstock.pcolorid order by tbl_productstock.stockid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
		public function read_alltodayorder(){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where Date(DateInserted)='{$curdate}'  Group BY orderid order by orderid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
		public function read_allorder(){
			    
				$sql="select * from tbl_orders Group BY orderid order by orderid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
		public function read_singleorder($id){
		$sql="select tbl_orders.*,tbl_orderdetails.*,tbl_products.productname from tbl_orders left join tbl_orderdetails on tbl_orderdetails.OrdID=tbl_orders.orderid Inner join tbl_products ON tbl_products.productid=tbl_orderdetails.ProductID Where tbl_orderdetails.OrdID='".$id."' Group BY tbl_orderdetails.orderdetailsid order by tbl_orderdetails.orderdetailsid Asc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
			}
		public function record_order() {
        $sql="select * from tbl_orders where status='0' Group By orderid";
        $query=  $this->db->query($sql);
        $total_active_events = $query->num_rows();
		$allrows = $query->result();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return 0;
		
    }
		public function reliever_notice() {
		if($this->session->userdata('userid')==false){
			   $setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
        $sql="select * from tbl_leavemanage where reliverid='".$setting_id."' AND relievar_status='0'";
        $query=  $this->db->query($sql);
        $total_active_events = $query->num_rows();
		$allrows = $query->result();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return 0;
		
    }
	public function read_relievernotice(){
		if($this->session->userdata('userid')==false){
			   $setting_id=  $admin_id=$this->session->userdata('employeid');
			}
			else if($this->session->userdata('employeid')==false){
			$setting_id=  $admin_id=$this->session->userdata('userid');
			}
		$sql="select * from tbl_leavemanage left join tbl_employee on tbl_employee.empid=tbl_leavemanage.reliverid Where tbl_leavemanage.reliverid='".$setting_id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
			}
		public function countbyproductwithcolorsize($pid,$psize,$pcolor){
        $sql="select SUM(pquantity) as allqty from tbl_productstock where proid='".$pid."' AND psizeid='".$psize."' AND pcolorid='".$pcolor."' Group By proid";
        $query=  $this->db->query($sql);
        $total_active_events = $query->num_rows();
		$allrows = $query->row();
        if( $total_active_events > 0 ) {
			if(empty($allrows->allqty)){
				return 	$allqty=0;
				}
			else{
            return 	$allqty=$allrows->allqty;
			}

        }
        return 0;
		
    }
	public function returnorcancel($pdata){
		
	}
	public function deliveredqty($pid,$braid,$size,$color){
			$codition="proid='".$pid."' AND brid='".$braid."' AND psizeid='".$size."' AND pcolorid='".$color."'";
			$sql="select * from tbl_productstock where {$codition} Group By proid";
			$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
	}
		public function checkqty($pdata){
			$pid =$pdata['pid'];
			$branchid =$pdata['branchid'];
			$psize =$pdata['psize'];
			$pcolor =$pdata['pcolor'];
			$codition="";
			if(($branchid=="") && ($psize=="") && ($pcolor=="")){
				$codition="proid='".$pid."'";
				}
			if(($branchid!="") && ($psize=="") && ($pcolor=="")){
				$codition="proid='".$pid."' AND brid='".$branchid."'";
				}
			if(($branchid=="") && ($psize!="") && ($pcolor=="")){
				$codition="proid='".$pid."' AND psizeid='".$psize."'";
				}
			if(($branchid=="") && ($psize=="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND pcolorid='".$pcolor."'";
				}
			if(($branchid!="") && ($psize!="") && ($pcolor=="")){
				$codition="proid='".$pid."' AND brid='".$branchid."' AND psizeid='".$psize."'";
				}
			if(($branchid!="") && ($psize=="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND brid='".$branchid."' AND pcolorid='".$pcolor."'";
				}
			if(($branchid=="") && ($psize!="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND psizeid='".$psize."' AND pcolorid='".$pcolor."'";
				}
			if(($branchid!="") && ($psize!="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND brid='".$branchid."' AND psizeid='".$psize."' AND pcolorid='".$pcolor."'";
				}
			$sql="select SUM(pquantity) as allqty from tbl_productstock where {$codition} Group By proid";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->allqty)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->allqty;
				}
	
			}
			return 0;
			
		}
		public function checkqty2($pdata){
			$pid =$pdata['pid'];
			$psize =$pdata['psize'];
			$pcolor =$pdata['pcolor'];
			$codition="";
			if(($psize=="") && ($pcolor=="")){
				$codition="proid='".$pid."'";
				}
			if(($psize!="") && ($pcolor=="")){
				$codition="proid='".$pid."' AND psizeid='".$psize."'";
				}
			if(($psize=="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND pcolorid='".$pcolor."'";
				}
			if(($psize!="") && ($pcolor!="")){
				$codition="proid='".$pid."' AND psizeid='".$psize."' AND pcolorid='".$pcolor."'";
				}
			$sql="select SUM(pquantity) as allqty,sell_rate from tbl_productstock where {$codition} Group By proid";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->allqty)){
				return 	$allrows=0;
				}
				else{
				return 	$allrows;
				}
	
			}
			return 0;
			
		}
		public function fullstock(){
			$sql="SELECT SUM(pquantity) as currentstock FROM tbl_productstock ";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->currentstock)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->currentstock;
				}
			}
			return 0;
		}
		public function totalpamountamount(){
			$sql="SELECT SUM(purchase_rate*pquantity) as currentstock FROM tbl_productstock";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->currentstock)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->currentstock;
				}
			}
			return 0;
		}
		public function saleamount($id){
			$sql="SELECT Sum(tbl_orderdetails.Quantity*tbl_orderdetails.Price) as totalsoldqty FROM `tbl_orderdetails` Inner Join tbl_orders ON tbl_orders.orderid=tbl_orderdetails.OrdID Where tbl_orders.status={$id}";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->totalsoldqty)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->totalsoldqty;
				}
			}
			return 0;
		}
		public function sellproductlist($id){
			$sql="SELECT * FROM `tbl_orderdetails` Inner Join tbl_orders ON tbl_orders.orderid=tbl_orderdetails.OrdID Where tbl_orders.status={$id}";
			$query_result=  $this->db->query($sql);
			$result=$query_result->result();
			return $result;
			}
		public function onlineprofit($startdate,$enddate){
			$sql="SELECT * FROM `tbl_chalan` Inner Join tbl_orders ON tbl_orders.orderid=tbl_chalan.chOrdID Where tbl_orders.paymentmethod=1 AND DATE(tbl_orders.DateInserted) Between '".$startdate."' AND '".$enddate."' Group BY tbl_chalan.chalanid Order BY tbl_chalan.DateInserted ASC";
			$query_result=  $this->db->query($sql);
			$result=$query_result->result();
			return $result;
			}
		public function getpurchaseprice($pid,$psize,$pcolor){
			$sql="select * from tbl_productstock where proid='".$pid."' AND psizeid='".$psize."' AND pcolorid='".$pcolor."' Group By proid";
			$query_result=  $this->db->query($sql);
			$result=$query_result->row();
			return $result;
			}
        public function sellreport($pdata){
			$fromdate =$pdata['fromdate'];
			$todate =$pdata['todate'];
			$pid =$pdata['productid'];
			$branchid =$pdata['branch'];
			$psize =$pdata['itemsize'];
			$pcolor =$pdata['pcolor'];
			$codition="";
			if(($branchid=="") && ($psize=="") && ($pcolor=="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid!="") && ($psize=="") && ($pcolor=="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chbranch='".$branchid."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid=="") && ($psize!="") && ($pcolor=="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chpsize='".$psize."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid=="") && ($psize=="") && ($pcolor!="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chpcolor='".$pcolor."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid!="") && ($psize!="") && ($pcolor=="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chbranch='".$branchid."' AND tbl_chalan.chpsize='".$psize."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid!="") && ($psize=="") && ($pcolor!="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chbranch='".$branchid."' AND tbl_chalan.chpcolor='".$pcolor."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid=="") && ($psize!="") && ($pcolor!="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.psizeid='".$psize."' AND tbl_chalan.chpcolor='".$pcolor."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			if(($branchid!="") && ($psize!="") && ($pcolor!="")){
				$codition="tbl_chalan.chProductID='".$pid."' AND tbl_chalan.chbranch='".$branchid."' AND tbl_chalan.chpsize='".$psize."' AND tbl_chalan.chpcolor='".$pcolor."' AND Date(tbl_chalan.DateInserted) Between '".$fromdate."' AND '".$todate."'";
				}
			$sql="select tbl_color.colorname,tbl_size.itemsize,tbl_branch.Branchname,tbl_products.productname,tbl_chalan.* from tbl_chalan left join tbl_products on tbl_products.productid=tbl_chalan.chProductID Left Join tbl_branch ON tbl_branch.branchid=tbl_chalan.chbranch Left Join tbl_size ON tbl_size.itemsizeid=tbl_chalan.chpsize Left Join tbl_color ON tbl_color.colorid=tbl_chalan.chpcolor where {$codition} Group By tbl_chalan.chalanid";
			$query=  $this->db->query($sql);
			$result=$query->result();
			return $result;
			}
		public function registercustomer(){
			$sql="SELECT Count(custid) as totalcustomer FROM `tbl_customer`";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->totalcustomer)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->totalcustomer;
				}
			}
			return 0;
		}
		public function expencelist(){
			$sql="SELECT * FROM `tbl_headcate` Where headcattype='Expence' Order By headcatname Asc";
			$query_result=  $this->db->query($sql);
			$result=$query_result->result();
			return $result;
			}
		public function incomelist(){
			$sql="SELECT * FROM `tbl_headcate` Where headcattype='Income' Order By headcatname Asc";
			$query_result=  $this->db->query($sql);
			$result=$query_result->result();
			return $result;
			}
		public function stocklistorder($id){
			$sql="SELECT Sum(tbl_orderdetails.Quantity) as totalsoldqty FROM `tbl_orderdetails` Inner Join tbl_orders ON tbl_orders.orderid=tbl_orderdetails.OrdID Where tbl_orders.status!={$id}";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->totalsoldqty)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->totalsoldqty;
				}
			}
			return 0;
		}
		public function stocklistprocess(){
			$sql="SELECT Sum(tbl_orderdetails.Quantity) as totalsoldqty FROM `tbl_orderdetails` Inner Join tbl_orders ON tbl_orders.orderid=tbl_orderdetails.OrdID Where tbl_orders.status!='3' AND tbl_orders.status!='2'";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->totalsoldqty)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->totalsoldqty;
				}
			}
			return 0;
		}
		public function stockliststatus($id){
			$sql="SELECT Sum(tbl_orderdetails.Quantity) as totalsoldqty FROM `tbl_orderdetails` Inner Join tbl_orders ON tbl_orders.orderid=tbl_orderdetails.OrdID Where tbl_orders.status={$id}";
			$query=  $this->db->query($sql);
			$total_active_events = $query->num_rows();
			$allrows = $query->row();
			if( $total_active_events > 0 ) {
				if(empty($allrows->totalsoldqty)){
				return 	$allqty=0;
				}
				else{
				return 	$allqty=$allrows->totalsoldqty;
				}
			}
			return 0;
		}
		
		public function allstocklist(){
			$sql="SELECT tbl_products.*,tbl_size.itemsize,tbl_color.colorname,tbl_branch.Branchname,tbl_productstock.proid,tbl_productstock.psizeid,tbl_productstock.pcolorid,tbl_productstock.brid,tbl_productstock.purchase_rate,tbl_productstock.sell_rate,SUM(tbl_productstock.pquantity) as currentstock,Count(*) FROM tbl_productstock Left Join tbl_products ON tbl_products.productid=tbl_productstock.proid Left Join tbl_branch ON tbl_branch.branchid=tbl_productstock.brid Left Join tbl_size ON tbl_size.itemsizeid=tbl_productstock.psizeid Left Join tbl_color ON tbl_color.colorid=tbl_productstock.pcolorid Group BY tbl_productstock.proid,tbl_productstock.psizeid,tbl_productstock.pcolorid";
			$query=  $this->db->query($sql);
			$result=$query->result();
        	return $result;
		}

		public function read_allincommingorder(){
			$sql="select * from tbl_orders where status='0' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
		public function read_allpendingorder(){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where status='4' OR status='1' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
	 	public function read_allpendingorderbyid($setting_id){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where (status='4' OR status='1') AND ShownBy='".$setting_id."' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
		public function read_alldeliveredorder(){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where status='2' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}	
		public function read_allcancelorder(){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where status='3' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}		
		public function read_allpartialorder(){
			$curdate=date('Y-m-d');
			$sql="select * from tbl_orders where status!='2' AND ispartial='1' Group BY orderid order by orderid Desc";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
		public function readgroupproduct($id)
			{
			 $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_productstock.proid='".$id."' Group By tbl_productstock.proid order by tbl_products.productname Asc";
			  $query_result=  $this->db->query($sql);
			  $result=$query_result->row();
			  return $result;
			}	
}
