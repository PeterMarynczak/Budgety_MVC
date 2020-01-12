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
class AddIncome extends \Core\Model
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
        
        $price = $this->price;
        $this->validate($price);

        if (empty($this->errors)) {

			$sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
					SELECT u.id, i.id, :price, :date_income, :comment
					FROM users u, incomes_category_assigned_to_users i WHERE u.id = :id AND i.user_id = :id AND
					i.name = :category';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':price', $this->price, PDO::PARAM_STR);
            $stmt->bindValue(':date_income', $this->date_income, PDO::PARAM_STR);
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate($price)
    {
        // Price
    	$price_check = str_replace(",",".",$price); 
        $price_check = round($price_check, 2);

        if ($price_check == 0) 
        {
        	$this->errors[] = 'Wprowadzona kwota nie jest liczbą';
    	}

	}

}

