 <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
                            
                             <ul class="uk-tab">
                                <li style="margin-right:15px;"><a href="<?php echo base_url();?>listrider" class="md-btn md-btn-wave waves-effect waves-button">View Rider</a></li>
                            </ul>
                            
                </div>
                <div class="md-card">
         		   <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                	<div class="uk-width-medium-2-10">&nbsp;</div>
                    <div class="uk-width-medium-6-10">
                    <div class="md-card">
                    <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                              Add Rider Information
                            </h3>
                        </div>
                        <div class="md-card-content">
                        <?php 
        $message=$this->session->userdata('message');
		$error=$this->session->userdata('error');
        if($message)
        {?>
                <div class="uk-alert uk-alert-success" data-uk-alert="">
                               <?php echo $message;
            $this->session->unset_userdata('message');
            ?>
                            </div>
               <?php }
        if($error)
        {?>
                <div class="uk-alert uk-alert-danger" data-uk-alert="">
                               <?php echo $error;
            $this->session->unset_userdata('error');
            ?>
                            </div>
               <?php }
        ?>
            <form action="<?php echo base_url();?>crmlogin/saverider" method="POST" name="frm_validate">
            				<div class="uk-width-medium-1-1  uk-row-first">
                                    <select required="required" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="riderarea" title="Select Area">
                                        <option value="">Select Area</option>
                                        <?php foreach($allareas as $area){?>
                                		<option value="<?php echo $area->RiderareaID;?>"><?php echo $area->RiderareaName;?></option>
                                        <?php } ?>
                                    </select></div>
            				<div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Rider Name</label>
                                    <input class="md-input" name="ridername" id="ridername" value="" type="text" required="required">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Rider Phone</label>
                                    <input class="md-input" name="Phone" id="Phone" value="" type="text" required="required">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Password</label>
                                    <input class="md-input" name="password" id="password" value="" type="password" required="required">
                                    <span class="md-input-bar "></span></div>
                                </div> 
                             <div class="uk-width-medium-1-1  uk-row-first">
                                    <select  data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" name="ridertype" title="Select Type" required="required">
                                        <option value="">Select Type</option>
                                		<option value="Flexible">Flexible</option>
                                        <option value="Permanent">Permanent</option>
                                    </select></div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Rider Salary</label>
                                    <input class="md-input" name="salary" id="salary" value="" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Rider NID</label>
                                    <input class="md-input" name="nid" id="nid" value="" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Paddy cash</label>
                                    <input class="md-input" name="paddycash" id="paddycash" value="0" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Warning Minimum Amount</label>
                                    <input class="md-input" name="minimumamount" id="minimumamount" value="0" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div> 
                            <div class="uk-width-medium-1-1">
                                  <div class="md-input-wrapper">
                                    <label>Warning Maximum Amount</label>
                                    <input class="md-input" name="maximumamount" id="maximumamount" value="0" type="text">
                                    <span class="md-input-bar "></span></div>
                                </div>   
                            <div class="uk-width-medium-1-1">
                              	<span class="uk-form-help-block">Own/Comapny Vehicle?</span>
                              		<span class="icheck-inline">
                                        <input type="radio" name="ownorcompanyvichletype" id="radio_demo_inline_1" value="1" data-md-icheck />
                                        <label for="radio_demo_inline_1" class="inline-label">Company</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="ownorcompanyvichletype" id="radio_demo_inline_2" value="0" checked="checked" data-md-icheck />
                                        <label for="radio_demo_inline_2" class="inline-label">Own </label>
                                    </span>
                             </div>
                            <div class="uk-width-medium-1-1">
                              	<span class="uk-form-help-block">Vehicle type</span>
                              		<span class="icheck-inline">
                                        <input type="radio" checked="checked" name="vichletype" id="radio_demo_inline_3" value="1" data-md-icheck />
                                        <label for="radio_demo_inline_3" class="inline-label">Cycle</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="vichletype" id="radio_demo_inline_4" value="0" data-md-icheck />
                                        <label for="radio_demo_inline_4" class="inline-label">Motor cycle </label>
                                    </span>
                                   
                             </div>
                            <div class="uk-width-medium-1-1">
                              	<span class="uk-form-help-block">Active?</span>
                              		<span class="icheck-inline">
                                        <input type="radio" name="RiderlistIsActive" id="radio_demo_inline_5" value="1" checked="checked" data-md-icheck />
                                        <label for="radio_demo_inline_5" class="inline-label">Yes</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="RiderlistIsActive" id="radio_demo_inline_6" value="0"  data-md-icheck />
                                        <label for="radio_demo_inline_6" class="inline-label">No </label>
                                    </span>
                             </div>    
                               
                                
                               
                               <div class="uk-grid">
                                    <div class="uk-width-medium-1-6">
                                        <input class="md-btn md-btn-primary" value="Submit" name="Add Rider" type="submit">
                                    </div>
                              </div>
                               </form>
            </div>
                </div>
                    <div class="uk-width-medium-2-10">&nbsp;</div>
                </div>
            </div>
        </div>
   				</div>
            
        </div>	
</div>
	
	
	
