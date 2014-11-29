<div class="container" id="content-main">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo $cat_title ?></h1>
			</div>
	</div>
	</div>

	<div clas="row">
		<div class="col-md-12">
		  <ol class="breadcrumb">
					<li><a title="<?php echo SITE_NAME?> Home Page" href="<?php echo HOME_URL ?>">Home</a></li>
					<li class="active"><?php echo $cat_title?></li>
				</ol>
		</div>
	</div>


	<div class="row">
      <div class="col-md-12">
		<?php
		foreach( $cat_list as $cat ):
		?>
			<div class="col-md-3"><a href="<?php echo $cat['url']?>"><?php echo $cat['cat_name'] ?></a></div>
		<?php endforeach ?>
		</div>
	</div>
	<br>
</div><!-- container -->
