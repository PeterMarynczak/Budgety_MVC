<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Flash;

/**
 * Income model
 *
 * PHP version 7.0
 */
class AddExpense extends \Core\Model
{

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save($id)
    {
        $this->validate();

        if (empty($this->errors)) {

            $sql = 'INSERT INTO expenses(user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                SELECT u.id, e.id, p.id ,:amount, :date_expense, :comment
                FROM users u, expenses_category_assigned_to_users e, payment_methods_assigned_to_users p 
                WHERE u.id = :id 
                AND e.user_id = :id 
                AND p.user_id = :id
                AND e.name = :category
                AND p.name = :payment';


            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':payment', $this->payment, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_expense', $this->date_expense, PDO::PARAM_STR);
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate()
    {
        // Amount
        $price = str_replace(",",".",$this->amount); 
        $price = round($price, 2);

        if ($price == 0) {
            $this->errors[] = 'Wprowadzona kwota nie jest liczbą';
            }

	}

}

