<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\User;

class Main extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Main/new.html');
        
    }


}