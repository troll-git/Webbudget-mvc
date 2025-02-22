<?php

namespace App\Controllers;

use App\Flash;
use \Core\View;
use \App\Models\User;

/**
 * signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{

    /**
     * Show the signup page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }
    /**
     * Show the signup page
     *
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);
        if($user->save()){
            $user->copyCategories();
            Flash::addMessage('Rejestracja przebiegla pomyslnie');
            $this->redirect('/login/new');
        } else {

            Flash::addMessage('Nieudana rejestracja, sprobuj jeszcze raz.',Flash::WARNING);
            View::renderTemplate('Signup/new.html',[
                'user'=>$user]);
            
        }
        
        
    }
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
}
 