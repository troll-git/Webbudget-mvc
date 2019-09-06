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
}