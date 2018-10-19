<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="<?php echo base_url();?>admin/assets/img/favicon.ico" sizes="16x16">
    <link rel="icon" type="image/png" href="<?php echo base_url();?>admin/assets/img/favicon.ico" sizes="32x32">

    <title><?php echo $title;?> | Foodmart Store</title>


    <!-- uikit -->
    <link rel="stylesheet" href="<?php echo base_url();?>admin/bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

    <!-- flag icons -->
    <link rel="stylesheet" href="<?php echo base_url();?>admin/assets/icons/flags/flags.min.css" media="all">

    <!-- style switcher -->
    <link rel="stylesheet" href="<?php echo base_url();?>admin/assets/css/style_switcher.min.css" media="all">
    
    <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo base_url();?>admin/assets/css/main.min.css" media="all">

    <!-- themes -->
    <link rel="stylesheet" href="<?php echo base_url();?>admin/assets/css/themes/themes_combined.min.css" media="all">

    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
        <link rel="stylesheet" href="assets/css/ie.css" media="all">
    <![endif]-->
<script>
var baseUrl="<?php echo base_url();?>admin";
var mybaseUrl="<?php echo base_url();?>";
</script>
</head>
<body class=" sidebar_main_open sidebar_main_swipe">
 <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">
                                
                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>
                
                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                    
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="<?php echo base_url();?>Incomming-order" class="user_action_icon" title="Incomming Order"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge" id="totord"><?php echo $orderplace=  $this->Foodmart_model->placeincomming_order();?></span></a>
                        </li>
                    
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE554;</i><span class="uk-badge" id="reliver"><?php //echo $orderplace=  $this->Super_admin_model->reliever_notice();?>0</span></a>
                        </li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_image"><img class="md-user-image" src="<?php echo base_url();?>admin/assets/img/avatars/avatar_11_tn.png" alt=""/></a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                 	<li><a href="#">My profile</a></li>
                                    <li><a href="<?php echo base_url();?>Logoff">Logout</a></li>
                                
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header><!-- main header end -->
    <!-- main sidebar -->
    <aside id="sidebar_main">
        
        <div class="sidebar_main_header">
            <div class="sidebar_logo">
                <a href="<?php echo base_url();?>dashboard" class="sSidebar_hide sidebar_logo_large">
                    <img class="logo_regular" src="<?php echo base_url();?>admin/assets/img/logo.png" alt="" style="height:60px;"/>
                    <img class="logo_light" src="<?php echo base_url();?>admin/assets/img/logo.png" alt="" style="height:60px;"//>
                </a>
                <a href="<?php echo base_url();?>dashboard" class="sSidebar_show sidebar_logo_small">
                    <img class="logo_regular" src="<?php echo base_url();?>admin/assets/img/logo.png" alt="" style="height:60px;"//>
                    <img class="logo_light" src="<?php echo base_url();?>admin/assets/img/logo.png" alt="" style="height:60px;"//>
                </a>
            </div>
        </div>
        
        <div class="menu_section">
            <ul>
                <li class="current_section" title="Dashboard">
                    <a href="<?php echo base_url();?>dashboard">
                        <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>
                        <span class="menu_title">Dashboard</span>
                    </a>
                    
                </li>
                <li title="CRM">
                    <a href="<?php echo base_url();?>Incomming-order">
                        <span class="menu_icon"><i class="material-icons">&#xE0B7;</i></span>
                        <span class="menu_title">Manage Order</span>
                    </a>
                </li>
                
                <li title="Employee Management">
                    <a href="<?php echo base_url();?>CrmOrderreport">
                        <span class="menu_icon"><i class="material-icons">timeline</i>	</span>
                        <span class="menu_title">CRM Report</span>
                    </a>
                    
                </li>
                <li title="Employee Management">
                    <a href="<?php echo base_url();?>Employeelog">
                        <span class="menu_icon"><i class="material-icons">&#xE8D3;</i></span>
                        <span class="menu_title">Employee Attendness</span>
                    </a>
                </li>
                 <li title="Employee Management">
                    <a href="<?php echo base_url();?>View-Leave">
                        <span class="menu_icon"><i class="material-icons">&#xE8D3;</i></span>
                        <span class="menu_title">Leave Management</span>
                    </a>
                    
                </li>
                <li title="Sms Management">
                    <a href="<?php echo base_url();?>Sendsms">
                        <span class="menu_icon"> <i class="material-icons">&#xE158;</i></span>
                        <span class="menu_title">Send SMS</span>
                    </a>
                    
                </li>
               <?php $crm_cat=$this->session->userdata('CrmUsersCategory');;
			   if($crm_cat==16){?>
               <li title="Rider Management">
                    <a href="<?php echo base_url();?>listrider">
                        <span class="menu_icon"><i class="material-icons">&#xE87C;</i></span>
                        <span class="menu_title">Rider Management</span>
                    </a>
                </li>
                <li title="SMS Management">
                    <a href="<?php echo base_url();?>OrderReport">
                        <span class="menu_icon"><i class="material-icons">&#xE0B7;</i></span>
                        <span class="menu_title">Order Report</span>
                    </a>
                </li>
                <?php } ?>
               
            </ul>
        </div>
    </aside><!-- main sidebar end -->
