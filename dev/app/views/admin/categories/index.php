<h1>Administrer les catégories</h1>
<p>
	<a href="/admin/categories/add" class="btn btn-success">Ajouter</a>
</p>
<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Titre</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($categories as $category) { ?>
		<tr>
			<td><?php echo $category->id; ?></td>
			<td><?php echo $category->title; ?></td>
			<td>
				<a href="/admin/categories/edit?id=<?php echo $category->id; ?>" class="btn btn-primary">Editer</a>
				<form action="/admin/categories/delete" method="post" style="display: inline;">
					<input type="hidden" name="id" value="<?php echo $category->id; ?>">
					<button class="btn btn-danger" type="submit">Supprimer</button>
				</form>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>