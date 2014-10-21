<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Server.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ServerDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/VMDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Report.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';

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

function getHTMLButtonAddServer() {
    if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
              return '<button id="add-slave-server" class="btn btn-info ladda-button ladda-progress mb5" data-style="expand-right">'
                          .'<span class="ladda-label">Add A Slave Server</span>'
                          .'<span class="ladda-spinner"></span>'
                          .'<span class="ladda-spinner"></span>'
                          .'<div class="ladda-progress" style="width: 100px;"></div>'
                      .'</button>';
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
                                    <a class="update-server" href="#">
                                        Update
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a class="text-danger remove-server" href="#">
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
					<div class="panel-toolbar text-right panel-toolbar-server">
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
							</div>'.
							/*'<div class="form-group">
								<label class="col-sm-3 control-label">Proc Using</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $server->getNbCurrentProc() .'/'. $server->getNbMaxProc() .'</p>
								</div>
							</div>*/
							'<div class="form-group">
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

function getHTMLButtonsManageVM() {
    if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<div class="btn-group">
                            <button type="button" class="btn btn-danger mb5 remove-vm"><i class="ico-fire22"></i> Delete</button>
                        </div>';
	http_response_code(401);
	return false;
}

function getHTMLPanelVM($VM) {
    return '<div class="col-md-4 server-panel">
			<!-- START panel -->
			<div class="panel panel-default">
				<!-- panel heading/header -->
				<div class="panel-heading">
					<h3 class="panel-title">Server: '. $VM->getServerIP() .'</h3>
					<!-- panel toolbar -->
					<div class="panel-toolbar text-right panel-toolbar-vm">
						<!-- option -->
						<div class="option">
							<button class="btn up" data-toggle="panelcollapse"><i class="arrow"></i></button>
						</div>
						<!--/ option -->
                                                <!-- panel toolbar button -->
                                                '.
                                                    ((isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? 
                                                    (getHTMLButtonsManageVM())
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
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<p class="form-control-static vm-name">'. $VM->getName() .'</p>
								</div>
							</div>
                                                        <div class="form-group">
								<label class="col-sm-3 control-label">IPV4</label>
								<div class="col-sm-9">
									<p class="form-control-static vm-ip">'. $VM->getIP() .'</p>
								</div>
							</div>
                                                        <div class="form-group">
								<label class="col-sm-3 control-label">Port</label>
								<div class="col-sm-9">
									<p class="form-control-static vm-port">'. $VM->getPort() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Disk Size</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $VM->getHDD() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">RAM Size</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $VM->getRAM() .'</p>
								</div>
							</div>
                                                        <span class="vm-id" style="display:none">'. $VM->getId() .'</span>
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

function getHTMLAllPanelVM() {
    $listVM = VMDAO::getListVM(); //getListVMWorking();

    $HTMLAllPanelVM = "";
    foreach($listVM as $VM) {
        $HTMLAllPanelVM .= getHTMLPanelVM($VM);
    }
    return $HTMLAllPanelVM;
}
/*
function getHTMLPanelRunningVM($VM) {
    return '<div class="col-md-4 server-panel">
			<!-- START panel -->
			<div class="panel panel-default">
				<!-- panel heading/header -->
				<div class="panel-heading">
					<h3 class="panel-title">Server: '. $VM->getServerIP() .'</h3>
					<!-- panel toolbar -->
					<div class="panel-toolbar text-right panel-toolbar-vm">
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
							<div class="form-group">
								<label class="col-sm-3 control-label">IPV4</label>
								<div class="col-sm-9">
									<p class="form-control-static vm-ip">'. $VM->getIP() .'</p>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $VM->getName() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Disk Size</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $VM->getHDD() .'</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">RAM Size</label>
								<div class="col-sm-9">
									<p class="form-control-static">'. $VM->getRAM() .'</p>
								</div>
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

function getHTMLAllPanelRunningVM() {
    $listRunningVM = VMDAO::getListVMByState(VM_STATE_USING);

    $HTMLPanelRunningVM = "";
    foreach($listRunningVM as $VM) {
        $HTMLPanelRunningVM .= getHTMLPanelRunningVM($VM);
    }
    return $HTMLPanelRunningVM;
}
*/
function getHTMLPanelNewVM() {
    return getHTMLPanelVM(VMDAO::getNewVM());
}

function getHTMLButtonLaunchAlgoOnVM() {
      if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<button id="launch-algo-vm" class="btn btn-info ladda-button ladda-progress mb5" data-style="expand-right">'
                            .'<span class="ladda-label">Launch Algo On VM</span>'
                            .'<span class="ladda-spinner"></span>'
                            .'<span class="ladda-spinner"></span>'
                            .'<div class="ladda-progress" style="width: 100px;"></div>'
                        .'</button>';
	http_response_code(401);
	return false;  
}

function getHTMLButtonAddVM() {
      if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
		return '<button id="add-vm" class="btn btn-info ladda-button ladda-progress mb5" data-style="expand-right">'
                            .'<span class="ladda-label">Add A VM</span>'
                            .'<span class="ladda-spinner"></span>'
                            .'<span class="ladda-spinner"></span>'
                            .'<div class="ladda-progress" style="width: 100px;"></div>'
                        .'</button>';
	http_response_code(401);
	return false;  
}

function getStyleLabelTypeReport($type) {
    $style = "";
    switch ($type) {
        case REPORTING_TYPE_SLAVE_ERROR:
            $style = "label-danger";
            break;
        case REPORTING_TYPE_SLAVE_BUG:
            $style = "label-warning";
            break;
        case REPORTING_TYPE_SLAVE_WARNING:
            $style = "label-warning";
            break;
        case REPORTING_TYPE_SECURITY:
            $style = "label-primary";
            break;
        case REPORTING_TYPE_INTERNAL_ERROR:
            $style = "label-danger";
            break;
        default:
            break;
    }
    return $style;
}

function getHTMLTableAllReport() {
    $listReport = ReportDAO::getAllReportExceptLog();
    
    $HTMLTableAllReport = ' <thead>
                                <tr id="table-all-report-thead-tr">
                                    '.
                                    ((isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? 
                                    ('<th>Action</th>')
                                    : 
                                    (''))
                                    .'
                                    <th style="display: none">id</th>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>';
    foreach($listReport as $report) {
        $HTMLTableAllReport .= '<tr class="table-all-report-tbody-tr">
                                    
                                    '.
                                    ((isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? 
                                    ('<td><button class="btn btn-success btn-report-solved" type="button">Solved</button></td>')
                                    : 
                                    (''))
                                    .'
                                    <td class="report-id" style="display: none">'.$report->getId().'</td>
                                    <td>'.$report->getuserLogin().'</td>
                                    <td>'.$report->getTitle().'</td>
                                    <td>'.$report->getDescription().'</td>
                                    <td><span class="label '. getStyleLabelTypeReport($report->getType()) .'">'.$report->getTypeName().'</span></td>
                                    <td>'.$report->getDate().'</td>
                                </tr>';
    }
    $HTMLTableAllReport .= '</tbody>';
    return $HTMLTableAllReport;
}

?>