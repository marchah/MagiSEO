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
                                                                
                                <div id="list-panel" class="row" style="margin-left: 20px; margin-right: 20px">
				      <?php
				            require_once('PHP/getHTMLCode.php');
                                            echo getHTMLAllPanelAlgoResult();
                                      ?>
				</div>
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>