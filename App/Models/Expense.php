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
    public function __construct($data = [])
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
        $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
        $stmt->bindValue(':expense_category_assigned_to_user_id', $this->cat_expense, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
        $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);
        $stmt->bindValue(':payment_method_assigned_to_user_id', $this->payment, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function getExpenseCat()
    {
        $sql = "SELECT name,id from expenses_category_assigned_to_users WHERE user_id =:user_id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $expensecat = $stmt->fetchAll();
        return $expensecat;
    }

    public static function getExpenses()
    {
        $db = static::getDB();
        $daterange = Balance::getRange();
        $expensecat = Expense::getExpenseCat();
        //$queryExpSum=$db->prepare("SELECT SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to");
        //$queryExpSum->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        //$queryExpSum->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        //$queryExpSum->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        //$queryExpSum->execute();
        //$expSumAll=$queryExpSum->fetchAll();
        $queryExp = $db->prepare("SELECT expense_category_assigned_to_user_id,SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to GROUP BY expense_category_assigned_to_user_id");
        $queryExp->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $queryExp->bindValue(':date_from', $daterange[0], PDO::PARAM_STR);
        $queryExp->bindValue(':date_to', $daterange[1], PDO::PARAM_STR);
        $queryExp->execute();
        $expTable = $queryExp->fetchAll();

        foreach ($expTable as &$expense) {
            foreach ($expensecat as $cat) {
                if ($expense['expense_category_assigned_to_user_id'] == $cat['id']) {
                    $expense['cat_name'] = $cat['name'];
                }
            }
        }

        return $expTable;
    }
    public static function getExpensesMonth()
    {
        $db = static::getDB();
        $daterange = Balance::getMonthRange();
        $expensecat = Expense::getExpenseCat();
        //$queryExpSum=$db->prepare("SELECT SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to");
        //$queryExpSum->bindValue(':user_id',$_SESSION['user_id'],PDO::PARAM_INT);
        //$queryExpSum->bindValue(':date_from',$daterange[0],PDO::PARAM_STR);
        //$queryExpSum->bindValue(':date_to',$daterange[1],PDO::PARAM_STR);
        //$queryExpSum->execute();
        //$expSumAll=$queryExpSum->fetchAll();
        $queryExp = $db->prepare("SELECT expense_category_assigned_to_user_id,SUM(amount) from public.expenses where user_id=:user_id and date_of_expense BETWEEN :date_from and :date_to GROUP BY expense_category_assigned_to_user_id");
        $queryExp->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $queryExp->bindValue(':date_from', $daterange[0], PDO::PARAM_STR);
        $queryExp->bindValue(':date_to', $daterange[1], PDO::PARAM_STR);
        $queryExp->execute();
        $expTable = $queryExp->fetchAll();

        foreach ($expTable as &$expense) {
            foreach ($expensecat as $cat) {
                if ($expense['expense_category_assigned_to_user_id'] == $cat['id']) {
                    $expense['cat_name'] = $cat['name'];
                }
            }
        }

        return $expTable;
    }

    public static function getSumExpenses($expenses_array)
    {
        $sum_exp = 0;
        foreach ($expenses_array as $expense) {
            $sum_exp = $sum_exp + $expense['sum'];
        }
        return $sum_exp;
    }
    public static function changeExpensesList($post)
    {
        $sql = "UPDATE expenses_category_assigned_to_users SET name = :name, limit_exp=:limit WHERE user_id =:user_id AND id=:id";
        if (strlen($post['limit']) == 0) {
            $limit = NULL;
        } else {
            $limit = $post['limit'];
        }
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $post['name'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_STR);
        $stmt->execute();

        //return $incomecat=$stmt->fetchAll();
    }
    public static function addExpenseCat($post)
    {
        $sql = 'INSERT INTO expenses_category_assigned_to_users VALUES (:user_id, :name, :id)';
        $sqlgetmax = 'SELECT MAX(id) FROM expenses_category_assigned_to_users WHERE user_id=:user_id';

        $db = static::getDB();
        $max = $db->prepare($sqlgetmax);
        $max->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $max->execute();
        $maxval = $max->fetch();
        $maxval = $maxval[0] + 1;
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $post['name'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $maxval, PDO::PARAM_STR);
        $stmt->execute();
        return $maxval;
        //return $incomecat=$stmt->fetchAll();
    }
    public static function removeExpenseCat($post)
    {
        $sql = 'DELETE FROM expenses_category_assigned_to_users WHERE name=:name AND user_id=:user_id';
        $sqlUpdate='UPDATE expenses SET expense_category_assigned_to_user_id = 0 WHERE user_id =:user_id AND expense_category_assigned_to_user_id = :id';
        $catname = strval($post['name']);
        $catid=strval($post['id']);
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt2 = $db->prepare($sqlUpdate);
        $stmt->bindValue(':name', $catname, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $stmt2->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt2->bindValue(':id', $catid, PDO::PARAM_INT);
        $stmt2->execute();
        return $post['name'];
    }
}
