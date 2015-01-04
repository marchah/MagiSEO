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
						<h4 class="title semibold">Algorithme</h4>
					</div>
				</div>
				<!-- Page Header -->
                                
                                <!-- START Launch Algo On VM -->
                                <div id="LaunchAlgoOnVMModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabe" aria-hidden="true">
                                  <div class="modal-dialog modal-sm">

                                        <div class="modal-content">
                                                <form>
                                                        <div class="modal-header">
                                                                <h4 class="modal-title">Launch Algo On VM</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                                <div>
                                                                    <label for=vm-available-ip">VM Available</label>
                                                                    <select id="vm-available-ip" class="form-control" name="vm-available-ip">
                                                                        <?php
                                                                            require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/VMDAO.class.php';
                                                                            $listVM = VMDAO::getListVMByState(VM_STATE_DONE);
                                                                            foreach($listVM as $VM) {
                                                                                echo '<option value="'. $VM->getId() .'">'. $VM->getName() .' - RAM: '.$VM->getRAM().'</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                        <label for="url-site">URL Site</label>
                                                                        <input id="url-site" class="form-control" type="input" name="url-site" />
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <span id="launch-algo-vm-failed" style="display:none;"></span>
                                                            <input id="launch-algo-vm-button" class="btn btn-success" type="button" name="submit" value="Launch" />
                                                        </div>
                                                </form>
                                        </div>
                                  </div>
                                </div>
                                <!--/ END Launch Algo On VM -->
                                
                                <div id="panel-launch-algo-vm">
                                    <?php
                                        require_once('PHP/getHTMLCode.php');
                                        if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
                                            echo getHTMLButtonLaunchAlgoOnVM();
                                    ?>
                                </div>

                                <div id="list-panel" class="row">
					<!-- START Left Side (Informations VMs) -->
      
					
                                        <?php
						echo getHTMLPanelVMByState(VM_STATE_USING);
					?>
                    
					<!--/ END Left Side -->
				</div>
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>