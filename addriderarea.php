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
                              Rider Area
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
            <form action="<?php echo base_url();?>Add-Rider-Area" method="POST" name="frm_validate">
            	<div class="uk-width-medium-1-1 uk-row-first">
                                  <div class="md-input-wrapper">
                                    <label>Area Name</label>
                                    <input class="md-input " name="Areaname" id="Areaname" value="" type="text" required="required">
                                    <span class="md-input-bar "></span></div>
                                </div>
                               
                               <div class="uk-grid">
                                    <div class="uk-width-medium-1-6">
                                        <input class="md-btn md-btn-primary" value="Submit" name="submit" type="submit">
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
	
	
	
