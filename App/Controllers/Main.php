<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\Balance;
use App\Flash;
use App\Models\User;
use PDO;


class Main extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Main/new.html');
    }
    public function incomeAction()
    {
        $user = new User();
        $result = [];
        $result['income_cat'] = $user->getIncomeCategories();
        View::renderTemplate('Main/income.html', $result);
    }
    public function expenseAction()
    {
        $user = new User();
        $result = [];
        $result['expense_cat'] = $user->getExpenseCategories();
        $result['pay_cat'] = $user->getPaymentCategories();
        View::renderTemplate('Main/expense.html', $result);
    }

    public function balanceAction()
    {
        $this->createBalanceAction();
        //View::renderTemplate('Main/balance.html');
    }

    public function settingsAction()
    {
        $user = new User();
        $result = [];
        $result['income_cat'] = $user->getIncomeCategories();
        $result['expense_cat'] = $user->getExpenseCategories();
        $result['pay_cat'] = $user->getPaymentCategories();
        View::renderTemplate('Main/settings.html', $result);
    }
    public function createIncomeAction()
    {
        $income = new Income($_POST);
        $income->save();
        Flash::addMessage('Pomyslnie dodano nowy przychod', FLASH::INFO);
        $this->redirect('/main/income');

        //var_dump($_POST);
    }
    public function createExpenseAction()
    {
        $expense = new Expense($_POST);
        $expense->save();
        Flash::addMessage('Pomyslnie dodano nowy wydatek', FLASH::INFO);
        $this->redirect('/main/expense');

        //var_dump($_POST);
    }
    public function createBalanceAction()
    {
        $inc = Income::getIncomes();
        $incomecat = Income::getIncomeCat();
        $exp = Expense::getExpenses();
        $expensecat = Expense::getExpenseCat();

        $result = [];
        $result['incomes'] = $inc;
        $result['incomes_sum'] = Income::getSumIncomes($inc);
        $result['expenses'] = $exp;
        $result['expenses_sum'] = Expense::getSumExpenses($exp);
        if (empty($inc)) {
            Flash::addMessage('Brak przychodow w wybranym okresie', FLASH::INFO);
        }
        if (empty($exp)) {
            Flash::addMessage('Brak wydatkow w wybranym okresie', FLASH::INFO);
        }
        $balance = new Balance;
        $saldo = $balance->getBalance(Income::getSumIncomes($inc), Expense::getSumExpenses($exp));
        $result['balance'] = $saldo;
        if ($saldo <= 0) {
            $result['infobalance'] = 'Jesteś na minusie. Musisz oszczędzać';
        } else {
            $result['infobalance'] = 'Nieźle zarządzasz pieniędzmi. Tak trzymać!';
        }
        View::renderTemplate('Main/balance.html', $result);
    }
}
