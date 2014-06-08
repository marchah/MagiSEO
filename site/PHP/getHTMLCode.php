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

function getHTMLButtonsManageServer() {
    if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<div class="btn-group">
                            <button class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" type="button">
                                Action
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:void(0);">
                                        Update
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a class="text-danger remove-server" href="#");">
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>';
	http_response_code(401);
	return false;
}

function getHTMLPanelServer($server) {
    return '<div class="col-md-4 server-panel">
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
                                                <!-- panel toolbar button -->
                                                '.
                                                    ((isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? 
                                                    (getHTMLButtonsManageServer())
                                                    : 
                                                    (''))
                                                    .'
                                            <!--/ panel toolbar button -->
					</div>
					<!--/ panel toolbar -->
				</div>
				<!--/ panel heading/header -->
				
				<!-- panel body with collapse capable -->
				<div style="" class="panel-collapse in pull out">
					<div class="panel-body">
						<div class="form-horizontal form-bordered">
							<div class="form-group">
								<label class="col-sm-3 control-label">IPV4</label>
								<div class="col-sm-9">
									<p class="form-control-static server-ip">'. $server->getIPV4() .'</p>
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
									<p class="form-control-static server-username">'. $server->getUsername() .'</p>
								</div>
							</div>
							<div class="form-group footer-server-info">
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

function getHTMLAllPanelServer() {
    $listServer = ServerDAO::getListSlaveServer();

    $HTMLAllPanelServer = "";
    foreach($listServer as $server) {
        $HTMLAllPanelServer .= getHTMLPanelServer($server);
    }
    return $HTMLAllPanelServer;
}

function getHTMLPanelNewServer() {
    return getHTMLPanelServer(ServerDAO::getNewSlaveServer());
}

?>