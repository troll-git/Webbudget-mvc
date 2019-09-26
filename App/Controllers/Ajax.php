<?php

namespace App\Controllers;

use App\Flash;
use \Core\View;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;
use App\Auth;

/**
 * signup controller
 *
 * PHP version 7.0
 */
class Ajax extends \Core\Controller
{
    //Incomes
    public function changeIncomesAction()
    {
        $modcat = $_POST;
        $incomecats = Income::changeIncomesList($modcat);
        echo json_encode($modcat);
    }
    public function addIncomeCatAction()
    {
        $newcat = $_POST;
        $json = Income::addIncomeCat($newcat);
        echo json_encode($json);
    }

    public function removeIncomeCatAction()
    {
        $remcat = $_POST;
        $json = Income::removeIncomeCat($remcat);
        echo json_encode($json);
    }
    //Expenses
    public function changeExpensesAction()
    {
        $modcat = $_POST;
        $expensecats = Expense::changeExpensesList($modcat);
        echo json_encode($modcat);
    }
    public function addExpenseCatAction()
    {
        $newcat = $_POST;
        $json = Expense::addExpenseCat($newcat);
        echo json_encode($json);
    }

    public function removeExpenseCatAction()
    {
        $remcat = $_POST;
        $json = Expense::removeExpenseCat($remcat);
        echo json_encode($json);
    }
    //Expenses
    public function changePayAction()
    {
        $modcat = $_POST;
        $paycats = User::changePayList($modcat);
        echo json_encode($modcat);
    }
    public function addPayCatAction()
    {
        $newcat = $_POST;
        $json = User::addPayCat($newcat);
        echo json_encode($json);
    }

    public function removePayCatAction()
    {
        $remcat = $_POST;
        $json = User::removePayCat($remcat);
        echo json_encode($json);
    }
    public function checkLimitAction()
    {
        $limitcat = $_POST;
        $json = User::checkLimit($limitcat);
        //Flash::addMessage('osiagnieto limit',FLASH::WARNING);

        echo json_encode($json);
    }
    public function changeUsernameAction()
    {
        $username = $_POST;
        $json = User::changeUsername($username);
        //Flash::addMessage('osiagnieto limit',FLASH::WARNING);

        echo json_encode($username);
    }
}
