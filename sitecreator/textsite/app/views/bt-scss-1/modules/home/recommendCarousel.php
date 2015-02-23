<?php
class RecommendCarousel {

	function createHtml( $productItems ) {
		$productGroups = $this->splitData( $productItems, 4 );
		$this->style();
	?>
	<div class="slider-pager"></div>
	<ul style="text-align: center" class="cycle-slideshow" 
		data-cycle-slides="li"
		data-cycle-loader="true"
		data-cycle-fx="scrollHorz" 
		data-cycle-timeout="15000"
		data-cycle-pager=".slider-pager">

		<div class="cycle-pager col-xs-12 col-sm-12 col-md-12"></div>
    	<?php foreach ( $productGroups as $group ): ?>
 		<li class="col-xs-12 col-sm-12 col-md-12"><?php echo $group ?></li>
    	<?php endforeach ?>
	</ul>

	<?php
	}

	function splitData( $productItems, $numberPerGroup ) {
		$groups = array_chunk( $productItems, $numberPerGroup );
		foreach ( $groups as $items ) {
			$data[] = $this->dataFormatting( $items );
		}
		return $data;
	}

	function dataFormatting( $items ) {
		$data = null;
		foreach ( $items as $item ) {
			$data .= '<div class="col-xs-12 col-sm-6 col-md-3 view view-first">';
			$data .= '<a href="#"><img src="'. $item['image_url'] . '" width="250"></a>';
			$data .= '<h2 style="font-size: 16px">' . $item['keyword'] . '</h2>';
			$data .= '</div>'; //view view-first
		}
		return $data;
	}

	function style() {
	?>
	<style>
		.slider-pager {
			text-align: center;
			margin-bottom: 20px;
		}

		.slider-pager span {
			font-size: 50px;
			/*width: 30px;
			height: 30px;*/
			display: inline-block;
			color: #ccc;
		}
		.slider-pager span.cycle-pager-active {
			color: red;
		}

		.slider-pager > * {
			cursor: pointer;
		}

		/*IMAGE HOVER EFFECT*/


.view-first img {
   -webkit-transition: all 0.2s linear;
   -moz-transition: all 0.2s linear;
   -o-transition: all 0.2s linear;
   -ms-transition: all 0.2s linear;
   transition: all 0.2s linear;
}

.view-first:hover img {
   -webkit-transform: scale(1.1,1.1);
   -moz-transform: scale(1.1,1.1);
   -o-transform: scale(1.1,1.1);
   -ms-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);
}

	</style>

	<?php
	}
}

// data-cycle-fx="fadeout"