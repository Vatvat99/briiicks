<form method="post">
	<?php echo $form->input('title', 'Titre de l\'article'); ?>
	<?php echo $form->select('category_id', 'Catégorie', $categories); ?>
	<?php echo $form->textarea('content', 'Contenu'); ?>
	<button class="btn btn-primary">Enregistrer</button>
</form>