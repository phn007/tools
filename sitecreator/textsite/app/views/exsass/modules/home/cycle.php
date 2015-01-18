<?php
class Cycle {
	function createHtml( $params ) {
	?>
	<br><br><br>
	<h4>SlideShow</h4>
	<div class="cycle-slideshow" 
		data-cycle-fx="scrollHorz" 
		data-cycle-timeout="15000"
		data-cycle-caption-template="Slide no. {{slideNum}} of {{slideCount}} - {{cycleTitle}}"
		data-cycle-caption=".slider-caption">
		<div class="cycle-caption"></div>
		<?php foreach ( $params as $item ): ?>
		<img src="<?php echo $item['image_url']?>" alt="" data-cycle-title="<?php echo $item['keyword']?>">
		<?php endforeach ?>
	</div>
	<div class="slider-caption"></div>
	
	<hr>

	<h4>Continue SlideShow</h4>
	<div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="1" data-cycle-speed="15000" data-cycle-easing="linear">
		<?php foreach ( $params as $item ): ?>
		<img src="<?php echo $item['image_url']?>" alt="" data-cycle-title="<?php echo $item['keyword']?>">
		<?php endforeach ?>
	</div>

	<hr>

	<style>
		.cycle-pager span {
			font-size: 80px;
			width: 25px;
			height: 25px;
			display: inline-block;
			color: #ccc;
		}
		.cycle-pager span.cycle-pager-active {
			color: red;
		}
	</style>
	<h4>Slide show with pager</h4>
	<div class="cycle-slideshow" 
		data-cycle-fx="scrollHorz" 
		data-cycle-timeout="3000" 
		data-pager-template="<a href=#><img src='{{src}}' width='40' height='40'></a>">
		<div class="cycle-pager"></div>
		<?php foreach ( $params as $item ): ?>
		<img src="<?php echo $item['image_url']?>" alt="" data-cycle-title="<?php echo $item['keyword']?>">
		<?php endforeach ?>
	</div>

	<hr>

	<h4>Manual show with pager</h4>
	<div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="0">
		<div class="cycle-prev">Prev</div>
		<div class="cycle-next">Next</div>

		<?php foreach ( $params as $item ): ?>
		<img src="<?php echo $item['image_url']?>" alt="" data-cycle-title="<?php echo $item['keyword']?>">
		<?php endforeach ?>
	</div>


	<hr>

	<h4>Manual show with pager 2</h4>
	<div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="1000" data-cycle-slides="> div">
		
		<?php foreach ( $params as $item ): ?>
		<div>
			<img src="<?php echo $item['image_url']?>" alt="" data-cycle-title="<?php echo $item['keyword']?>">
			<p>This is an image description</p>
		</div>
		<?php endforeach ?>
	</div>

	<?php
	}
}