<?php

namespace App\Models;

use App\Token;
use App\Flash;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @var array
     */
    /*public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/

    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
        //View::renderTemplate('Signup/success.html');
    }
    /**
     * Save the user model to database
     *
     * @return void
     */
    public function save()
    {
        $this->validate();
        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (username, email, password)
                VALUES (:username, :email, :password_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function copyCategories()
    {
        $userData = static::findByEmail($this->email);
        $db = static::getDB();
        $qgetCopyIncomes = $db->prepare("SELECT * from public.incomes_category_default");
        $qgetCopyIncomes->execute();
        $copyIncomes = $qgetCopyIncomes->fetchAll();
        foreach ($copyIncomes as $income) {
            $qinsertIncome = $db->prepare("INSERT INTO public.incomes_category_assigned_to_users VALUES ( :user_id, :name,:id)");
            $qinsertIncome->bindValue(':user_id', $userData->id, PDO::PARAM_INT);
            $qinsertIncome->bindValue(':name', $income['name'], PDO::PARAM_STR);
            $qinsertIncome->bindValue(':id', $income['id'], PDO::PARAM_INT);
            $qinsertIncome->execute();
        }
        //copy expenses
        $qgetCopyExp = $db->prepare("SELECT * from public.expenses_category_default");
        $qgetCopyExp->execute();
        $copyExp = $qgetCopyExp->fetchAll();
        foreach ($copyExp as $exp) {
            $qinsertExp = $db->prepare("INSERT INTO public.expenses_category_assigned_to_users VALUES ( :user_id, :name,:id)");
            $qinsertExp->bindValue(':user_id', $userData->id, PDO::PARAM_INT);
            $qinsertExp->bindValue(':name', $exp['name'], PDO::PARAM_STR);
            $qinsertExp->bindValue(':id', $exp['id'], PDO::PARAM_INT);
            $qinsertExp->execute();
        }
        //copy payment methods
        $qgetCopyPay = $db->prepare("SELECT * from public.payment_methods_default");
        $qgetCopyPay->execute();
        $copyPay = $qgetCopyPay->fetchAll();
        foreach ($copyPay as $pay) {
            $qinsertPay = $db->prepare("INSERT INTO public.payment_methods_assigned_to_users VALUES ( :user_id, :name,:id)");
            $qinsertPay->bindValue(':user_id', $userData->id, PDO::PARAM_INT);
            $qinsertPay->bindValue(':name', $pay['name'], PDO::PARAM_STR);
            $qinsertPay->bindValue(':id', $pay['id'], PDO::PARAM_INT);
            $qinsertPay->execute();
        }
    }

    public function getIncomeCategories()
    {
        //$userData= static::findByEmail($this->email);
        $db = static::getDB();
        $qgetCopyIncomes = $db->prepare("SELECT * from public.incomes_category_assigned_to_users WHERE user_id = :user_id ORDER BY id");
        $qgetCopyIncomes->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $qgetCopyIncomes->execute();
        $copyIncomes = $qgetCopyIncomes->fetchAll();
        return $copyIncomes;
    }
    public function getExpenseCategories()
    {
        //$userData= static::findByEmail($this->email);
        $db = static::getDB();
        $qgetCopyExpenses = $db->prepare("SELECT * from public.expenses_category_assigned_to_users WHERE user_id = :user_id ORDER BY id");
        $qgetCopyExpenses->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $qgetCopyExpenses->execute();
        $copyExpenses = $qgetCopyExpenses->fetchAll();
        return $copyExpenses;
    }
    public function getPaymentCategories()
    {
        //$userData= static::findByEmail($this->email);
        $db = static::getDB();
        $qgetCopyPayments = $db->prepare("SELECT * from public.payment_methods_assigned_to_users WHERE user_id = :user_id ORDER BY id");
        $qgetCopyPayments->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $qgetCopyPayments->execute();
        $copyPayments = $qgetCopyPayments->fetchAll();
        return $copyPayments;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ((strlen($this->username) < 3) || (strlen($this->username) > 20)) {
            $this->errors[] = 'imie lub nazwa musi posiadac od 3 do 20 znakow';
        }

        // email address
        $emailB = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $this->email)) {
            $this->errors[] = "Adres e-mail jest niepoprawny";
        }

        if (static::emailExists($this->email)) {
            $this->errors[] = 'Ten adres juz jest w uzyciu';
        }


        // Password
        if ($this->password != $this->r_password) {
            $this->errors[] = 'Podane hasła nie są identyczne';
        }

        if ((strlen($this->password) < 4) || (strlen($this->password) > 20)) {
            $this->errors[] = 'Hasło musi posiadać od 4 do 20 znaków!';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }
    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email ';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id ';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; //30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
    public static function changePayList($post)
    {
        $sql = "UPDATE payment_methods_assigned_to_users SET name = :name WHERE user_id =:user_id AND id=:id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $post['name'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
        $stmt->execute();

        //return $incomecat=$stmt->fetchAll();
    }
    public static function addPayCat($post)
    {
        $sql = 'INSERT INTO payment_methods_assigned_to_users VALUES (:user_id, :name, :id)';
        $sqlgetmax = 'SELECT MAX(id) FROM payment_methods_assigned_to_users WHERE user_id=:user_id';

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
    public static function removePayCat($post)
    {
        $sql = 'DELETE FROM payment_methods_assigned_to_users WHERE name=:name AND user_id=:user_id';
        $catname = strval($post['name']);
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $catname, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $post['name'];
    }
    public static function checkLimit($post)
    {
        $sql = 'SELECT limit_exp FROM expenses_category_assigned_to_users WHERE id=:id AND user_id=:user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}
