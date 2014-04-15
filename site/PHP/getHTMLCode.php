<?php
@session_start();

require_once('getServerInformation.php');

function getHTMLUserButtonAuth() {
	if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<ul class="nav navbar-nav navbar-right"> 
					<!-- Profile dropdown -->
					
					<li class="dropdown profile">
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
							<span class="meta">
								<span class="avatar"><img src="'. $_SESSION['avatarPath'] .'" class="img-circle" alt=""></span>
								<span class="text hidden-xs hidden-sm">'. ucfirst($_SESSION['firstName']) .' '. strtoupper($_SESSION['lastName']) .'</span>
								<span class="arrow"></span>
							</span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="javascript:void(0);" id="disconnection_button"><span class="icon"><i class="ico-exit"></i></span> Sign Out</a></li>
						</ul>
					</li>
					<!--/ Profile dropdown -->
				</ul>';
	//http_response_code(401); for PHP > 5.4
	header(':', true, 401);
	header('X-PHP-Response-Code: 401', true, 401);
	return false;
}

function getHTMLAllPanelServer() {
	$listServer = getAllServerInfo();
	
	$HTMLAllPanelServer = "";
	
	foreach($listServer as $server) {
		$HTMLAllPanelServer .= '
		<div class="col-md-4">
			<!-- START panel -->
			<div class="panel panel-default">
				<!-- panel heading/header -->
				<div class="panel-heading">
					<h3 class="panel-title">'. $server->getName() .'</h3>
					<!-- panel toolbar -->
					<div class="panel-toolbar text-right">
						<!-- option -->
						<div class="option">
							<button class="btn up" data-toggle="panelcollapse"><i class="arrow"></i></button>
						</div>
						<!--/ option -->
					</div>
					<!--/ panel toolbar -->
				</div>
				<!--/ panel heading/header -->
				
				<!-- panel body with collapse capable -->
				<div style="" class="panel-collapse in pull out">
					<div class="panel-body">
						<div class="form-horizontal form-bordered">
							<span class="hidden id-server-slave">'. $server->getID() .'</span>
							<div class="form-group">
								<label class="col-sm-3 control-label">IPV4</label>
								<div class="col-sm-9">
									<input class="form-control ipv4-server-slave" type="text" value="'. $server->getIPV4() .'"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Working</label>
								<div class="col-sm-9">
									<input class="switchery" type="checkbox" checked="" style="display: none;" data-switchery="true"></input>'.
									(($server->getIsOn()) ?
									('<span class="switchery" style="background-color: rgb(100, 189, 99); border-color: rgb(100, 189, 99); box-shadow: 0px 0px 0px 16px rgb(100, 189, 99) inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;">
										<small style="left: 18px; transition: left 0.2s ease 0s;"></small>
									</span>')
									:
									('<span class="switchery" style="style="border-color: rgb(223, 223, 223); box-shadow: 0px 0px 0px 0px rgb(223, 223, 223) inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;"">
										<small style="left: 0px; transition: left 0.2s ease 0s;"></small>
									</span>'))
								.'</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">State</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $server->getState() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<input class="form-control name-server-slave" type="text" value="'. $server->getName() .'"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Disk Using</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $server->getDiskCurrentSize() .'/'. $server->getDiskMaxSize() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Proc Using</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $server->getNbCurrentProc() .'/'. $server->getNbMaxProc() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Flash Using</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $server->getFlashCurrentSize() .'/'. $server->getFlashMaxSize() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">UserName</label>
								<div class="col-sm-9">
									<input class="form-control username-server-slave" type="text" value="'. $server->getUsername() .'"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									<input class="form-control password-server-slave" type="password" value="'. $server->getPassword() .'"></input>
								</div>
							</div>
							<div class="form-group footer-server-info">
							'.
							((isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? 
							('
								<button type="button" class="btn btn-success btn-update-server">Update</button>
								<img class="img_loading_update_server" src="image/spinner.gif" alt="Loading ..." style="display:none;margin-right: 10px; float:right;margin-top: 8px;" />
							')
							: 
							(''))
							.'
							</div>
						</div>
					</div>
				</div>
				<!--/ panel body with collapse capabale -->

				<!-- Loading indicator -->
				<div class="indicator"><span class="spinner"></span></div>
				<!--/ Loading indicator -->
			</div>
			<!--/ END panel -->
		</div>
';
	}
	return $HTMLAllPanelServer;
}

/*

									'. (($server->getState()) ? ('<span class="switchery" style="border-color: #64BD63; box-shadow: 0px 0px 0px 16px rgb(100,…s ease 0s, background-color 1.2s ease 0s; background-color: #64BD63;">
																	<small style="left: 18px; transition: left 0.2s ease 0s;"></small>
																</span>')
															: ('<span class="switchery" style="border-color: #DFDFDF; box-shadow: 0px 0px 0px 0px rgb(223, …t; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;">
																	<small style="left: 0px; transition: left 0.2s ease 0s;"></small>
																</span>'))
									.'
									*/
?>