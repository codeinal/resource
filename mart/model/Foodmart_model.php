<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Foodmart_Model
 *
 * @author linktech
 */
class Foodmart_Model extends CI_Model{
    //put your code here
    public function updatedelfree()
    {
        
    }
	public function Getsessionid($id){
		$sql="select * from tbluser where UserID='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
		}
	 public function pageseo($pageslug){
		$sql="select * from tblseo where Seoslug='".$pageslug."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
		}
	 public function Allocation(){
		$this->db->select('Name,ServicelocationID');
        $this->db->from('tblservicelocation');
        $this->db->where('ServicelocationIsActive',1);
        $this->db->order_by('Name','ASC');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
		}
	/*public function Allarea(){
		if($this->input->cookie('LocationCK')!=''){
		$SetLocation = "servicelocation='".$this->input->cookie('LocationCK')."'";
		  }else{
			$SetLocation = 'servicelocation IS NOT NUll';
		  }
		$this->db->select('ResAarea');
        $this->db->from('tbluser');
        $this->db->where($SetLocation);
		$this->db->where('UsersCategory',3);
		$this->db->where('UserIsActive',1);
        $this->db->order_by('RestaurantName','ASC');
		$this->db->group_by('ResAarea');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
		}*/
	public function Allarea($getloc=''){
		if($getloc!=''){
		$SetLocation = "locationname='".$getloc."'";
		  }else{
			$SetLocation = 'locationname IS NOT NUll';
		  }
		$this->db->select('Name');
        $this->db->from('tblservicearea');
		$this->db->where($SetLocation);
        $this->db->where('ServiceareaIsActive',1);
        $this->db->order_by('Name','ASC');
		$this->db->group_by('Name');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
		}
	public function Getcontact(){
		$this->db->select('*');
        $this->db->from('tblcontactinfo');
        $this->db->where('ContactinfoIsActive',1);
        $this->db->order_by('ContactinfoID','DESC');
		$this->db->limit(1,0);
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
		}
	public function checkinbox($id){
		$sql2="select InboxID from tblinbox where UserID='".$id."'";
		$query_result2=  $this->db->query($sql2);
        $result2=$query_result2->row();
		$numrows = $query_result2->num_rows();
		if($numrows>0){
			
			}
		else{
			$insertdata['InboxUUID']		=  GUID();
			$insertdata['Message']    		= "";
			$insertdata['UserID']   	   	= $id;
			$insertdata['IsRead']   	   	= 1;
			$insertdata['ParentID']         = 0;
			$insertdata['InboxIsActive']   	= 1;
			$insertdata['SendBy']   	    = $id;
			$insertdata['DateInserted']   	= date('Y-m-d H:i:s');
			$insertdata['DateUpdated']   	= date('Y-m-d H:i:s');
			$insertdata['DateLocked']   	= date('Y-m-d H:i:s');
			$this->db->insert('tblinbox',$insertdata);
			}
		return $numrows;
		}
	 public function Chatbox($id){
	$sql="select * from tblinbox where InboxIsActive=1 AND UserID='".$id."' Order BY InboxID ASC";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
		}
	 public function senderinfo($id){
		$sql="select * from tblinbox where UserID='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
		}
	public function replayinfo($inboxid){
		$id=$this->session->userdata('UserID');
		$this->db->select('*');
        $this->db->from('tblinbox');
        $this->db->where('InboxIsActive',1);
        $this->db->where('UserID',$id);
		$this->db->where('InboxID!=',$inboxid);
		$this->db->order_by('InboxID','ASC');
        $query_result=  $this->db->get();
        $result=$query_result->result();
        return $result;
		}
	 public function senderf($id){
		$sql="select * from tbluser where UserID='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
		}
	public function loginUser($username, $password)
        {
            $val=0;
			$this->db->select('*');
            $this->db->where("(UserEmail = '$username' OR PhoneNumber = '$username' OR PhoneMobile = '$username')");
			$this->db->where('UserPassword', $password);
			$this->db->where('UserIsActive', 1);
			$query = $this->db->get('tbluser');
			$rows=$query->result(); 
			//print_r($rows);
			foreach ($rows as $row){
				$val=$row->UserID;
				$usercategory=$row->UsersCategory;
				$username=$row->UserName;
				$useremail=$row->UserEmail;
			}
			if($val > 0){ 
			//echo $vlcheck;
			$sessiondata = array(
				'UserID' =>$val,
				'UsersCategory' =>$usercategory,
				'UserName' =>$username,
				'UserEmail' =>$useremail
			);
			//print_r($sessiondata);
			$this->session->set_userdata($sessiondata);
		 }
             return $val;
        }
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
	public function update_infomulti($table, $data, $field_name, $field_value)
    {
        $where= "OrderID=".$field_value." AND (ShownBy IS NULL OR ShownBy='')";
		$this->db->where($where);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }
	public function check_user($data)
    {
        $UserEmail=$data['UserEmail'];
		$PhoneNumber=$data['PhoneMobile'];
		$PhoneMobile=$data['PhoneNumber'];
		$this->db->select('UserEmail,PhoneNumber,PhoneMobile');
        $this->db->from('tbluser');
		$this->db->where("UserEmail = '$UserEmail' OR PhoneNumber = '$PhoneNumber' OR PhoneMobile = '$PhoneMobile'");
 		return $this->db->get()->row();
    }
	public function check_user2($data)
    {
        $UserEmail=$data['UserEmail'];
		$PhoneNumber=$data['PhoneMobile'];
		$PhoneMobile=$data['PhoneNumber'];
		$reslug=$data['marchantid'];
		$this->db->select('UserEmail,PhoneNumber,PhoneMobile');
        $this->db->from('tbluser');
		$this->db->where("UserEmail = '$UserEmail' OR PhoneNumber = '$PhoneNumber' OR PhoneMobile = '$PhoneMobile' OR marchantid='$reslug'");
 		return $this->db->get()->row();
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
	public function read_all($select_items, $table, $where_array, $order_by_name = NULL, $order_by = NULL)
    {
        $this->db->select($select_items);
        $this->db->from($table);
        foreach ($where_array as $field => $value) {
            $this->db->where($field, $value);
        }

        if ($order_by_name != NULL && $order_by != NULL)
        {
            $this->db->order_by($order_by_name, $order_by);
        }
        return $this->db->get()->result();
    }
    public function totalcrmorder($select_items, $table, $where_array, $order_by_name = NULL, $order_by = NULL){
		$this->db->from( $table );
		foreach ($where_array as $field => $value) {
            $this->db->where($field, $value);
        }
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		}
	public function read_allgroup($select_items, $table, $where_array, $order_by_name = NULL, $order_by = NULL, $group_by_name = NULL)
    {
        $this->db->select($select_items);
        $this->db->from($table);
        foreach ($where_array as $field => $value) {
            $this->db->where($field, $value);
        }

        if ($order_by_name != NULL && $order_by != NULL)
        {
            $this->db->order_by($order_by_name, $order_by);
        }
		if ($group_by_name != NULL )
        {
            $this->db->group_by($group_by_name);
        }
        return $this->db->get()->result();
    }
	public function read_allcart($select_items, $table, $where_array, $order_by_name = NULL, $order_by = NULL)
    {
        $productid=$where_array['ProductID'];
		$restid=$where_array['Company'];
		$orderid=$where_array['OrderID'];
		$type=$where_array['Type'];
		if(($type==0) || ($type=='')){
		$mytype="(Type IS NULL OR Type=0)";
		}
		else{
			$mytype=$type;
			}
		$this->db->select($select_items);
        $this->db->from($table);
		$this->db->where('ProductID', $productid);
		$this->db->where('Company', $restid);
		$this->db->where('OrderID', $orderid);
		$this->db->where($mytype);
       

        if ($order_by_name != NULL && $order_by != NULL)
        {
            $this->db->order_by($order_by_name, $order_by);
        }
        return $this->db->get()->result();
    }
	public function get_search_result($data)
    {
        $this->db->select('tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.sponsor,tbluser.marchantid,tbluser.MinOrder, tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved');
		//$this->db->from('tbllocation');
		$this->db->join('tblproducts', 'tbllocation.RestaurantID = tblproducts.UserID');
		$this->db->join('tbluser', 'tbllocation.RestaurantID = tbluser.UserID');
		$this->db->where("tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%'");
		$this->db->order_by('tbluser.sponsor DESC,tbluser.Commission DESC');
		$this->db->group_by('tbllocation.RestaurantID');
		//$this->db->limit(10);
		$query = $this->db->get('tbllocation');
		return $rows=$query->result();
		 
		/*$Where="WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName, tbluser.sponsor,tbluser.marchantid, tbluser.MinOrder, tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved FROM tbllocation LEFT JOIN tblproducts ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser ON tbllocation.RestaurantID=tbluser.UserID ".$Where." GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
        return $this->db->query($sql)->result();*/
    }
	
	public function get_popular_result($data)
    {
        $Where="WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbluser.UserIsPopular=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.UserPicture,tbluser.sponsor,tbluser.marchantid, tbluser.MinOrder,tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved FROM tbllocation 
LEFT JOIN tblproducts ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser ON tbllocation.RestaurantID=tbluser.UserID ".$Where." GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
        return $this->db->query($sql)->result();
    }
	
	public function get_deals_result($data)
    {

        $Where="WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbluser.UserIsDeal=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.UserPicture,tbluser.sponsor,tbluser.marchantid,
tbluser.MinOrder,tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved FROM tbllocation 			LEFT JOIN tblproducts ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser ON tbllocation.RestaurantID=tbluser.UserID ".$Where." GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
        return $this->db->query($sql)->result();
    }
	public function get_filter_result($data)
    {
$term = $data["filterterm"];
	if($term=='UserIsPopular'){
		 $Where="WHERE (tbluser.Speciality LIKE '%Bangla%' OR tbluser.Speciality LIKE '%Bengali%') AND tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
	}
	if($term=='Deals'){
		  $Where="WHERE tbluser.Speciality LIKE '%Home%' AND tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
		}
	if($term=='fastfood'){
		  $Where="WHERE tbluser.Speciality LIKE '%Fast Food%' AND tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%'";
		}
       
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.UserPicture,tbluser.sponsor,tbluser.marchantid,
tbluser.MinOrder,tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved FROM tbllocation
LEFT JOIN tblproducts ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser ON tbllocation.RestaurantID=tbluser.UserID ".$Where." GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
        return $this->db->query($sql)->result();
    }
	
	public function get_maps_result()
    {
	$AreaCK= $this->input->cookie('AreaCK');
	if($AreaCK!=""){
			$condition="tbluser.UsersCategory=3 AND tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%".$AreaCK."%'";
			}
		else{
			$condition="tbluser.UsersCategory=3 AND tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1";
			}
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbllocation.Latitude,tbllocation.Longitude,tbllocation.RestaurantName,tbluser.UserID,tbluser.UserIsDeal,tbluser.UserPicture
            FROM tbllocation LEFT JOIN tblproducts
            ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser
            ON tbllocation.RestaurantID=tbluser.UserID
            WHERE {$condition} GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
        return $this->db->query($sql)->result();
    }
	public function get_food_result($data)
    {
	$Where="WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 AND tbllocation.Address LIKE '%{$data["area"]}%' AND tbllocation.Address LIKE '%{$data["location"]}%' AND ((tbluser.RestaurantName LIKE '%{$data["filterterm"]}%') OR (tblproducts.ProductName LIKE '%{$data["filterterm"]}%')) and tblproducts.ProductsIsActive<>0";
	
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.UserPicture,tbluser.sponsor,tbluser.marchantid,tbluser.MinOrder,tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved
			FROM tbllocation
			LEFT JOIN tblproducts
			ON tbllocation.RestaurantID=tblproducts.UserID
			LEFT JOIN tbluser
			ON tbllocation.RestaurantID=tbluser.UserID
				".$Where."
			GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC 
		";
        return $this->db->query($sql)->result();
    }
	
	public function get_sorting_result($data)
    {	
		$Where="";
		$OrderBy="ORDER BY tbluser.Position ASC";
		$firstsortby = $data["FirstShortBy"];
		$FreeDelivery = $data["FreeDelivery"];
		$PreOrder = $data["PreOrder"];
		$Reservation = $data["Reservation"];
		$Deals = $data["Deals"];
		$Online = $data["online"];
		$PriyoCard = $data["PriyoCard"];
		$foodname = $data["foodname"];
        
		$Where.="
				WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1
				AND tbllocation.Address LIKE '%{$data["area"]}%' 	
			";
		if(!empty($foodname)){
			$item_qry_str="";
			$item_arry = $foodname;
			for($i=0; $i<sizeof($item_arry); $i++){
				 $items= $item_arry[$i];
				  $item_qry_str .= " tbluser.Speciality Like '%$items%' AND";
			}
			$item_qry_str2=trim($item_qry_str,'AND');
			$Where.= "AND (".$item_qry_str2.")";
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}
		if(!empty($PreOrder)){
			$Where.=" AND tbluser.AllowPreOrder=1";	
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}	
		if(!empty($FreeDelivery)){
			$Where.=" AND tbluser.DeliveryFee=0";	
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}
		if(!empty($PriyoCard)){
			$Where.=" AND tbluser.AcceptPriyoCard=1";	
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}
		if(!empty($Reservation)){
			$Where.=" AND tbluser.AllowReservation=1";	
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}	
		if(!empty($Deals)){
			$Where.=" AND tbluser.UserIsDeal=1";
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}
		if(!empty($Online)){
			$Where.=" AND tbluser.deliveritype Like '%2%'";
			$OrderBy="ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";
			}			
		if(!empty($firstsortby)){
			if($firstsortby=="Alphabetic"){
				$OrderBy="ORDER BY tbluser.RestaurantName ASC,tbluser.sponsor DESC,tbluser.Commission DESC";	
			}else if($firstsortby=="MinimumOrder"){
				$OrderBy="ORDER BY tbluser.MinOrder ASC,tbluser.sponsor DESC,tbluser.Commission DESC";	
			}
			}
	$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.RestaurantName,tbluser.marchantid,tbluser.sponsor,tbluser.MinOrder,tbluser.Discount,tbluser.ResAddress,tbluser.ResAarea,tbluser.deliveritype,tbluser.Position,tbluser.DiscountText,tbluser.UserIsApproved FROM tbllocation LEFT JOIN tblproducts ON tbllocation.RestaurantID=tblproducts.UserID LEFT JOIN tbluser ON tbllocation.RestaurantID=tbluser.UserID ".$Where." GROUP BY tbllocation.RestaurantID ".$OrderBy."";
        return $this->db->query($sql)->result();
    }
	
	function getPosts()
	{
		$this->db->select('postId, postText');
  		$this->db->from('tbl_post');
		$this->db->order_by('postId','DESC');
		$data = $this->db->get();
		return $data->result();
	}
	
	function addPost($postData)
	{ 
	 	$this->db->insert("tbl_post", $postData);
		$insertId = $this->db->insert_id();
		return $insertId;
	}
	
	function getPostById($postId)
	{
		$this->db->select('postId, postText');
  		$this->db->from('tbl_post');
		$this->db->where('postId', $postId);
		$data = $this->db->get();
		return $data->result();
	}
	public function get_alloffers()
    {
		$startdate=date('Y-m-d');
		
		 //AND DATE(offerexpiredate)>='{$startdate}'
		$sql="SELECT * FROM tbluser WHERE UserIsApproved=1 AND UserIsActive=1 AND UserIsdealarea=1 AND DATE(offerexpiredate)>='{$startdate}' GROUP BY UserID ORDER BY sponsor DESC,Commission DESC";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
    }

	public function get_alloffersbyarea($myarea)
    {
		$startdate=date('Y-m-d');
		$lat=$this->input->post('latitude');
		$lng=$this->input->post('longitude');
		if(!empty($lat)){
		$distance = 2;
		// earth's radius in km = ~6371
		$radius = 6371;
		
		// latitude boundaries
		$maxlat = $lat + rad2deg($distance / $radius);
		$minlat = $lat - rad2deg($distance / $radius);
		
		// longitude boundaries (longitude gets smaller when latitude increases)
		$maxlng = $lng + rad2deg($distance / $radius / cos(deg2rad($lat)));
		$minlng = $lng - rad2deg($distance / $radius / cos(deg2rad($lat)));
		$condition="AND tbllocation.Latitude Between '".$minlat."' AND '".$maxlat."' AND tbllocation.Longitude Between '".$minlng."' AND '".$maxlng."'";
		}
		else{
			$condition="";
			}
		//$exlududeuniversity="Dhaka University";
		 //AND DATE(tbluser.offerexpiredate)>='{$startdate}'
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.UserID,tbluser.marchantid,tbluser.RestaurantName,tbluser.offertitle,tbluser.offerexpiredate,tbluser.ResAarea,tbluser.Offerindealareaimg FROM `tbluser` Left Join tbllocation ON tbllocation.RestaurantID=tbluser.UserID WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 {$condition} AND DATE(tbluser.offerexpiredate)>='{$startdate}' GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";	

		//$sql="SELECT * FROM tbluser WHERE UserIsApproved=1 AND UserIsActive=1 AND UserIsdealarea=1 GROUP BY UserID ORDER BY sponsor DESC,Commission DESC";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
    }
	
	public function get_testbyarea($myarea)
    {
		$startdate=date('Y-m-d');
		echo $lat=$this->input->post('latitude');
		echo "<br/>";
		echo $lng=$this->input->post('longitude');
		echo "<br/>";
		if(!empty($lat)){
		$distance = 1.5;
		// earth's radius in km = ~6371
		$radius = 6371;
		
		// latitude boundaries
		echo $maxlat = $lat + rad2deg($distance / $radius);
		echo "<br/>";
		echo $minlat = $lat - rad2deg($distance / $radius);
		echo "<br/>";
		// longitude boundaries (longitude gets smaller when latitude increases)
		echo $maxlng = $lng + rad2deg($distance / $radius / cos(deg2rad($lat)));
		echo "<br/>";
		echo $minlng = $lng - rad2deg($distance / $radius / cos(deg2rad($lat)));
		echo "<br/>";
		$condition="AND tbllocation.Latitude Between '".$minlat."' AND '".$maxlat."' AND tbllocation.Longitude Between '".$minlng."' AND '".$maxlng."'";
		}
		else{
			$condition="";
			}
		//$exlududeuniversity="Dhaka University";
		 //AND DATE(tbluser.offerexpiredate)>='{$startdate}'
		$sql="SELECT tbllocation.RestaurantID,tbllocation.Address,tbluser.UserID,tbluser.marchantid,tbluser.RestaurantName,tbluser.UserPicture,tbluser.offertitle,tbluser.offerexpiredate,tbluser.ResAarea,tbluser.Offerindealareaimg FROM `tbluser` Left Join tbllocation ON tbllocation.RestaurantID=tbluser.UserID WHERE tbluser.UserIsApproved=1 AND tbluser.UserIsActive=1 {$condition} GROUP BY tbllocation.RestaurantID ORDER BY tbluser.sponsor DESC,tbluser.Commission DESC";	

		//$sql="SELECT * FROM tbluser WHERE UserIsApproved=1 AND UserIsActive=1 AND UserIsdealarea=1 GROUP BY UserID ORDER BY sponsor DESC,Commission DESC";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
    }
	public function get_allcoupon()
    {
		$expiredate=date('Y-m-d');
		 //AND DATE(offerexpiredate)>='{$startdate}'
		$sql="SELECT tblcoupon.*,tbluser.RestaurantName,tbluser.marchantid,tbluser.UserPicture FROM tblcoupon Left Join tbluser ON tbluser.UserID=tblcoupon.reataurant WHERE tblcoupon.couponcodetype='Multi time' AND tblcoupon.reataurant>'0' AND tblcoupon.proendate>='{$expiredate}' GROUP BY tblcoupon.reataurant ORDER BY tblcoupon.CouponID";
        $query_result=  $this->db->query($sql);
        $result=$query_result->result();
        return $result;
    }
	public function fulltable($select_items, $table)
    {
        $this->db->select($select_items);
        $this->db->from($table);
        $this->db->group_by('orderid');
        return $this->db->get()->result();
    }
	
	//Start Branch Crm Admin
	public function crmLoginCheckInfo($username, $password)
    {
        $val=0;
		$con="(UsersCategory=15 OR UsersCategory=16)";
			$this->db->select('*');
            $this->db->where("(UserEmail = '$username' OR PhoneNumber = '$username' OR PhoneMobile = '$username')");
			$this->db->where('UserPassword', $password);
        	$this->db->where('UserIsActive',1);
			$this->db->where($con);
			$query = $this->db->get('tbluser');
			$rows=$query->result(); 
			//print_r($rows);
			foreach ($rows as $row){
				$val=$row->UserID;
				$usercategory=$row->UsersCategory;
				$username=$row->UserName;
				$useremail=$row->UserEmail;
				$branch=$row->servicelocation;
			}
			if($val > 0){ 
			//echo $vlcheck;
			$sessiondata = array(
				'CrmUserID' =>$val,
				'CrmUsersCategory' =>$usercategory,
				'CrmUserName' =>$username,
				'CrmUserEmail' =>$useremail,
				'CrmBranch' =>$branch
			);
			//print_r($sessiondata);
			$this->session->set_userdata($sessiondata);
		 }
             return $val;
    }
	
 	//Order Management
	public function read_allincommingorder(){
			$mylocation=$this->session->userdata('CrmBranch');
			$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where OrderStatus = 'Pending' AND OrderShown=0 AND t22.servicelocation='".$mylocation."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
	public function read_singleorder($id){
		$sql="select * from tblorder Where OrderID='".$id."'";
        $query_result=  $this->db->query($sql);
        $result=$query_result->row();
        return $result;
			}
	public function Ordercount($tablename,$id,$fieldname='',$fieldvalue='',$fieldname2='',$fieldvalue2='') {
        $this->db->from($tablename);
		if($fieldname!=""){
			$this->db->where($fieldname,$fieldvalue);
			}
		if($fieldname2!=""){
			$this->db->where($fieldname2,$fieldvalue2);
			}
		$this->db->order_by('OrderID','ASC');
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
	public function Ordercountrest($tablename,$uid,$restid) {
        $this->db->from($tablename);
		$this->db->where('UserID',$uid);
		$this->db->where('RestaurantID',$restid);
        $query = $this->db->get();
        $total_active_events = $query->num_rows();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return false;
		
    }
 public function deleteitem($tablename,$fieldname1,$item_id1)
    {
		$this->db->where($fieldname1, $item_id1);
   		$del=$this->db->delete($tablename); 
		return $del;
    }
 public function updatecarttable($pricewithaddons,$finalqty,$productid,$restid,$orderid,$type){
	 $data = array( 
			'Price'=> $pricewithaddons, 
			'Quantity'=> $finalqty 
		);
		if(($type==0) || ($type=='')){
		$mytype="(Type IS NULL OR Type=0)";
		}
		else{
			$mytype=$type;
			}
		$this->db->where('ProductID', $productid);
		$this->db->where('Company', $restid);
		$this->db->where('OrderID', $orderid);
		$this->db->where($mytype);
		$this->db->update('tblcart', $data);
	 }
 public function read_allpendingorder(){
	 $mylocation=$this->session->userdata('CrmBranch');
			$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where (OrderStatus = 'Processing' or OrderStatus = 'Pending') AND (ShownBy IS NOT NULL OR ShownBy!='') AND t22.servicelocation='".$mylocation."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
	public function read_allpendingorderbyid($myid){
			$mylocation=$this->session->userdata('CrmBranch');
			$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where (OrderStatus = 'Processing' or OrderStatus = 'Pending') AND ShownBy='".$myid."' AND t22.servicelocation='".$mylocation."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
		public function read_alldeliveredorder($myid){
			$mylocation=$this->session->userdata('CrmBranch');
			$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where OrderStatus = 'Delivered' AND ShownBy='".$myid."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}	
		public function read_allcancelorder($myid){
			$mylocation=$this->session->userdata('CrmBranch');
			$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where OrderStatus = 'Cancelled' AND ShownBy='".$myid."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->result();
        	return $result;
			}
		public function getridertotalorder($riderid){
			$mdate=date('Y-m-d');
			$sql="SELECT COUNT(tblorder.riderid) as totalord,tblriderlist.RiderName,tblriderlist.phone,tblriderlist.DateUpdated FROM tblriderlist Left Join tblorder ON tblorder.riderid=tblriderlist.RiderlistID WHERE tblriderlist.RiderlistID='".$riderid."' AND tblorder.OrderStatus='Processing' AND date(tblorder.DateInserted)='".$mdate."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
			}
		public function ridertotaldeli($riderid){
			$mdate=date('Y-m-d');
			$sql="SELECT COUNT(tblorder.riderid) as totalordeli,tblriderlist.phone FROM tblriderlist Left Join tblorder ON tblorder.riderid=tblriderlist.RiderlistID WHERE tblriderlist.RiderlistID='".$riderid."' AND tblorder.OrderStatus='Delivered' AND date(tblorder.DateInserted)='".$mdate."'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
			}
		public function ridertotalcredit($riderid){
			$mdate=date('Y-m-d');
			$sql="SELECT tblriderlist.RiderName,tblriderlist.phone,SUM(tblriderAccounts.amount) as totalcredit,tblriderAccounts.DateInserted as dates,tblriderAccounts.* FROM tblriderAccounts Left Join tblriderlist ON tblriderlist.RiderlistID=tblriderAccounts.RiderID WHERE tblriderAccounts.RiderID ='".$riderid."' AND tblriderAccounts.actype='Credit' AND tblriderAccounts.isapproved=1";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
			}
		public function ridertotaldebit($riderid){
			$mdate=date('Y-m-d');
			$sql="SELECT tblriderlist.RiderName,tblriderlist.phone,SUM(tblriderAccounts.amount) as totalcredit,tblriderAccounts.DateInserted as dates,tblriderAccounts.* FROM tblriderAccounts Left Join tblriderlist ON tblriderlist.RiderlistID=tblriderAccounts.RiderID WHERE tblriderAccounts.RiderID ='".$riderid."' AND tblriderAccounts.actype='Debit' AND tblriderAccounts.isapproved=1";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
			}
	public function read_phone($riderid){
			$cdate=date('Y-m-d');
			$sql="SELECT COUNT(tblorder.riderid) as totalord,tblriderlist.phone FROM tblriderlist Left Join tblorder ON tblorder.riderid=tblriderlist.RiderlistID WHERE tblriderlist.RiderlistID={$riderid} AND tblorder.OrderStatus='Processing' AND date(tblorder.DateInserted)='{$cdate}'";
        	$query_result=  $this->db->query($sql);
        	$result=$query_result->row();
        	return $result;
		}
	public function placeincomming_order(){
		$mylocation=$this->session->userdata('CrmBranch');
		$sql="SELECT tblorder.*,t21.UserName,t22.ResAarea,t22.RestaurantName,tblriderlist.RiderName FROM tblorder left join tbluser t21 on tblorder.ShownBy = t21.UserID inner join tbluser t22 on tblorder.RestaurantID = t22.UserID Left Join tblriderlist ON tblriderlist.RiderlistID=tblorder.riderid Where OrderStatus = 'Pending' AND OrderShown=0 AND t22.servicelocation='".$mylocation."'";
        $query=  $this->db->query($sql);
        $total_active_events = $query->num_rows();
		$allrows = $query->result();
        if( $total_active_events > 0 ) {
            return $total_active_events;
        }
        return 0;
		}
		public function resize($filename, $width, $height) {

		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != DIR_IMAGE) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'tempf/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
 
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}


			return site_url(). $image_new;
		
	}
}