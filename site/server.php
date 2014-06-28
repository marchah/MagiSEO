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
						<h4 class="title semibold">Informations Servers</h4>
					</div>
				</div>
				<!-- Page Header -->

                                <!-- START Add Slave Server -->
                                <div id="AddSlaveServerModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabe" aria-hidden="true">
                                  <div class="modal-dialog modal-sm">

                                        <div class="modal-content">
                                                <form>
                                                        <div class="modal-header">
                                                                <h4 class="modal-title">Add A Slave Server</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                                <div>
                                                                        <label for="add-slave-server-ip">IPV4</label>
                                                                        <input id="add-slave-server-ip" class="form-control" type="input" name="add-slave-server-ip" />
                                                                </div>
                                                                <div>
                                                                        <label for="add-slave-server-login">Login</label>
                                                                        <input id="add-slave-server-login" class="form-control" type="input" name="add-slave-server-login" />
                                                                </div>
                                                                <div>
                                                                        <label for="add-slave-server-password">Password</label>
                                                                        <input id="add-slave-server-password" class="form-control" type="password" name="add-slave-server-password" />
                                                                </div>
                                                                <div>
                                                                        <label for="add-slave-server-password-confirmation">Password Confirmation</label>
                                                                        <input id="add-slave-server-password-confirmation" class="form-control" type="password" name="add-slave-server-password-confirmation" />
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <span id="add-slave-server-failed" style="display:none;"></span>
                                                            <input id="add-slave-server-button" class="btn btn-success" type="button" name="submit" value="Add" />
                                                        </div>
                                                </form>
                                        </div>
                                  </div>
                                </div>
                                <!--/ END Add Slave Server -->
                                
                                <div id="progress-bar-container-server" class="progress progress-striped active" style="width: 500px; background-color: black; display: none;">
                                    <div id="progress-bar-server-slave" class="progress-bar progress-bar-success">
                                        <p id="state-server-slave"></p>
                                    </div>
                                </div>

                                <div id="panel-add-server-slave">
                                    <?php
                                        require_once('PHP/getHTMLCode.php');
                                        if (isset($_SESSION['auth']) && $_SESSION['auth'] == true)
                                            echo getHTMLButtonAddServer();
                                    ?>
                                </div>

                                
				<div id="list-planel" class="row">
					<!-- START Left Side (Informations Servers) -->
      
					<?php
						echo getHTMLAllPanelServer();
					?>
                                        
                    
					<!--/ END Left Side -->
				</div>
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>