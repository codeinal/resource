<?php	$adminhtml='';
	$emid = $eid;
	$firstdate = date('Y-m-01');
    $lastdate = date('Y-m-d');
	$datediff = strtotime($lastdate) - strtotime($firstdate);
$datediff = floor($datediff/(60*60*24));

for($i = 0; $i < $datediff + 1; $i++){
$alldays= date("Y-m-d", strtotime($firstdate . ' + ' . $i . 'day'));

$sql="SELECT tbluser.UserName,tbluser.PhoneMobile,tblemployeelog .* FROM tblemployeelog Left Join tbluser ON tbluser.UserID=tblemployeelog.UserID WHERE tblemployeelog.UserID={$emid} AND tblemployeelog.logintime!='' AND DAY(tblemployeelog.DateInserted)=DAY('{$alldays}') AND MONTH(tblemployeelog.DateInserted)=MONTH('{$alldays}') AND YEAR(tblemployeelog.DateInserted)=YEAR('{$alldays}') Order By tblemployeelog.EmployeelogID ASC LIMIT 1";
 $query_result=  $this->db->query($sql);
 $getrows=$query_result->row();

$sql2="SELECT tbluser.UserName,tbluser.PhoneMobile,tblemployeelog .* FROM tblemployeelog Left Join tbluser ON tbluser.UserID=tblemployeelog.UserID WHERE tblemployeelog.UserID={$emid} AND DAY(tblemployeelog.DateInserted)=DAY('{$alldays}') AND MONTH(tblemployeelog.DateInserted)=MONTH('{$alldays}') AND YEAR(tblemployeelog.DateInserted)=YEAR('{$alldays}') Order By tblemployeelog.EmployeelogID DESC LIMIT 1";
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
		<tr>';
}?>
 <div id="page_content_inner">
        	<div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
            	<div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h3 class="heading_a">Details Attendance</h3>
                            <div class="uk-grid">
                                <div class="uk-width-large-1-3">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="uk_dp_1">Select date</label>
                                         <input type="hidden" id="userid" name="userid" value="<?php echo $emid;?>"/>
                                        <input class="md-input" type="text" id="uk_dp_1" data-uk-datepicker="{format:'DD.MM.YYYY'}">
                                    </div>
                                </div>
                                <div class="uk-width-large-1-3 uk-width-1-1">
                                        <input class="md-btn md-btn-primary" value="Submit" name="branch" type="button" onclick="Submitmonth()">
                                    </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
             <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content" id="changemonth">
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
                </div>
            </div>
        </div>
        </div>
		<script type="text/javascript">
		function Submitmonth(){
			var getmonth = $("#uk_dp_1").val();
			var userid= $("#userid").val();
			if(getmonth==""){
				alert("Please select Any Month!!!");
				return false;
				}
			var data = "userid="+userid+"&getmonth="+getmonth;
			$.ajax({
			  type: "POST",
			  url: mybaseUrl+"crmlogin/searchattendness",
			  data: data,
			  success: function(data){ 
				 $("#changemonth").html(data);
			  }
			 });
	}
</script>
