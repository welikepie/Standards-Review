<?php
	
	App::import('Vendor', 'markdown');

	// Only render the additional elements
	// for standard, non-AJAX requests.
	if (!$is_ajax) {
	
		$this->Html->script(array(
			'jquery-1.7.2.min',
			'scripts'
		), array(
			'inline' => false,
			'block' => 'script-async'
		));
		
		$this->Html->scriptBlock(
			'scripts.paginate("div#issues:first");',
			array(
				'inline' => false,
				'block' => 'script-async'
			)
		);

?><h1>Issue Index</h1><?php

	}
	
	// Render each of the issues into
	// a single div (the only AJAX element)

?><div id="issues">
<?php foreach($issues as &$issue) { ?>
	<article>
		<h2><?php echo($issue['title']); ?></h2>
		<p class="created">Made by <?php echo($issue['author']); ?> on <?php echo($issue['created']); ?></p>
		<div class="description">
			<?php echo(Markdown($issue['description'])); ?>
		</div>
		<p class="status_<?php echo($issue['state']['state_id']); ?>"><?php echo($issue['state']['name']); ?></p>
		<p class="revised">Last edit by <?php echo($issue['revisionist']); ?> on <?php echo($issue['revised']); ?></p>
		<?php echo($this->Html->link(
			'View Solutions',
			'/issues/' . $issue['issue_id'] . '/solutions'
		)); ?>
	</article>
<?php } ?>
</div>
<?php

	// Render the paging elements
	if (!$is_ajax) {
?><div id="paginator"><?php
		for ($i = 1; $i <= $pages['total']; $i++) {
		
			echo $this->Html->link(
				$i,
				"/issues?page=" . $i,
				( $i === $pages['current'] ? array("class" => "current") : array() )
			);
		
		}
?></div><?php
	}

?>