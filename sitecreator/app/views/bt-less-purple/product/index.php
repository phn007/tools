<div class="container" id="content-main">

	<div class="row">
		<div class="adtext">
			<span><?php echo $title1?></span>
			<span style="font-size: 11px"><?php echo $ad1 ?></span>
			<a rel="nofollow" title="<?php echo $title1?>" href="<?php echo $goto ?>"><?php echo ucfirst( $more_info1 ) ?></a>
		</div>
		<hr>
	</div>

	<div class="row">
		<ol class="breadcrumb">
			<li><a title="<?php echo SITE_NAME?> Home Page" href="<?php echo HOME_URL ?>">Home</a></li>
	  		<li><a title="<?php echo $category?>" href="<?php echo $category_link ?>"><?php echo $category ?></a></li>
	  		<li class="active"><?php echo $keyword ?></li>
		</ol>
	</div>


	<div class="row">
		<div class="col-md-4">
			<a rel="nofollow" title="<?php echo $keyword ?>" href="<?php echo $goto ?>">
				<img src="<?php echo $image_url?>" alt="<?php echo $keyword?>">
			</a>
		</div>
		<div class="col-md-8" id="product-detail">
			<h1><?php echo $keyword ?></h1>

			<?php if ( $brand ):?>
				<div>Brand: <a title="<?php echo $brand ?>" href="<?php echo $brand_link ?>"><?php echo $brand ?></a></div>
			<?php endif ?>
			<hr>
			<div class="row">
				<div class="col-md-2" id="price">$<?php echo $price ?></div>
				<div class="col-md-10">
					<a rel="nofollow" title="<?php echo $keyword ?>" class="btn btn-warning" href="<?php echo $goto ?>">
						<i class="glyphicon glyphicon-shopping-cart"></i>Visit Store</a>
				</div>
			</div>
			<hr>
			<p><?php echo $description ?></p>
			<hr>
			<div>
				<ul class="list-inline">
					<?php
					new Widget( 'bt-less/socialshare' );
					SocialShare::display( $keyword, $permalink );
					?>
				</ul>
			</div>
		</div>
	</div>


	<?php if ( $related ): ?>
	<div class="row">
		<div class="col-md-12">

			<hr>
			<div style="text-align: center; margin-bottom: 30px"><h2>YOU MAY ALSO LIKE</h2></div>

			<?php
				new Widget( 'bt-less/relatedproduct' );
				RelatedProduct::display( $related );
			?>
		</div>
	</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-12">

			<h2><?php echo $title2?></h2>

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
			  <li class="active"><a href="#lastest-search" role="tab" data-toggle="tab">Lastest Search</a></li>
			  <li><a href="#video" role="tab" data-toggle="tab">Video</a></li>
			  <li><a href="#about" role="tab" data-toggle="tab">About</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active product-tab" id="lastest-search"><?php echo $spam ?></div>
			  	<div class="tab-pane product-tab" id="video">
					<h4><?php echo ucfirst( $textsearch )?></h4>
					<iframe width="430" height="315" src="http://youtube.com/embed?listType=search;list=<?php echo urlencode( $textsearch )?>"></iframe>
				</div>
			  	<div class="tab-pane product-tab" id="about">
					<p><?php echo $ad_desc ?></p>
					<p><?php echo $ad2 ?></p>
					<a rel="nofollow" title="<?php echo $keyword ?>" href="<?php echo $goto ?>"><?php echo ucfirst( $more_info2 ) ?></a>
			  	</div>
			</div>

		</div>
	</div>


	<div class="row">
		<ul class="pager">
			<li <?php echo $first_state ?>><?php echo $link_first?></li>
			<li <?php echo $prev_state ?>><?php echo $link_prev?></li>
			<li <?php echo $next_state ?>><?php echo $link_next?></li>
			<li <?php echo $last_state ?>><?php echo $link_last?></li>
		</ul>
	</div>
</div><!-- container -->
