<!DOCTYPE html>
<html>
	<head>
		<title>Standards Review<?php if($title_for_layout) { echo(" :: ".$title_for_layout); } ?></title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1">
		<?php echo($this->fetch('meta')); ?>
		
		<?php echo($this->Html->css(array("reset-min", "styles"))); ?>
		<?php echo($this->fetch('css')); ?>
		
		<?php echo($this->fetch('script')); ?>
		<?php $__temp = $this->fetch('script-async');
		if ($__temp) {
			$parser = new DOMDocument('1.0', 'utf-8');
			$parser->loadHTML($__temp);
			
			$scripts = array();
			$complete = "";
			
			foreach ($parser->getElementsByTagName("script") as $tag) {
				if ($tag->hasAttribute("src")) {
					$scripts[] = $tag->getAttribute("src");
				} else {
					$complete .= "\n" . trim(str_replace(array('//<![CDATA[', '//]]>'), '', $tag->nodeValue));
				}
			}
			
			echo($this->Html->script("yepnope"));
			echo($this->Html->scriptBlock("yepnope({ load: " . json_encode($scripts) . ", complete: function() {\n" . trim($complete) . "\n} });"));
			
			unset($scripts); unset($complete);
			unset($parser); unset($tag);
		} unset($__temp); ?>
	</head>
	<body>
	
		<header id="top">
			<hgroup id="logo">
				<h1><?php echo($this->Html->link("Standards Review", "/")); ?></h1>
				<h2>Where your ideas go to die</h2>
			</hgroup>
			<nav>
				<?php echo($this->Html->link("Browse Issues", "/issues", array("rel" => "search"))); ?>
				<?php echo($this->Html->link("About Us", "/about", array("rel" => "author"))); ?>
			</nav>
		</header>
		
		<?php $__temp = $this->fetch('sidebar'); if ($__temp) { echo('<aside id="sidebar">' . $__temp . '</aside>'); } unset($__temp); ?>
	
		<div id="content">
			<?php echo($this->Session->flash()); ?>
			<?php echo($this->fetch('content')); ?>
		</div>
	
		<footer id="bottom">
			<img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-performance-semantics.png" width="99" height="32" alt="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics" title="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics">
			© Arran Ross-Paterson &amp; Mikołaj Banasik, <a href="http://welikepie.com" rel="external author">We Like Pie</a> 2012
		</footer>
	
	</body>
</html>