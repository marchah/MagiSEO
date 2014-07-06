<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Report.class.php';
@session_start();
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
						<h4 class="title semibold">Dashboard</h4>
					</div>
				</div>
				<!-- Page Header -->

				<div class="row">
					<!-- START Left Side (General Statistic) -->
					<div class="col-md-9">
						<!-- Top Stats -->
						<div class="row">
							<div class="col-sm-4">
								<!-- START Statistic Widget -->
								<div class="table-layout animation delay animating fadeInDown">
									<div class="col-xs-4 panel bgcolor-info">
										<div class="ico-archive fsize24 text-center"></div>
									</div>
									<div class="col-xs-8 panel">
										<div class="panel-body text-center">
											<h4 id="nb_slave_server" class="semibold nm"></h4>
											<p class="semibold text-muted mb0 mt5">SERVER SLAVE</p>
										</div>
									</div>
								</div>
								<!--/ END Statistic Widget -->
							</div>
							<div class="col-sm-4">
								<!-- START Statistic Widget -->
								<div class="table-layout animation delay animating fadeInUp">
									<div class="col-xs-4 panel bgcolor-teal">
										<div class="ico-skull3 fsize24 text-center"></div>
									</div>
									<div class="col-xs-8 panel">
										<div class="panel-body text-center">
											<h4 id="nb_error" class="semibold nm"></h4>
											<p class="semibold text-muted mb0 mt5">ERROR</p>
										</div>
									</div>
								</div>
								<!--/ END Statistic Widget -->
							</div>
							<div class="col-sm-4">
								<!-- START Statistic Widget -->
								<div class="table-layout animation delay animating fadeInDown">
									<div class="col-xs-4 panel bgcolor-primary">
										<div class="ico-cabinet fsize24 text-center"></div>
									</div>
									<div class="col-xs-8 panel">
										<div class="panel-body text-center">
											<h4 id="nb_vm" class="semibold nm"></h4>
											<p class="semibold text-muted mb0 mt5">VM</p>
										</div>
									</div>
								</div>
								<!--/ END Statistic Widget -->
							</div>
						</div>
						<!--/ Top Stats -->
					</div>
					<!--/ END Left Side -->

					<!-- START Right Side (Lastest Activity) -->
					<div class="col-md-3">
						<div class="panel panel-minimal">
							<div class="panel-heading"><h5 class="panel-title"><i class="ico-health mr5"></i>Latest Activity</h5></div>
						
							<!-- Media list feed -->
							<ul id="list_log" class="media-list media-list-feed nm">
							</ul>
							<!--/ Media list feed -->
						</div>
					</div>
					<!--/ END Right Side -->
				</div>
			</div>
			<!--/ END Template Container -->
		</section>
<?php
	require_once('include/footer.html');
?>
