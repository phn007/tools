<div class="container" id="content-main">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo $cat_title . ': ' . $item['cat_name'] ?></h1>
			</div>
		</div>
	</div>

	<div clas="row">
		<div class="col-md-12">
		  <ol class="breadcrumb">
					<li><a title="<?php echo SITE_NAME?> Home Page" href="<?php echo HOME_URL ?>">Home</a></li>
					<li class="active"><?php echo $item['cat_name'] ?></li>
				</ol>
		</div>
	</div>


	<div class="row">
			<div class="col-md-12">
				<ul class="pager">
					<li <?php echo $menu['first_state'] ?>><?php echo $menu['first']?></li>
					<li <?php echo $menu['prev_state'] ?>><?php echo $menu['prev']?></li>
					<li <?php echo $menu['next_state'] ?>><?php echo $menu['next']?></li>
					<li <?php echo $menu['last_state'] ?>><?php echo $menu['last']?></li>
				</ul>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
		<?php
		if ( $item['show_items'] )
		{
			foreach ( $item['show_items'] as $list )
			{
				$list['price'] = '$' . $list['price'];
				new Widget( 'bt-less/catitems' );
				CatItems::showItems( $list );
			}
		}
		?>
		</div>
	</div>


	<div class="row">
      	<div class="col-md-12">
      		<ul class="pager">
	      		<li <?php echo $menu['first_state'] ?>><?php echo $menu['first']?></li>
	      		<li <?php echo $menu['prev_state'] ?>><?php echo $menu['prev']?></li>
				<li <?php echo $menu['next_state'] ?>><?php echo $menu['next']?></li>
				<li <?php echo $menu['last_state'] ?>><?php echo $menu['last']?></li>
      		</ul>
		</div>
	</div>
</div><!-- container -->
