<?php

namespace App\Models;

//use App\Token;
use App\Auth;
use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Balance extends \Core\Model
{
    /*public function __construct($data=[])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };

        
        //View::renderTemplate('Signup/success.html');
    }*/

    public static function getRange(){
        $range=[];
        if (isset($_POST['balance'])){
            $balancerange = $_POST['balance'];
            if ($balancerange=="bm"){
                //obecny miesiac
                $date_from=date("Y-m-01");
                $date_to=date("Y-m-d");
            }
            else if ($balancerange=="pm"){
                //poprzedni miesiac
                $date = date_create();
                //$date_to= mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
                $date_from=date('Y-m-d', strtotime("first day of previous month"));
                $date_to=date('Y-m-d', strtotime("last day of previous month"));
                
            }
            else if ($balancerange=="br"){
                //biezacy rok
                $date_from=date("Y-01-01");
                $date_to=date("Y-m-d");
            }
            else if ($balancerange=="ns"){
                //niestandardowe
                $date_from=$_POST['date_from'];
                $date_to=$_POST['date_to'];
            }
            
    }
    if (!isset($_POST['balance'])){
        $date_from=date("Y-m-01");
        $date_to=date("Y-m-d");
    }
    $range[]=$date_from;
    $range[]=$date_to;
    return $range;
    }
    public static function getIncomes()
    {
        $db=static::getDB();
        $daterange=Balance::getRange();
        $queryIncome=$db->prepare("SELECT income_category_assigned_to_user_id,SUM(amount) from public.incomes where user_id=:user_id and date_of_income BETWEEN :date_from and :date_to GROUP BY income_category_assigned_to_user_id");
        $queryIncome->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $queryIncome->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        $queryIncome->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        $queryIncome->execute();
        $queryIncomeSum=$db->prepare("SELECT SUM(amount) from public.incomes where user_id=:user_id and date_of_income BETWEEN :date_from and :date_to");
        $queryIncomeSum->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $queryIncomeSum->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        $queryIncomeSum->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        $queryIncomeSum->execute();
        $incomesSumAll=$queryIncomeSum->fetchAll();
        $incomesTable=$queryIncome->fetchAll();
        $incomeSums=array_fill(0,4,0);
        for ($i = 0; $i <= count($incomeSums); $i++) {
        foreach ($incomesTable as $income){
            if ($i==($income['income_category_assigned_to_user_id']-1))
                $incomeSums[$i]=$income['sum'];
        }

        }
        return $incomeSums;

    }
    public static function getExpenses()
    {
        $db=static::getDB();
        $daterange=Balance::getRange();
        $queryExpSum=$db->prepare("SELECT SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to");
        $queryExpSum->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $queryExpSum->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        $queryExpSum->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        $queryExpSum->execute();
        $expSumAll=$queryExpSum->fetchAll();
        $queryExp=$db->prepare("SELECT expense_category_assigned_to_user_id,SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to GROUP BY expense_category_assigned_to_user_id");
        $queryExp->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $queryExp->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        $queryExp->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        $queryExp->execute();
        $expTable=$queryExp->fetchAll();
        $expSums=array_fill(0,17,0);
        for ($i = 0; $i <= count($expSums); $i++) {
            foreach ($expTable as $expense){
                if ($i==($expense['expense_category_assigned_to_user_id']-1))
                $expSums[$i]=$expense['sum'];
        }
    }
        return $expSums;

    }
    
}