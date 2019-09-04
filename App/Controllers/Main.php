<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\Balance;
use App\Flash;
use PDO;


class Main extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Main/new.html');
        
    }
    public function incomeAction()
    {
        View::renderTemplate('Main/income.html');
        
    }
    public function expenseAction()
    {
        View::renderTemplate('Main/expense.html');
    }

    public function balanceAction()
    {
        View::renderTemplate('Main/balance.html');
    }
    public function createIncomeAction()
    {
        $income= new Income($_POST);
        $income->save();
        Flash::addMessage('Pomyslnie dodano nowy przychod');
        $this->redirect('/main/income');

        //var_dump($_POST);
    }
    public function createExpenseAction()
    {
        $expense= new Expense($_POST);
        $expense->save();
        Flash::addMessage('Pomyslnie dodano nowy wydatek');
        $this->redirect('/main/expense');

        //var_dump($_POST);
    }    
    public function createBalanceAction()
    {
        $inc=Balance::getIncomes();
        $exp=Balance::getExpenses();
        //var_dump($IncomesTable,$ExpTable);
        View::renderTemplate('Main/balance.html');
    }
    
}


