<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Server.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ServerDAO.class.php';

function getHTMLUserButtonAuth() {
	if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<ul class="nav navbar-nav navbar-right"> 
					<!-- Profile dropdown -->
					
					<li class="dropdown profile">
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
							<span class="meta">
								<span class="avatar"><img src="'. $_SESSION['user']->getAvatarPath() .'" class="img-circle" alt=""></span>
								<span class="text hidden-xs hidden-sm">'. ucfirst($_SESSION['user']->getFirstName()) .' '. strtoupper($_SESSION['user']->getLastName()) .'</span>
								<span class="arrow"></span>
							</span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="javascript:void(0);" id="disconnection_button"><span class="icon"><i class="ico-exit"></i></span> Sign Out</a></li>
						</ul>
					</li>
					<!--/ Profile dropdown -->
				</ul>';
	http_response_code(401);
	return false;
}

function getHTMLAllPanelServer() {
	$listServer = ServerDAO::getListSlaveServer();
	
	$HTMLAllPanelServer = "";
	
	foreach($listServer as $server) {
		$HTMLAllPanelServer .= '
		<div class="col-md-4">
			<!-- START panel -->
			<div class="panel panel-default">
				<!-- panel heading/header -->
				<div class="panel-heading">
					<h3 class="panel-title">'. $server->getIPV4() .'</h3>
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
									<p class="form-control-static">'. $server->getIPV4() .'</p>
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
									<p class="form-control-static">'. $server->getUsername() .'</p>
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

function getHTMLPanelNewServer() {
    
    $server = ServerDAO::getNewSlaveServer();
    return '<div class="col-md-4">
			<!-- START panel -->
			<div class="panel panel-default">
				<!-- panel heading/header -->
				<div class="panel-heading">
					<h3 class="panel-title">'. $server->getIPV4() .'</h3>
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
									<p class="form-control-static">'. $server->getIPV4() .'</p>
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
									<p class="form-control-static">'. $server->getUsername() .'</p>
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
		</div>';
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