<?php
class Header {
	function createHtml( $data ) {
		$cat = new CatMenuList_Plugin();
		$mnList = $cat->menuList();
	?>
		<div id="top-h">
			<div id="top-h-container">
				<ul id="top-h-l">
					<li><a href="<?php echo HOME_URL?>categories<?php echo FORMAT?>">All Categories</a></li>
					<li><a href="<?php echo HOME_URL?>brands<?php echo FORMAT?>">All Brands</a></li>
				</ul>
	            <ul id="top-h-r">
	                <li><a href="<?php echo HOME_URL?>contact<?php echo FORMAT?>">Contact</a></li>
	                <li><a href="<?php echo HOME_URL?>about<?php echo FORMAT?>">About</a></li>
	                <li><a href="<?php echo HOME_URL?>privacy-policy<?php echo FORMAT?>">Privacy Policy</a></li>
	            </ul>
			</div>
        </div>
        <div id="mid-h">
            <div id="sitename">
            	<a href="<?php echo HOME_LINK?>"><?php echo strtoupper( SITE_NAME )?></a>
            </div>
            <div id="desc">
            	<?php if ( $data['current-page'] == 'home-page' ) echo SITE_DESC ?>
            	<?php if ( $data['current-page'] == 'product-page' ) echo $data['product-detail']['keyword'] ?>
            </div>
        </div>
        <div class="navigation">
            <div class="navigation-wrapper">
                <a href="<?php echo HOME_LINK?>" class="logo"><span id="home-icon" class="icon fa fa-home"></span></a>
                <a href="javascript:void(0)" class="navigation-menu-button" id="js-mobile-menu"><span class="icon fa fa-navicon"></span></a>
                <nav role="navigation">
                    <ul id="js-navigation-menu" class="navigation-menu show">
                        <?php foreach ( $mnList as $name => $link ): ?>
							<li class="nav-link"><a href="<?php echo $link?>"><?php echo ucwords( $name )?></a></li>
                        <?php endforeach ?>
                    </ul>
                </nav>
            </div>
        </div>
	<?php
	}

	function createHtml_1() {
		$this->topHeader();
		$this->siteInfo();
		$this->navigation();
	}

	function topHeader_1() {
	?>
		<div class="top-header">
			<div class="header-container">
				<ul class="topmenu">
					<li><a href="<?php echo HOME_URL?>about<?php echo FORMAT?>">About</a></li>
					<li><a href="<?php echo HOME_URL?>contact<?php echo FORMAT?>">Contact</a></li>
					<li><a href="<?php echo HOME_URL?>privacy-policy<?php echo FORMAT?>">Privacy-Policy</a></li>
				</ul>
				<ul class="top-social">
					<li><a href="<?php echo $this->facebook()?>"><i class='icon fa fa-facebook'></i></a></li>
					<li><a href="<?php echo $this->twitter()?>"><i class='icon fa fa-twitter'></i></a></li>
					<li><a href="<?php echo $this->googlePlus()?>"><i class='icon fa fa-google-plus'></i></a></li>
					<li><a href="<?php echo $this->pinterest()?>"><i class='icon fa fa-pinterest'></i></a></li>
					<li><a href="<?php echo $this->linkedIn()?>"><i class='icon fa fa-linkedin'></i></a></li>
					<li><a href="<?php echo $this->stumbleupon()?>"><i class='icon fa fa-stumbleupon'></i></a></li>
				</ul>
			</div>
		</div>
	<?php
	}

	function siteInfo() {
	?>
		<div class="site-info">
			<div class="site-info-wrappe">
				<a href="<?php echo HOME_LINK?>"><?php echo strtoupper( SITE_NAME )?></a>
			</div>	
		</div>
	<?php
	}

	function navigation() {
	?>
		<div class="navigation">
			<div class="header-container">
				<!--LOGO-->
				<a href="javascript:void(0)" class="logo">CATEGORY</a>

				<!--MENU BUTTON-->
				<a href="javascript:void(0)" class="navigation-menu-button" id="js-mobile-menu">MENU</a>

				<!--NAVIGATION MENU-->
				<nav role="navigation">
					<ul id="js-navigation-menu" class="navigation-menu">
						<li><a href="<?php echo HOME_LINK?>"><span class="icon fa fa-home"></span></a></li>
						<li><a href="<?php echo HOME_URL?>categories<?php echo FORMAT?>">Categories</a></li>
						<li><a href="<?php echo HOME_URL?>brands<?php echo FORMAT?>">Brands</a></li>
					</ul>
				</nav>
			</div>
		</div>
	<?php
	}
}

trait HeaderSocialShare {
	function facebook() {
		return 'http://www.facebook.com/share.php?u=' . $this->url();
	}
	function twitter() {
		return 'http://twitter.com/share?text=' . $this->url();
	}
	function googlePlus() {
		return 'http://www.google.com/bookmarks/mark?op=add&bkmk=' . $this->url() . '&title=' . SITE_NAME;
	}
	function pinterest() {
		return 'http://pinterest.com/pin/create/button?url=' . $this->url() . '&amp;title=' . SITE_NAME;
	}
	function linkedIn() {
		return 'http://linkedin.com/shareArticle?mini=true&amp;url=' . $this->url() . '&amp;title=' . SITE_NAME;
	}
	function stumbleupon() {
		return 'http://www.stumbleupon.com/badge/?url=' . $this->url();
	}

	function url() {
		return rtrim( HOME_LINK, '/' );
	}
}