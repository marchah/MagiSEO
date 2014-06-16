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
						<h4 class="title semibold">Reports</h4>
					</div>
				</div>
				<!-- Page Header -->

                                
                                <!-- START row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">List Reports</h3>
                            </div>
                            <table class="table table-striped" id="zero-configuration">
                                    <?php
                                        require_once('PHP/getHTMLCode.php');
                                        echo getHTMLTableAllReport();
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>
                <!--/ END row -->
 
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>
