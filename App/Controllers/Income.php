<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\User;

class Income extends Authenticated
{
    public function incomeAction()
    {
        View::renderTemplate('Main/income.html');
        
    }


}