<?php 
class ProductItemDetail {
	use Image;
	use Content;
	use SocialShare;

	function createHtml( $content ) {
		$productDetail = $content['product-detail'];
		$spinContent = $content['spin-content'];

		$this->displayImage( $productDetail );
		$this->displayProductDetail( $productDetail, $spinContent );

		echo "<pre>";
		print_r( $spinContent );
		echo "</pre>";
	}
}

trait Content {
	function displayProductDetail( $productDetail, $spinContent ) {
		extract( $productDetail );
	?>
		<?php $this->title( $goto, $keyword )?>
		<?php $this->socialShare( $permalink, $keyword )?>
		<?php $this->adtext1( $spinContent )?>
		<?php $this->brand( $brandLink, $brand )?>
		<?php $this->merchant( $merchant )?>
		<?php $this->price( $price )?>
		<?php $this->description( $description )?>
		<?php $this->button( $goto, $keyword )?>
	<?php
	}

	function title( $goto, $keyword ) {?>
		<div class="title">
			<h1><a title="<?php echo $keyword?>" href="<?php echo $goto?>"><?php echo $keyword?></a></h1>
		</div>
	<?php
	}

	function brand( $brandLink, $brand ) { ?>
		<div class="brand">
			<span>Brand:</span> <a title="<?php echo $brandLink?>" href="<?php echo $brandLink?>"><?php echo $brand?></a>
		</div>
	<?php
	}

	function merchant( $merchant ) { ?>
		<div class="merchant"><?php echo $merchant?></div>
	<?php
	}

	function price( $price ) { ?>
		<div class="price">$<?php echo $price?></div>
	<?php
	}

	function description( $description ) { ?>
		<div class="description"><?php echo $description?></div>
	<?php
	}

	function button( $goto, $keyword ) { ?>
		<div class="button"><a title="<?php echo $keyword?>" href="<?php echo $goto ?>">VISIT STORE</a></div>
	<?php
	}

	function adtext1( $spinContent ) {
		extract( $spinContent );
	?>
		<div class="srt col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h2><?php echo $ad1?></h2>
			<p><?php echo $ad_desc?></p>
		</div>
	<?php
	}
}

trait SocialShare {
	function socialShare( $permalink, $keyword ) {
		$title = rawurlencode( $keyword );
		$facebook = 'http://www.facebook.com/share.php?u=' . $permalink;
		$twitter = 'http://twitter.com/share?text=' . $permalink;
		$google = 'http://www.google.com/bookmarks/mark?op=add&bkmk=' . $permalink . '&title=' . $title;
		$pinterest = 'http://pinterest.com/pin/create/button?url=' . $permalink . '&amp;title=' . $title;
		$linkedIn   = 'http://linkedin.com/shareArticle?mini=true&amp;url=' . $permalink . '&amp;title=' . $title;
		$stumbleupon = 'http://www.stumbleupon.com/badge/?url=' . $permalink;
		
	?>
		<div class='social-share'>
			<a href="<?php echo $facebook?>"><i class='icon fa fa-facebook'></i></a>
			<a href="<?php echo $twitter?>"><i class='icon fa fa-twitter'></i></a>
			<a href="<?php echo $google?>"><i class='icon fa fa-google-plus'></i></a>
			<a href="<?php echo $pinterest?>"><i class='icon fa fa-pinterest'></i></a>
			<a href="<?php echo $linkedIn?>"><i class='icon fa fa-linkedin'></i></a>
			<a href="<?php echo $stumbleupon?>"><i class='icon fa fa-stumbleupon'></i></a>
		</div>
		<div style="clear: both"></div>
	<?php
	}
}

trait Image {
	function displayImage( $productDetail ) {
		extract( $productDetail );
	?>
		<div>
			<img src="<?php echo $image_url ?>" alt="<?php echo $keyword?>">

		</div> 
	<?php
	}
}
