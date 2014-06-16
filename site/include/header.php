<!DOCTYPE html>
<html>
    <!-- START Head -->
<head>
        <!-- START META SECTION -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Admin MagiSEO</title>
<meta name="description" content="MagiSEO admin SEO">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="image/touch/apple-touch-icon-144x144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="image/touch/apple-touch-icon-114x114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="image/touch/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="image/touch/apple-touch-icon-57x57-precomposed.png">
<link rel="shortcut icon" href="image/touch/apple-touch-icon.png">
<!--/ END META SECTION -->

<!-- START STYLESHEETS -->
<!-- 3rd party plugin stylesheet : optional(per use) -->
<link href="plugins/gritter/css/jquery.gritter.min.css" rel="stylesheet"></link>
<link href="plugins/switchery/css/switchery.min.css" rel="stylesheet"></link>
<!--/ 3rd party plugin stylesheet -->

<!-- Library stylesheet : mandatory -->
<link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="library/jquery/css/jquery-ui.min.css">
<!--/ Library stylesheet -->

<!-- Plugins stylesheet : optional -->
<link rel="stylesheet" href="plugins/datatables/css/jquery.datatables.min.css">
<!--/ Plugins stylesheet -->

<!-- Application stylesheet : mandatory -->
<link rel="stylesheet" href="stylesheet/layout.min.css">
<link rel="stylesheet" href="stylesheet/uielement.min.css">
<link rel="stylesheet" href="stylesheet/backend.min.css">
<!--/ Application stylesheet -->
<!-- END STYLESHEETS -->

<!-- START JAVASCRIPT SECTION - Load only modernizr script here -->
<script src="library/modernizr/js/modernizr.min.js"></script>
<!--/ END JAVASCRIPT SECTION -->
    </head>
    <!--/ END Head -->

	<body data-page="dashboard">

		<!-- START Authentification -->
		<div id="authentificationModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabe" aria-hidden="true">
		  <div class="modal-dialog modal-sm">

			<div class="modal-content">
				<form>
					<div class="modal-header">
						<h4 class="modal-title">Authentification</h4>
					</div>
					<div class="modal-body">				
						<div>
							<label for="auth_login">Login</label>
							<input id="auth_login" class="form-control" type="input" name="auth_login" />
						</div>
						<div>
							<label for="auth_password">Password</label>
							<input id="auth_password" class="form-control" type="password" name="auth_password" />
						</div>
						<div>
							<p id="auth_failed" style="display:none;"></p>
						</div>
					</div>
					<div class="modal-footer">
						<img id="img_loading_auth" src="image/spinner.gif" alt="Loading ..." style="margin-right: 10px;display:none;" />
						<input id="auth_button" class="btn btn-success" type="button" name="submit" value="Connection" />
					</div>
				</form>
			</div>
		  </div>
		</div>
		<!--/ END Authentification -->
		
		<!-- START Template Header -->
        <header id="header" class="navbar navbar-fixed-top">
		
            <!-- START navbar header -->
            <div class="navbar-header">
                <!-- Brand -->
                <a class="navbar-brand" href="index.php">
                    <span class="template-logo"></span>
                </a>
                <!--/ Brand -->
            </div>
            <!--/ END navbar header -->
			
            <!-- START Toolbar -->
			<div class="navbar-toolbar clearfix">
                <!-- START Right nav -->
				<?php
					require_once('PHP/getHTMLCode.php');
					if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
						echo getHTMLUserButtonAuth();
					else
						echo '<button id="btn_authentification" class="btn btn-default btn-connection" type="button">Connection</button>';
				?>
                <!--/ END Right nav -->
			</div>
            <!--/ END Toolbar / link -->
                    
        </header>
        <!--/ END Template Header -->