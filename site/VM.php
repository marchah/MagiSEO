<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
session_start();
require_once('include/header.php');
require_once('include/menu.html');
?>

        <!-- START Template Main -->
		
        <section id="main" role="main">
            <!-- START Template Container -->
			<div class="container-fluid">
				<!-- Page Header -->
				<div class="page-header page-header-block">
					<div class="page-header-section">
						<h4 class="title semibold">Informations VMs</h4>
					</div>
				</div>
				<!-- Page Header -->

                                <!-- START Add Slave VM -->
                                <div id="AddVMModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabe" aria-hidden="true">
                                  <div class="modal-dialog modal-sm">

                                        <div class="modal-content">
                                                <form>
                                                        <div class="modal-header">
                                                                <h4 class="modal-title">Add A VM</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                                <div>
                                                                    <label for="add-vm-ip_server">IPV4 Server</label>
                                                                    <select id="add-vm-ip-server" class="form-control" name="add-vm-ip-server">
                                                                        <?php
                                                                            $listServer = ServerDAO::getListSlaveServer();
                                                                            foreach($listServer as $server) {
                                                                                echo '<option value="'. $server->getId() .'">'. $server->getIPV4() .'</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                        <label for="add-vm-name">Name</label>
                                                                        <input id="add-vm-name" class="form-control" type="input" name="add-vm-name" />
                                                                </div>
                                                                <div>
                                                                        <label for="add-vm-ram">RAM</label>
                                                                        <input id="add-vm-ram" class="form-control" type="input" name="add-vm-ram" />
                                                                </div>
                                                                <div>
                                                                        <label for="add-vm-hdd">HDD Size</label>
                                                                        <input id="add-vm-hdd" class="form-control" type="input" name="add-vm-hdd" />
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <span id="add-vm-failed" style="display:none;"></span>
                                                            <input id="add-vm-button" class="btn btn-success" type="button" name="submit" value="Add" />
                                                        </div>
                                                </form>
                                        </div>
                                  </div>
                                </div>
                                <!--/ END Add VM -->
                                
                                <div id="progress-bar-container-vm" class="progress progress-striped active" style="width: 500px; background-color: black; display: none;">
                                    <div id="progress-bar-vm" class="progress-bar progress-bar-success">
                                        <p id="state-vm"></p>
                                    </div>
                                </div>

                                <div id="panel-add-vm">
                                    <?php
                                        require_once('PHP/getHTMLCode.php');
                                        if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
                                            echo getHTMLButtonAddVM();
                                    ?>
                                </div>

                                
				<div id="list-panel" class="row">
					<!-- START Left Side (Informations VMs) -->
      
					
                                        <?php
						echo getHTMLAllPanelVM();
					?>
                    
					<!--/ END Left Side -->
				</div>
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>
