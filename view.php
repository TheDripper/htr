<?php

	$title = "Blog | IgnitedSpaces";
	$description = "A radical convergence of workspace and content creation studios, IgnitedSpaces is a place and community for brands and artists to leverage their voice for cultural and technological advancement.";

	$this->content['title'] = $title;
	$this->addHeadTag('meta', 'og:title', $title);
	$this->addHeadTag('meta', 'twitter:title', $title);
	
	$this->addHeadTag('meta', 'description', substr(strip_tags($description),0,240)."...");
	$this->addHeadTag('meta', 'twitter:description', substr(strip_tags($description),0,240)."...");
	$this->addHeadTag('meta', 'og:description', substr(strip_tags($description),0,240)."...");


	$this->addHeadTag('icon','/images/ignited/favicon.png');

	$image = "/images/ignited/og-image.jpg";
	$featuredWidth = 1200;
	$featuredHeight = 630;
	/*
	if(!empty($article['info']['media_featured_url'])) {
		$featuredimagedetails = getimagesize($article['info']['media_featured_url']);
		$featuredHeight = $featuredimagedetails[1];
		$featuredWidth = $featuredimagedetails[0];
	}
	*/
	$this->addHeadTag('meta', 'twitter:image', $image);
	$this->addHeadTag('meta', 'og:image', $image);
	$this->addHeadTag('meta', 'og:image:width', $featuredWidth);
	$this->addHeadTag('meta', 'og:image:height', $featuredHeight);

	$this->addHeadTag("script","/js/jquery.parallax-scroll.js");
	//$this->addHeadTag("script","https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js");

	$this->addHeadTag('stylesheet','/js/photoswipe/dist/photoswipe.css');
	$this->addHeadTag('stylesheet','/js/photoswipe/dist/default-skin/default-skin.css');

	$this->contentAfter .= <<<EOD
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
				<button class="pswp__button pswp__button--share" title="Share"></button>
				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
					  <div class="pswp__preloader__cut">
						<div class="pswp__preloader__donut"></div>
					  </div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div> 
			</div>
			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
			</button>
			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
			</button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>
EOD;
?>

<?php $post = $_GET['post']; ?>	
<?php $titles = array(
	'coworking' => "Coworking; How to network in your coworking space",
	'bulletproof' => "Why bulletproof coffee should be your next obsession. And your new breakfast.",
	'productivity' => "Productivity; Protecting The Brain From Social Media",
	'workspace'=> "Workspace Design and Productivity"
); ?>
<section id="home-hero" class="global_hero_simple">
	<div class="global_contain full">
		<div class="global_hero_simple_content">
		</div>
	</div>
</section>
<div id="afterhero"></div>

<style>
#home-hero {
	background-image:url("/images/ignited/culturehero.jpg");
}
</style>
<div id=post class=global_contain>
	<a href=/culture>
	<div id=retwrap>
		<img src=/images/ignited/return.svg id=ret />
		<h4>Return</h4>
	</div>
	</a>
<h1 class=blogtitle><?php echo $titles[$post]; ?></h1>
<?php echo file_get_contents("$post.html"); ?>
</div>
<h2 class=site_header>DISCOVER MORE STORIES</h2>
<div id=mason>
	<div class="blog card"><img src=/images/ignited/brain_thumb.png /><div class=wrap><h4>BLOG</h4><h2>Productivity; Protecting The Brain From Social Media </h2><a href=/blog?post=productivity>READ MORE</a></div></div>
	<div class="insta card"><img src=/images/ignited/insta2.png /><div class=wrap><h4>INSTAGRAM</h4><h2>“Wine Wednesday is back! Bringing you some extra motivation...”</h2><a href="https://www.instagram.com/p/BsbnPAinYP-/" target=_blank>READ MORE</a></div></div>
	<div class="news card"><img src=/images/ignited/nomad.png /><div class=wrap><h4>NEWS &amp; PRESS</h4><h2>How To Escape The Traditional Ofﬁce Environment As A Digital Nomad</h2><a href="https://www.forbes.com/sites/forbeslacouncil/2018/03/01/how-to-escape-the-traditional-office-environment-as-a-digital-nomad/#b8a9bd923ad5" target=_blank>READ MORE</a></div>
</div>
</div>
<?php //include __DIR__."/../section_contact.php" ?>
