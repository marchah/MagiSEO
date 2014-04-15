<?php
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

				<div class="row">
					<!-- START Left Side (Informations Servers) -->
					
					<?php
						require_once('PHP/getHTMLCode.php');
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