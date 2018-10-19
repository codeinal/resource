<!doctype html>

<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->

<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Remove Tap Highlight on Windows Phone IE -->

    <meta name="msapplication-tap-highlight" content="no"/>



    <link rel="icon" type="image/png" href="<?php echo base_url();?>admin/assets/img/favicon.ico" sizes="16x16">

    <link rel="icon" type="image/png" href="<?php echo base_url();?>admin/assets/img/favicon.ico" sizes="32x32">



    <title>Foodmart-CRM Login</title>



    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>



    <!-- uikit -->

    <link rel="stylesheet" href="<?php echo base_url();?>admin/bower_components/uikit/css/uikit.almost-flat.min.css"/>



    <!-- altair admin login page -->

    <link rel="stylesheet" href="<?php echo base_url();?>admin/assets/css/login_page.min.css" />



</head>

<body class="login_page">



    <div class="login_page_wrapper">

        <div class="md-card" id="login_card">

            <div class="md-card-content large-padding" id="login_form">

                <div class="login_heading">

                    <div class="user_avatar"></div>

                </div>

                 <?php

                $exceptional=$this->session->userdata('exceptional');

                if($exceptional)

                {

            	?>

                <div class="uk-alert uk-alert-danger" data-uk-alert="">

                <?php

                    echo $exceptional;

                    $this->session->unset_userdata('exceptional');

                ?>

                            </div>

                 <?php }

				  $message=$this->session->userdata('message');

					if($message)

					{

				  ?>

                  <div class="uk-alert uk-alert-danger" data-uk-alert="">

                   <?php

                    echo $message;

                    $this->session->unset_userdata('message');

               		 ?>

                            </div>

                   <?php } ?>

                <form action="<?php echo base_url();?>Crm-Login-Check" method="post">

                    <div class="uk-form-row">

                        <label for="login_username">Username</label>

                        <input class="md-input" type="text" id="login-username" required name="admin_email_address" />

                    </div>

                    <div class="uk-form-row">

                        <label for="login_password">Password</label>

                        <input class="md-input" type="password" id="login-password" required name="admin_password" />

                    </div>

                    <div class="uk-margin-medium-top">

                         <input class="md-btn md-btn-primary" id="login" value="Login" name="login" type="submit" data-uk-button>

                    </div>

                </form>

            </div>

        </div>

        

    </div>



    <!-- common functions -->

    <script src="<?php echo base_url();?>admin/assets/js/common.min.js"></script>

    <!-- uikit functions -->

    <script src="<?php echo base_url();?>admin/assets/js/uikit_custom.min.js"></script>

    <!-- altair core functions -->

    <script src="<?php echo base_url();?>admin/assets/js/altair_admin_common.min.js"></script>



    <!-- altair login page functions -->

    <script src="<?php echo base_url();?>admin/assets/js/pages/login.min.js"></script>



    <script>

        // check for theme

        if (typeof(Storage) !== "undefined") {

            var root = document.getElementsByTagName( 'html' )[0],

                theme = localStorage.getItem("altair_theme");

            if(theme == 'app_theme_dark' || root.classList.contains('app_theme_dark')) {

                root.className += ' app_theme_dark';

            }

        }

    </script>



</body>

</html>