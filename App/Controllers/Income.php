<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\User;
use PDO;

class Income extends Authenticated
{
    public function incomeAction()
    {
        $user= new User();
        $result=[];
        $result['income_cat']=$user->getExpenseCategories();
        View::renderTemplate('Main/income.html',$result);
        
    }


}