<div id="page_content">
		<?php echo $content;?>
</div>
 
    <!-- common functions -->
    <script src="<?php echo base_url();?>admin/assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="<?php echo base_url();?>admin/assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="<?php echo base_url();?>admin/assets/js/altair_admin_common.min.js"></script>
<?php 
if($title=="Dashboard"){?>
 <!-- page specific plugins -->
        <!-- d3 -->
        <script src="<?php echo base_url();?>admin/bower_components/d3/d3.min.js"></script>
        <!-- metrics graphics (charts) -->
        <script src="<?php echo base_url();?>admin/bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
        <!-- chartist (charts) -->
        <script src="<?php echo base_url();?>admin/bower_components/chartist/dist/chartist.min.js"></script>
        <!-- maplace (google maps) -->
        <script src="http://maps.google.com/maps/api/js"></script>
        <script src="<?php echo base_url();?>admin/bower_components/maplace-js/dist/maplace.min.js"></script>
        <!-- peity (small charts) -->
        <script src="<?php echo base_url();?>admin/bower_components/peity/jquery.peity.min.js"></script>
        <!-- easy-pie-chart (circular statistics) -->
        <script src="<?php echo base_url();?>admin/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <!-- countUp -->
        <script src="<?php echo base_url();?>admin/bower_components/countUp.js/dist/countUp.min.js"></script>
        <!-- handlebars.js -->
        <script src="<?php echo base_url();?>admin/bower_components/handlebars/handlebars.min.js"></script>
        <script src="<?php echo base_url();?>admin/assets/js/custom/handlebars_helpers.min.js"></script>
        <!-- CLNDR -->
        <script src="<?php echo base_url();?>admin/bower_components/clndr/clndr.min.js"></script>

        <!--  dashbord functions -->
        <script src="<?php echo base_url();?>admin/assets/js/pages/dashboard.min.js"></script>

<?php } 
else{
?>
  	<script src="<?php echo base_url();?>admin/bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>
    <!-- htmleditor (codeMirror) -->
    <script src="<?php echo base_url();?>admin/assets/js/uikit_htmleditor_custom.min.js"></script>
    <!-- inputmask-->
    <script src="<?php echo base_url();?>admin/bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>

    <!--  forms advanced functions -->
    <script src="<?php echo base_url();?>admin/assets/js/pages/forms_advanced.min.js"></script>
    <!-- tinymce -->
    <script src="<?php echo base_url();?>admin/bower_components/tinymce/tinymce.min.js"></script>

    <!--  wysiwyg editors functions -->
    <script src="<?php echo base_url();?>admin/assets/js/pages/forms_wysiwyg.min.js"></script>
  <!-- datatables -->
    <script src="<?php echo base_url();?>admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables buttons-->
    <script src="<?php echo base_url();?>admin/bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="<?php echo base_url();?>admin/assets/js/custom/datatables/buttons.uikit.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/datatables-buttons/js/buttons.colVis.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/datatables-buttons/js/buttons.html5.js"></script>
    <script src="<?php echo base_url();?>admin/bower_components/datatables-buttons/js/buttons.print.js"></script>
    
    <!-- datatables custom integration -->
    <script src="<?php echo base_url();?>admin/assets/js/custom/datatables/datatables.uikit.min.js"></script>

    <!--  datatables functions -->
    <script src="<?php echo base_url();?>admin/assets/js/pages/plugins_datatables.min.js"></script>
     
<?php } ?>
    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "<?php echo base_url();?>admin/bower_components/dense/src/dense.js", function() {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
        });
    </script>


    <div id="style_switcher">
        <div id="style_switcher_toggle"><i class="material-icons">&#xE8B8;</i></div>
        <div class="uk-margin-medium-bottom">
            <h4 class="heading_c uk-margin-bottom">Colors</h4>
            <ul class="switcher_app_themes" id="theme_switcher">
                <li class="app_style_default active_theme" data-app-theme="">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_a" data-app-theme="app_theme_a">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_b" data-app-theme="app_theme_b">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_c" data-app-theme="app_theme_c">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_d" data-app-theme="app_theme_d">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_e" data-app-theme="app_theme_e">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_f" data-app-theme="app_theme_f">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_g" data-app-theme="app_theme_g">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_h" data-app-theme="app_theme_h">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_i" data-app-theme="app_theme_i">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
                <li class="switcher_theme_dark" data-app-theme="app_theme_dark">
                    <span class="app_color_main"></span>
                    <span class="app_color_accent"></span>
                </li>
            </ul>
        </div>
        <div class="uk-visible-large uk-margin-medium-bottom">
            <h4 class="heading_c">Sidebar</h4>
            <p>
                <input type="checkbox" name="style_sidebar_mini" id="style_sidebar_mini" data-md-icheck />
                <label for="style_sidebar_mini" class="inline-label">Mini Sidebar</label>
            </p>
            <p>
                <input type="checkbox" name="style_sidebar_slim" id="style_sidebar_slim" data-md-icheck />
                <label for="style_sidebar_slim" class="inline-label">Slim Sidebar</label>
            </p>
        </div>
        <div class="uk-visible-large uk-margin-medium-bottom">
            <h4 class="heading_c">Layout</h4>
            <p>
                <input type="checkbox" name="style_layout_boxed" id="style_layout_boxed" data-md-icheck />
                <label for="style_layout_boxed" class="inline-label">Boxed layout</label>
            </p>
        </div>
        <div class="uk-visible-large">
            <h4 class="heading_c">Main menu accordion</h4>
            <p>
                <input type="checkbox" name="accordion_mode_main_menu" id="accordion_mode_main_menu" data-md-icheck />
                <label for="accordion_mode_main_menu" class="inline-label">Accordion mode</label>
            </p>
        </div>
    </div>

    <script>
	    
        $(function() {
            var $switcher = $('#style_switcher'),
                $switcher_toggle = $('#style_switcher_toggle'),
                $theme_switcher = $('#theme_switcher'),
                $mini_sidebar_toggle = $('#style_sidebar_mini'),
                $slim_sidebar_toggle = $('#style_sidebar_slim'),
                $boxed_layout_toggle = $('#style_layout_boxed'),
                $accordion_mode_toggle = $('#accordion_mode_main_menu'),
                $html = $('html'),
                $body = $('body');


            $switcher_toggle.click(function(e) {
                e.preventDefault();
                $switcher.toggleClass('switcher_active');
            });

            $theme_switcher.children('li').click(function(e) {
                e.preventDefault();
                var $this = $(this),
                    this_theme = $this.attr('data-app-theme');

                $theme_switcher.children('li').removeClass('active_theme');
                $(this).addClass('active_theme');
                $html
                    .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g app_theme_h app_theme_i app_theme_dark')
                    .addClass(this_theme);

                if(this_theme == '') {
                    localStorage.removeItem('altair_theme');
                    $('#kendoCSS').attr('href','<?php echo base_url();?>backend_asset/bower_components/kendo-ui/styles/kendo.material.min.css');
                } else {
                    localStorage.setItem("altair_theme", this_theme);
                    if(this_theme == 'app_theme_dark') {
                        $('#kendoCSS').attr('href','<?php echo base_url();?>backend_asset/bower_components/kendo-ui/styles/kendo.materialblack.min.css')
                    } else {
                        $('#kendoCSS').attr('href','<?php echo base_url();?>backend_asset/bower_components/kendo-ui/styles/kendo.material.min.css');
                    }
                }

            });

            // hide style switcher
            $document.on('click keyup', function(e) {
                if( $switcher.hasClass('switcher_active') ) {
                    if (
                        ( !$(e.target).closest($switcher).length )
                        || ( e.keyCode == 27 )
                    ) {
                        $switcher.removeClass('switcher_active');
                    }
                }
            });

            // get theme from local storage
            if(localStorage.getItem("altair_theme") !== null) {
                $theme_switcher.children('li[data-app-theme='+localStorage.getItem("altair_theme")+']').click();
            }


        // toggle mini sidebar

            // change input's state to checked if mini sidebar is active
            if((localStorage.getItem("altair_sidebar_mini") !== null && localStorage.getItem("altair_sidebar_mini") == '1') || $body.hasClass('sidebar_mini')) {
                $mini_sidebar_toggle.iCheck('check');
            }

            $mini_sidebar_toggle
                .on('ifChecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.setItem("altair_sidebar_mini", '1');
                    localStorage.removeItem('altair_sidebar_slim');
                    location.reload(true);
                })
                .on('ifUnchecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.removeItem('altair_sidebar_mini');
                    location.reload(true);
                });

        // toggle slim sidebar

            // change input's state to checked if mini sidebar is active
            if((localStorage.getItem("altair_sidebar_slim") !== null && localStorage.getItem("altair_sidebar_slim") == '1') || $body.hasClass('sidebar_slim')) {
                $slim_sidebar_toggle.iCheck('check');
            }

            $slim_sidebar_toggle
                .on('ifChecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.setItem("altair_sidebar_slim", '1');
                    localStorage.removeItem('altair_sidebar_mini');
                    location.reload(true);
                })
                .on('ifUnchecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.removeItem('altair_sidebar_slim');
                    location.reload(true);
                });

        // toggle boxed layout

            if((localStorage.getItem("altair_layout") !== null && localStorage.getItem("altair_layout") == 'boxed') || $body.hasClass('boxed_layout')) {
                $boxed_layout_toggle.iCheck('check');
                $body.addClass('boxed_layout');
                $(window).resize();
            }

            $boxed_layout_toggle
                .on('ifChecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.setItem("altair_layout", 'boxed');
                    location.reload(true);
                })
                .on('ifUnchecked', function(event){
                    $switcher.removeClass('switcher_active');
                    localStorage.removeItem('altair_layout');
                    location.reload(true);
                });

        // main menu accordion mode
            if($sidebar_main.hasClass('accordion_mode')) {
                $accordion_mode_toggle.iCheck('check');
            }

            $accordion_mode_toggle
                .on('ifChecked', function(){
                    $sidebar_main.addClass('accordion_mode');
                })
                .on('ifUnchecked', function(){
                    $sidebar_main.removeClass('accordion_mode');
                });


        });
		function getfoodname(restid){
				var catename = $("#category").val();
				var dataString = "catename="+catename+'&restid='+restid;
				$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/getproduct",
				data: dataString,
				success: function(data){
					$('#Foodname').selectize()[0].selectize.destroy();
					$("#Foodname").html(data);
					$('select#Foodname').selectize({});
					$("#addfood").addClass("disabled");
					}
				});
				}
function getproductname(restid,orderid){
	var productname = $('#Foodname option:selected').text();
	var proid=$("#Foodname").val();
	$("#productname").text(productname);
	$("#addfood").removeClass("disabled");
	var dataString = "productid="+proid+'&restid='+restid+'&orderid='+orderid;
	$.ajax({
				type: "POST",
				url: mybaseUrl+"crmlogin/getmenuinfo",
				data: dataString,
				success: function(data){
					altair_md.inputs();
					altair_md.checkbox_radio();
					altair_forms.select_elements();
					altair_forms.switches();
					//$('#checkrd').iCheck();
					//$("input[type='checkbox'], input[type='radio']").iCheck();
					$("#modal_overflow").html(data);
					
					//$('input').iCheck({checkboxClass: 'icheckbox', radioClass: 'icheckbox'});
					}
				});
	}


    </script>
    <!-- google web fonts -->
    <script type="text/javascript">
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
$(function(){  
          setInterval(function(){
            $.post("<?php echo base_url();?>crmlogin/incommingalert",function(data){
               $("#totord").html(data);
            });
          }, 2000);

        });
    </script>
    

  </body>
</html>