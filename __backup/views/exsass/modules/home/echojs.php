<?php
class EchoJs {
	function createHtml( $params ) {
	?>
	<br><br><br><br>
	<h1>Category1</h1>
	<?php foreach ( $params as $item ):?>
		<div>
		<img id="myechos" src="<?php echo IMG_PATH?>echos/blank.png" data-echo="<?php echo $item['image_url']?>" alt="Image">
		</div>
	<?php endforeach?>

	<h1>Category2</h1>
	<?php foreach ( $params as $item ):?>
		<div>
		<img id="myechos" src="<?php echo IMG_PATH?>echos/blank.png" data-echo="<?php echo $item['image_url']?>" alt="Image">
		</div>
	<?php endforeach?>

	<h1>Category3</h1>
	<?php foreach ( $params as $item ):?>
		<div>
		<img id="myechos" src="<?php echo IMG_PATH?>echos/blank.png" data-echo="<?php echo $item['image_url']?>" alt="Image">
		</div>
	<?php endforeach?>

	<h1>Category4</h1>
	<?php foreach ( $params as $item ):?>
		<div>
		<img id="myechos" src="<?php echo IMG_PATH?>echos/blank.png" data-echo="<?php echo $item['image_url']?>" alt="Image">
		</div>
	<?php endforeach?>

	<h1>Category5</h1>
	<?php foreach ( $params as $item ):?>
		<div>
		<img id="myechos" src="<?php echo IMG_PATH?>echos/blank.png" data-echo="<?php echo $item['image_url']?>" alt="Image">
		</div>
	<?php endforeach?>


	<style>
		#myechos img {
			/*width: 500px;*/
			/*height: 500px;*/
			background: #fff url( <?php echo IMG_PATH?>echos/ajax-loader.gif ) no-repeat center center;
			/*border: 1px solid #ccc;*/
			/*padding: 5px;*/
		}
	</style>

	<?php
	}
}