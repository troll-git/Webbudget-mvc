<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use \Core\View;
use \App\Models\User;

class Login extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['pass']);
        $remember_me=isset($_POST['remember_me']);
        if ($user) {
            Auth::login($user,$remember_me);
            Flash::addMessage('Pomyslnie zalogowano');
            $this->redirect('/main/new');
        } else {
            Flash::addMessage('Nieudana proba logowania, sprobuj jeszcze raz');
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email']
            ]);
        }
    }
    public function destroyAction()
    {
        Auth::logout();
        
        $this->redirect('/login/show-logout-message');
    }
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Pomyslnie wylogowano');
        $this->redirect('/');
    }
}
