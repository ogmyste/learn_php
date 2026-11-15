<?php $_SESSION['users_csrf'] ??= bin2hex(random_bytes(32)); ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="<?= $user->id ? '/admin/users/edit?id=' . (int) $user->id : '/admin/users' ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['users_csrf']) ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input value="<?= htmlspecialchars($user->name ?? '') ?>" name="name" type="text" class="form-control" id="name" maxlength="255" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input value="<?= htmlspecialchars($user->email ?? '') ?>" name="email" type="email" class="form-control" id="email" maxlength="255" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password" autocomplete="new-password" aria-describedby="password-help" <?= $user->id ? '' : 'required' ?>>
        <div id="password-help" class="form-text">When editing, leave blank to keep the password. Set a password if you change the name or email. Passwords may contain at most 72 bytes.</div>
    </div>
    <div class="mb-3">
        <label for="password_confirm" class="form-label">Confirm password</label>
        <input name="password_confirm" type="password" class="form-control" id="password_confirm" autocomplete="new-password" <?= $user->id ? '' : 'required' ?>>
    </div>
    <button class="btn btn-primary" type="submit"><?= $user->id ? 'Update' : 'Create' ?></button>
    <a href="/admin/users" class="btn btn-secondary">Cancel</a>
</form>
