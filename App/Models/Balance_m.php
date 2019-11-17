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
class Balance_m extends \Core\Model
{

   // public function __construct($day1, $day2)
    //{
    //        $this->day1st = $day1;
    //        $this->day2nd = $day2;
   // }
    
    public static function getIncomes($dayFirst, $dayLast, $id)
    {

        $sql = 'SELECT i.amount, i.date_of_income, ic.name
        FROM incomes i, incomes_category_assigned_to_users ic
        WHERE i.user_id = :id
        AND ic.id = i.income_category_assigned_to_user_id 
        AND date_of_income >= :dayFirst 
        AND date_of_income <= :dayLast ORDER BY i.date_of_income ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':dayFirst', $dayFirst, PDO::PARAM_STR);
        $stmt->bindValue(':dayLast', $dayLast, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getExpenses($dayFirst, $dayLast, $id)
    {

        $sql = 'SELECT e.amount, e.date_of_expense, ec.name
        FROM expenses e, expenses_category_assigned_to_users ec
        WHERE e.user_id = :id
        AND ec.id = e.expense_category_assigned_to_user_id 
        AND date_of_expense >= :dayFirst 
        AND date_of_expense <= :dayLast ORDER BY e.date_of_expense ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':dayFirst', $dayFirst, PDO::PARAM_STR);
        $stmt->bindValue(':dayLast', $dayLast, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }



    public function show()
    {

    }

    public function validate()
    {
       
	}

}

