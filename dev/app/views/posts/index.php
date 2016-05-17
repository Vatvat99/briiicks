<h1>Page home</h1>
<div class="row">
	<div class="col-sm-8">
		<?php foreach($posts as $post) { ?>
			<h2>
				<a href="<?php echo $post->url; ?>">
					<?php echo $post->title; ?>
				</a>
			</h2>
			<p><em><?php echo $post->category; ?></em></p>
			<p>
				<?php echo $post->excerpt; ?>
			</p>
		<?php } ?>
	</div>
	<div class="col-sm-4">
		<ul>
			<?php foreach($categories as $category) { ?>
				<li><a href="<?php echo $category->url; ?>"><?php echo $category->title; ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>