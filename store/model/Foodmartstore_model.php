<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Welcome_model
 *
 * @author linktech
 */
class Foodmartstore_model extends CI_Model{
    //put your code here
	
	public function insert_data($table, $data)
		{
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}
		public function update_info($table, $data, $field_name, $field_value)
    {
        $this->db->where($field_name, $field_value);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
		 public function update_date($table, $data, $field_name, $field_value)
			{
				$this->db->where($field_name, $field_value);
				$this->db->update($table, $data);
				return $this->db->affected_rows();
			}
		public function loginUser($username, $password)
        {
            $val=0;
			$this->db->select('*');
            $this->db->where('cus_email', $username);
			$this->db->where('cus_password', $password);
			$query = $this->db->get('tbl_customer');
			$rows=$query->result(); 
			//print_r($rows);
			foreach ($rows as $row){
				$val=$row->custid;
				$firstname=$row->cus_firstname;
				$lastname=$row->cus_lastname;
				$useremail=$row->cus_email;
			}
			if($val > 0){ 
			//echo $vlcheck;
			$sessiondata = array(
				'CusUserID' =>$val,
				'cusfname' =>$firstname,
				'cuslname' =>$lastname,
				'CustomerEmail' =>$useremail
			);
			//print_r($sessiondata);
			$this->session->set_userdata($sessiondata);
		 }
             return $val;
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
		 public function read2($select_items, $table, $orderby, $where_array)
			{
			   
				$this->db->select($select_items);
				$this->db->from($table);
				foreach ($where_array as $field => $value) {
					$this->db->where($field, $value);
					
				}
				$this->db->order_by($orderby,'DESC');
				return $this->db->get()->row();
			}
		public function read_all($select_items, $table, $orderby,$delitem="",$stype="",$val="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($delitem!=""){
			$this->db->where($delitem,0);
			}
			if($stype!=""){
			$this->db->where($stype,$val);
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
		
		public function record_count($id) {
        $this->db->from( 'tbl_productstock' );
		$this->db->where('catids',$id);
		$this->db->group_by('proid');
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
	public function record_count2($id,$max,$min,$filterdata) {
		$Where="";
		$category = $filterdata["category"];
		$color = $filterdata["color"];
		
		$Where.="WHERE tbl_productstock.sell_rate BETWEEN {$min} AND {$max} ";
		//$OrderBy="order by menuid Asc";
		if($category==""){
				$Where.= "AND (tbl_productstock.catids={$id})";
				$OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
				}
		if(!empty($category)){
			$item_qry_str="";
			$item_arry = $category;
			for($i=0; $i<sizeof($item_arry); $i++){
				 $items= $item_arry[$i];
				   $item_qry_str .= " tbl_productstock.catids={$items} OR";
			}
			$item_qry_str2=trim($item_qry_str,'OR');
			$Where.= "AND (tbl_productstock.catids={$id} AND".$item_qry_str2.")";
			$OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
			//$OrderBy="order by menuid Asc";
			}
		if(!empty($color)){
				$item_qry_str="";
				$item_arry = $color;
				for($i=0; $i<sizeof($item_arry); $i++){
					 $items= $item_arry[$i];
					   $item_qry_str .= " tbl_productstock.pcolorid={$items} OR";
				}
				$item_qry_str2=trim($item_qry_str,'OR');
				$Where.= "AND ".$item_qry_str2."";
				$OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
				}
		   $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid ".$Where." ".$OrderBy."";
				 $query_result=  $this->db->query($sql);
        $total_active_events = $query_result->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
    public function record_allrows($field1value,$field1,$table) {
        $this->db->from( $table );
        $this->db->where( $field1, $field1value );
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
	public function read_avarage($table,$field1,$field2,$field2value) {
		
        $this->db->select('AVG('.$field1.') as ratingavarage');
		$this->db->where( $field2, $field2value );
		$query = $this->db->get($table);
        $total_active_events = $query->num_rows();
		$allrows = $query->row();
        if( $total_active_events > 0 ) {
            return $allrows;
        }
        return false;
		
    }
	public function read_totalcolor($colorid,$catid) {
		$sql="select * from tbl_productstock where catids='".$catid."' AND pcolorid='".$colorid."' Group By proid";
        $query=  $this->db->query($sql);
        $total_active_events = $query->num_rows();
		$allrows = $query->result();
        if( $total_active_events > 0 ) {
            return $allrows;
        }
        return false;
		
    }
	public function relatedproductcount($pid,$catid) {
		$sql="select * from tbl_productstock where proid!='".$pid."' AND catids='".$catid."' Group By tbl_productstock.proid order by proid desc";
        $query_result=  $this->db->query($sql);
        $total_active_events = $query_result->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
		 public function read_all_catproduct($limit,$start,$catid,$minprice,$maxprice,$filterdata)
			{
				if($start==NULL)
				{
					$start=0;
				}
				 $Where="";
				$category = $filterdata["category"];
				$color = $filterdata["color"];
				
				$Where.="WHERE tbl_productstock.sell_rate BETWEEN {$minprice} AND {$maxprice} ";
				 $OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
				 if($category==""){
				$Where.= "AND (tbl_productstock.catids={$catid})";
				}
				 if(!empty($category)){
				$item_qry_str="";
				$item_arry = $category;
				for($i=0; $i<sizeof($item_arry); $i++){
					 $items= $item_arry[$i];
					   $item_qry_str .= " (tbl_productstock.catids={$items}) OR";
				}
				$item_qry_str2=trim($item_qry_str,'OR');
				$Where.= "AND ".$item_qry_str2."";
				$OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
				}
				
				if(!empty($color)){
				$item_qry_str="";
				$item_arry = $color;
				for($i=0; $i<sizeof($item_arry); $i++){
					 $items= $item_arry[$i];
					   $item_qry_str .= " tbl_productstock.pcolorid={$items} OR";
				}
				$item_qry_str2=trim($item_qry_str,'OR');
				$Where.= "AND ".$item_qry_str2."";
				$OrderBy="Group By tbl_productstock.proid order by tbl_productstock.proid Asc";
				}
				
				
				 $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid ".$Where." ".$OrderBy." limit $start,$limit";
				 $query_result=  $this->db->query($sql);
				 $result=$query_result->result();
				 if( $query_result->num_rows() > 0 ) {

            foreach ( $query_result->result() as $row ) {

                $data[] = $row;

            }

            return $data;

        }

        return false;
				
				
			/*	$this->db->limit( $limit, $start );
        $this->db->where('delation_status = 0' );
		$this->db->where('find_in_set("'.$catid.'", catids) <> 0');
        $this->db->order_by( 'menuid', 'asc' );
        $query = $this->db->get( 'tbl_menuitem' );

        if( $query->num_rows() > 0 ) {

            foreach ( $query->result() as $row ) {

                $data[] = $row;

            }

            return $data;

        }

        return false;*/

			}
		public function readproductcolor($pid) {
		$sql="select tbl_productstock.pcolorid,tbl_color.* from tbl_color Left Join tbl_productstock ON tbl_productstock.pcolorid=tbl_color.colorid where tbl_productstock.proid='".$pid."' Group BY tbl_color.colorname Order By tbl_color.colorname";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		
    }	
	public function readproductsize($pid) {
		$sql="select tbl_productstock.psizeid,tbl_productstock.sell_rate,tbl_size.* from tbl_size Left Join tbl_productstock ON tbl_productstock.psizeid=tbl_size.itemsizeid where tbl_productstock.proid='".$pid."' Group BY tbl_size.itemsize Order By tbl_size.itemsize";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		
    }	
		
		 public function readmaxmin($catid){
			 	$sql="select MAX(sell_rate) as Maxp, MIN(sell_rate) as Minp from tbl_productstock where catids='".$catid."'";
				$query_result=  $this->db->query($sql);
				$result=$query_result->row();
				if( $query_result->num_rows() > 0 ) {
				return $result;
				}
				return false;
			 	/*$this->db->select('MAX(price) as max_fare, MIN(price) as min_fare');
				$this->db->from('tbl_menuitem');
				$query = $this->db->get();*/
				//SELECT * FROM `tbl_menuitem` WHERE CAST(`price` AS SIGNED) BETWEEN 0 AND 13 AND find_in_set(12,catids) <> 0 
				//SELECT *, MAX(price) as Maxp, MIN(price)as Minp FROM `tbl_menuitem` WHERE find_in_set(12,catids) <> 0 
			 }
		/*public function read_all_catproduct($select_items, $table, $orderby,$delitem="",$stype="",$val="")
		{
			$this->db->select($select_items);
			$this->db->from($table);
			if($delitem!=""){
			$this->db->where($delitem,0);
			}
			if($stype!=""){
			$this->db->where('find_in_set("'.$val.'", '.$stype.') <> 0');
			}
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}*/
		 public function readgroup($id)
			{
			  $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_productstock.proid='".$id."' Group By tbl_productstock.proid order by tbl_products.productname Asc";
			  $query_result=  $this->db->query($sql);
			  $result=$query_result->row();
			  return $result;
			}
		public function productdetails($id)
			{
			  $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_products.pruductslug='".$id."' Group By tbl_productstock.proid order by tbl_products.productname Asc";
			  $query_result=  $this->db->query($sql);
			  $result=$query_result->row();
			  return $result;
			}
		public function featured()
			{
			  $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_products.featureproduct=1 Group By tbl_productstock.proid order by tbl_products.productname Asc";
			  $query_result=  $this->db->query($sql);
			  $result=$query_result->result();
			  return $result;
			}
		public function newproduct()
			{
			  $sql="select tbl_products.*,tbl_productstock.* from tbl_productstock left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_products.newproduct=1 Group By tbl_productstock.proid order by tbl_products.productname Asc";
			  $query_result=  $this->db->query($sql);
			  $result=$query_result->result();
			  return $result;
			}
		 public function readitem($id)
			{
				$sql="select tbl_menuitem.*,tbl_menucategory.mecatname from tbl_menuitem left join tbl_menucategory on tbl_menucategory.menucatid=tbl_menuitem.catids where  tbl_menuitem.menuid='".$id."' AND tbl_menuitem.delation_status=0 order by  tbl_menuitem.menuid Desc";
		  $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
			}
		public function read_allitems()
		{
			 $sql="select tbl_menuitem.*,tbl_menucategory.* from tbl_menuitem left join tbl_menucategory on tbl_menucategory.menucatid=tbl_menuitem.catids where tbl_menuitem.delation_status=0 order by tbl_menuitem.menuid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
		}
		public function readcat($id)
		{
		$sql="select * from tbl_menucategory where catslug='".$id."' AND delation_status=0 order by menucatid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
		}
		public function readbycat($id)
			{
				$sql="select tbl_menuitem.*,tbl_menucategory.* from tbl_menuitem left join tbl_menucategory on tbl_menucategory.menucatid=tbl_menuitem.catids where tbl_menucategory.catslug='".$id."' AND tbl_menuitem.delation_status=0 order by  tbl_menuitem.menuid Desc";
		 $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
			}
		 public function readsingle($id)
			{
				$sql="select tbl_menuitem.*,tbl_menucategory.* from tbl_menuitem left join tbl_menucategory on tbl_menucategory.menucatid=tbl_menuitem.catids where tbl_menuitem.itemslug='".$id."' AND tbl_menuitem.delation_status=0 order by  tbl_menuitem.menuid Desc";
		  $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
			}
		public function readexceptid($catid,$id)
			{
				$sql="select tbl_menuitem.*,tbl_menucategory.* from tbl_menuitem left join tbl_menucategory on tbl_menucategory.menucatid=tbl_menuitem.catids where tbl_menuitem.menuid!='".$id."' AND tbl_menuitem.catids='".$catid."' AND tbl_menuitem.delation_status=0 order by  tbl_menuitem.menuid Desc";
		 $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
			}
		
 public function allinstavidinfo($per_page,$offset)
    {
        if($offset==NULL)
        {
            $offset=0;
        }
        $sql="select * from tbl_instavid where delation_status=0 order by instaid desc limit $offset,$per_page";
         $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
    }
public function allorderlist(){
			    
				$sql="select tbl_orders.*,tbl_orderdetails.*,tbl_products.productname from tbl_orders left join tbl_orderdetails on tbl_orderdetails.OrdID=tbl_orders.orderid Inner join tbl_products ON tbl_products.productid=tbl_orderdetails.ProductID  Group BY tbl_orders.orderid order by tbl_orders.orderid Desc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
public function singleorder($id){
			    
		$sql="select tbl_orders.*,tbl_orderdetails.*,tbl_products.productname from tbl_orders left join tbl_orderdetails on tbl_orderdetails.OrdID=tbl_orders.orderid Inner join tbl_products ON tbl_products.productid=tbl_orderdetails.ProductID Where tbl_orderdetails.OrdID='".$id."' Group BY tbl_orderdetails.orderdetailsid order by tbl_orderdetails.orderdetailsid Asc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
		
        return $result;
			}
	
public function bestsalerproduct(){
		$sql="select ProductID,count(ProductID) as cnt from tbl_orderdetails Group BY ProductID  order by cnt desc Limit 5";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result_array();
        return $result;
	}
public function relatedproduct($pid,$catid){
		$sql="select tbl_products.*,tbl_productstock.* from tbl_productstock Left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_productstock.proid!='".$pid."' AND tbl_productstock.catids='".$catid."' Group BY tbl_productstock.proid order by tbl_products.productname Asc";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
	}
public function readsearch($id)
			{
		$sql="select tbl_products.*,tbl_menucategory.mecatname,tbl_productstock.* from tbl_productstock left join tbl_menucategory on tbl_menucategory.menucatid=tbl_productstock.catids Left Join tbl_products ON tbl_products.productid=tbl_productstock.proid where tbl_menucategory.mecatname LIKE '%".$id."%' OR tbl_products.productname LIKE '%".$id."%' Group BY tbl_productstock.proid order by tbl_products.productname Asc";
		$query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
			}		
}
