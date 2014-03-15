  <html>
 <head>
 	<?php Loader::element('header_required'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getThemePath(); ?>/asset/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getThemePath(); ?>/asset/css/style.css" />
	<script src="<?php echo $this->getThemePath(); ?>/asset/js/bootstrap.js"></script>
 </head>
 <body>
 	<div class="ombrage"></div>
	<div class="header">
			<div class="navbar navbar-default" role="navigation">
					<div class="navbar-header">
						<a class="navbar-brand noborder" href="">
							<img src="<?php echo $this->getThemePath(); ?>/asset/image/logo.png"/>
						</a>
						<button class="navbar-toggle" data-target="#navbar-main" data-toggle="collapse" type="button">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
					</div>
					<div class="col-md-offset-4 navbar-collapse collapse">
					<?php
						$nav = BlockType::getByHandle('autonav');
						$nav->controller->orderBy = 'display_asc';
						$nav->controller->displayPages = 'top';
						$nav->controller->displaySubPages = 'all';
						$nav->controller->displaySubPageLevels = 'custom';
						$nav->controller->displaySubPageLevelsNum = 2;
						$nav->render('templates/Polonav');
					?>			
					</div>	
			</div>
	</div>
	<div class="bandeau">
		<center>
			<div class="slider">
				<?php
					$a = new Area('Diaporama');
					$a->display($c);
				?>
			</div>
		</center>
	</div>
		<div class="row">
			<div class="main">
				<div class="col-md-12">   
					<?php  print $innerContent; ?>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
	</footer>
	<?php Loader::element('footer_required'); ?>
 </body>
 </html>
 