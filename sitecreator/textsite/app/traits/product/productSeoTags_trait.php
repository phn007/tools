<?php
trait ProductSeoTags {
	function getProductSeoTags() {
		$tagCom = $this->component( 'seoTags' );
		return $tagCom->createSeoTags( $this->setSeoTags() );
	}
	
	function setSeoTags() {
		$tags = array(
			'title' => $this->spinContent['title1'],
			'keywords' => $this->spinContent['title1'],
			'description' => trim( strip_tags( $this->spinContent['ad1'] ) ),
			'linkTag' => $this->permalink,
			'author' => AUTHOR,
			'propertyLocale' => 'en_US',
			'propertyType' => 'article',
			'propertyTitle' => $this->spinContent['title1'],
			'propertyDescription' => trim( strip_tags( $this->spinContent['ad1'] ) ),
			'propertyUrl' => $this->permalink,
			'propertySiteName' => SITE_NAME,
		);
		return $tags;
	}
}

//'

/*
$cpn->keywords = $spin['title1'];//
$cpn->author = AUTHOR;//
$cpn->title = $spin['title1'];//
$cpn->description = trim( strip_tags( $spin['ad1'] ) );
$cpn->link = $permalink;
$cpn->property_locale = 'en_US';
$cpn->property_type = 'article';
$cpn->property_title = $spin['title1'];
$cpn->property_description = trim( strip_tags( $spin['ad1'] ) );
$cpn->property_url = $permalink;
$cpn->property_site_name = SITE_NAME;

if ( isset( $tags['tag1'] ) )
	$cpn->property_article_tag1 = $tags['tag1'];

if ( isset( $tags['tag2'] ) )
	$cpn->property_article_tag2 = $tags['tag2'];

if ( isset( $tags['tag3'] ) )
	$cpn->property_article_tag3 = $tags['tag3'];

$cpn->property_article_section1 = $product['brand'];
$cpn->property_article_section2 = $product['category'];
*/