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
class Expense extends \Core\Model
{
    public function __construct($data=[])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };

        
        //View::renderTemplate('Signup/success.html');
    }

    public function save()
    {
        $sql = 'INSERT INTO public.expenses VALUES ( :user_id,:expense_category_assigned_to_user_id,:payment_method_assigned_to_user_id,:amount,:date_of_expense,:expense_comment)';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':amount',$this->amount,PDO::PARAM_STR);
            $stmt->bindValue(':expense_category_assigned_to_user_id',$this->cat_expense,PDO::PARAM_INT);
            $stmt->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
            $stmt->bindValue(':date_of_expense',$this->date,PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment',$this->comment,PDO::PARAM_STR);    
            $stmt->bindValue(':payment_method_assigned_to_user_id',$this->payment,PDO::PARAM_INT);    

            return $stmt->execute();
    }

    public static function getExpenseCat()
    {
        $sql = "SELECT name,id from expenses_category_assigned_to_users WHERE user_id =:user_id";
    
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);  
        $stmt->execute();

        $expensecat=$stmt->fetchAll();
        return $expensecat;


    }

    public static function getExpenses()
    {
        $db=static::getDB();
        $daterange=Balance::getRange();
        $expensecat=Expense::getExpenseCat();
        //$queryExpSum=$db->prepare("SELECT SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to");
        //$queryExpSum->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        //$queryExpSum->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        //$queryExpSum->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        //$queryExpSum->execute();
        //$expSumAll=$queryExpSum->fetchAll();
        $queryExp=$db->prepare("SELECT expense_category_assigned_to_user_id,SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to GROUP BY expense_category_assigned_to_user_id");
        $queryExp->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        $queryExp->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        $queryExp->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        $queryExp->execute();
        $expTable=$queryExp->fetchAll();
        
        foreach ($expTable as &$expense){
            foreach ($expensecat as $cat){
                if ($expense['expense_category_assigned_to_user_id']==$cat['id']){
                    $expense['cat_name']=$cat['name'];
            }
            }  
        }

        return $expTable;

    }

    public static function getSumExpenses($expenses_array)
    {
        $sum_exp=0;
        foreach($expenses_array as $expense){
            $sum_exp= $sum_exp+$expense['sum'];
        }
        return $sum_exp;
    }
        
    
}