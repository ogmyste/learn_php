<?php

namespace App\Controllers;

use App\Models\User;

class UsersController
{
    public function index()
    {
        $title = 'Users';
        $users = User::all();
        view('users/index', compact('title', 'users'));
    }

    public function view()
    {
        $user = $this->findUser();
        if ($user) {
            view('users/view', ['title' => 'User details', 'user' => $user]);
        }
    }

    public function create()
    {
        view('users/create', ['title' => 'New user', 'user' => new User()]);
    }

    public function store()
    {
        if (!$this->validToken()) {
            return;
        }
        $user = new User();
        $errors = $this->validate($user);
        if ($errors) {
            http_response_code(422);
            return view('users/create', ['title' => 'New user', 'user' => $user, 'errors' => $errors]);
        }
        $user->save();
        redirect('/admin/users');
    }

    public function edit()
    {
        $user = $this->findUser();
        if ($user) {
            view('users/edit', ['title' => 'Edit user', 'user' => $user]);
        }
    }

    public function update()
    {
        if (!$this->validToken()) {
            return;
        }
        $user = $this->findUser();
        if (!$user) {
            return;
        }
        $errors = $this->validate($user);
        if ($errors) {
            http_response_code(422);
            return view('users/edit', ['title' => 'Edit user', 'user' => $user, 'errors' => $errors]);
        }
        $user->save();
        redirect('/admin/users');
    }

    public function delete()
    {
        if (!$this->validToken()) {
            return;
        }
        $user = $this->findUser();
        if ($user) {
            $user->delete();
            if (($_SESSION['userID'] ?? null) == $user->id) {
                unset($_SESSION['userID']);
            }
            redirect('/admin/users');
        }
    }

    private function findUser()
    {
        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $user = $id ? User::find($id) : false;
        if (!$user) {
            http_response_code(404);
            echo 'User not found';
        }
        return $user;
    }

    private function validToken()
    {
        $token = $_POST['csrf_token'] ?? null;
        if (!is_string($token) || !isset($_SESSION['users_csrf']) || !hash_equals($_SESSION['users_csrf'], $token)) {
            http_response_code(403);
            echo 'Invalid form token';
            return false;
        }
        return true;
    }

    private function validate(User $user)
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];
        if ($name === '' || strlen($name) > 255) {
            $errors[] = 'Enter a name of up to 255 bytes.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            $errors[] = 'Enter a valid email address.';
        } else {
            foreach (User::where('email', $email) as $existing) {
                if ($existing->id != $user->id) {
                    $errors[] = 'This email address is already in use.';
                    break;
                }
            }
        }
        // The existing login includes name and email in the password hash.
        if ($password === '' && (!$user->id || $name !== $user->name || $email !== $user->email)) {
            $errors[] = 'Set a password when creating a user or changing their name or email.';
        }
        if ($password !== (string) ($_POST['password_confirm'] ?? '')) {
            $errors[] = 'Passwords must match.';
        }
        if (strlen($password) > 72) {
            $errors[] = 'The password must be at most 72 bytes.';
        }
        $user->name = $name;
        $user->email = $email;
        if (!$errors && $password !== '') {
            $user->password = password_hash($password, PASSWORD_BCRYPT);
        }
        return $errors;
    }
}
