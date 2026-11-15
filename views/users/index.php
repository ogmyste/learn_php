<?php include __DIR__ . '/../partials/header.php'; ?>
<?php $_SESSION['users_csrf'] ??= bin2hex(random_bytes(32)); ?>
<main class="container">
    <h1>Users</h1>
    <a href="/admin/users/create" class="btn btn-primary mb-3">New user</a>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int) $user->id ?></td>
                        <td><?= htmlspecialchars($user->name) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= htmlspecialchars($user->created_at ?? '') ?></td>
                        <td><?= htmlspecialchars($user->updated_at ?? '') ?></td>
                        <td>
                            <a href="/admin/users/view?id=<?= (int) $user->id ?>" class="btn btn-info btn-sm">View</a>
                            <a href="/admin/users/edit?id=<?= (int) $user->id ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/admin/users/delete?id=<?= (int) $user->id ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['users_csrf']) ?>">
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$users): ?>
                    <tr><td colspan="6">No users yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
