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
class Income extends \Core\Model
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
        $sql = 'INSERT INTO incomes VALUES (:user_id, :income_category_assigned_to_user_id,:amount,:date_of_income,:income_comment)';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id',$this->cat_income,PDO::PARAM_INT);
            $stmt->bindValue(':amount',$this->amount,PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income',$this->date,PDO::PARAM_STR);
            $stmt->bindValue(':income_comment',$this->comment,PDO::PARAM_STR);    
            return $stmt->execute();
    }

    public static function getIncomeCat()
    {
        $sql = "SELECT name,id from incomes_category_assigned_to_users WHERE user_id =:user_id";
    
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);  
        $stmt->execute();

        $incomecat=$stmt->fetchAll();
        return $incomecat;


    }
    public static function getIncomes()
    {
        $db=static::getDB();
        $daterange=Balance::getRange();
        $incomescat=Income::getIncomeCat();
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
        foreach ($incomesTable as &$income){
            foreach ($incomescat as $cat){
                if ($income['income_category_assigned_to_user_id']==$cat['id']){
                    $income['cat_name']=$cat['name'];
            }
            }  
        }
        return $incomesTable;
        //return $incomeSums;
    }

    public static function getSumIncomes($incomes_array)
    {
        $sum_inc=0;
        foreach($incomes_array as $income){
            $sum_inc= $sum_inc+$income['sum'];
        }
        return $sum_inc;
    }

        
    
}