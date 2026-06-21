<?php

namespace App\Controllers;

use CodeIgniter\Shield\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $users = db_connect()
            ->table('users u')
            ->select('u.id, u.active, u.created_at, i.secret as email')
            ->join('auth_identities i', "i.user_id = u.id AND i.type = 'email_password'")
            ->where('u.deleted_at IS NULL')
            ->orderBy('u.created_at', 'ASC')
            ->get()->getResultArray();

        return view('users/index', ['users' => $users]);
    }

    public function new()
    {
        return view('users/create', ['errors' => [], 'old' => []]);
    }

    public function create()
    {
        $email           = trim($this->request->getPost('email'));
        $password        = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        $errors = [];

        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email address is required.';
        }
        if (empty($password) || strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }

        if (empty($errors)) {
            $taken = db_connect()->table('auth_identities')
                ->where('type', 'email_password')
                ->where('secret', $email)
                ->countAllResults();
            if ($taken > 0) {
                $errors['email'] = 'An account with that email already exists.';
            }
        }

        if (!empty($errors)) {
            return view('users/create', ['errors' => $errors, 'old' => ['email' => $email]]);
        }

        $userProvider = model(UserModel::class);
        $user = $userProvider->createNewUser(['email' => $email, 'password' => $password, 'active' => 0]);
        $userProvider->save($user);

        $user = $userProvider->findById($userProvider->getInsertID());
        $userProvider->addToDefaultGroup($user);
        $user->activate();
        $userProvider->save($user);

        return redirect()->to('/users')->with('success', 'User ' . esc($email) . ' added.');
    }

    public function toggleActive($id)
    {
        $userProvider = model(UserModel::class);
        $user = $userProvider->findById($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ((int) $id === (int) auth()->id() && $user->active) {
            return redirect()->to('/users')->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->active) {
            $user->deactivate();
            $action = 'deactivated';
        } else {
            $user->activate();
            $action = 'activated';
        }
        $userProvider->save($user);

        return redirect()->to('/users')->with('success', 'User ' . esc($user->email) . ' ' . $action . '.');
    }
}
