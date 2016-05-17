<h1>Administrer les articles</h1>
<p>
	<a href="/admin/posts/add" class="btn btn-success">Ajouter</a>
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
		<?php foreach($posts as $post) { ?>
		<tr>
			<td><?php echo $post->id; ?></td>
			<td><?php echo $post->title; ?></td>
			<td>
				<a href="/admin/posts/edit?id=<?php echo $post->id; ?>" class="btn btn-primary">Editer</a>
				<form action="/admin/posts/delete" method="post" style="display: inline;">
					<input type="hidden" name="id" value="<?php echo $post->id; ?>">
					<button class="btn btn-danger" type="submit">Supprimer</button>
				</form>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>