<?php
class masonry {
	function createHtml( $params ) {
	?>
	<br><br><br><br>
	<div id="topic-thumbs" class="row">
	<?php foreach ( $params as $item ):?>
		<div class="grid-sizer col-xs-6 col-sm-4 col-md-3"></div>
		<article class="item col-xs-6 col-sm-4 col-md-3">
			<div>
				<img src="<?php echo $item['image_url']?>">
				<div class="caption">
					<h3><?php echo $item['keyword']?></h3>
				</div>
			</div>
		</article>
	<?php endforeach?>
	</div>

	<div id="loading-indicator">
		<img class="loader-dots" src="http://meta.metafizzy.co/dump/loader-dots.svg" />
	</div>
	<?php
	}
}