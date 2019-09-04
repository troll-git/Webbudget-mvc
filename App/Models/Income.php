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
        
    
}