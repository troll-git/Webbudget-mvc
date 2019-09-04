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
        
    
}