<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="container">
    <h1>User details</h1>
    <table class="table table-striped">
        <tbody>
            <tr><th>ID</th><td><?= (int) $user->id ?></td></tr>
            <tr><th>Name</th><td><?= htmlspecialchars($user->name) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($user->email) ?></td></tr>
            <tr><th>Created</th><td><?= htmlspecialchars($user->created_at ?? '') ?></td></tr>
            <tr><th>Updated</th><td><?= htmlspecialchars($user->updated_at ?? '') ?></td></tr>
        </tbody>
    </table>
    <a href="/admin/users/edit?id=<?= (int) $user->id ?>" class="btn btn-warning">Edit</a>
    <a href="/admin/users" class="btn btn-secondary">Back to users</a>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
