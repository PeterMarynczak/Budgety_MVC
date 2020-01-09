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

   public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function getIncomes($dayFirst, $dayLast, $id)
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

    public function getExpenses($dayFirst, $dayLast, $id)
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

    public function showBalanceExpense($dayFirst, $dayLast, $id) {

        if (empty($this->errors)) {

            $expensePieChart = "SELECT ec.name, SUM(e.amount) 
            FROM expenses AS e, expenses_category_assigned_to_users AS ec 
            WHERE e.user_id=:id 
            AND ec.user_id=:id 
            AND ec.id=e.expense_category_assigned_to_user_id 
            AND e.date_of_expense 
            BETWEEN :dayFirst 
            AND :dayLast
            GROUP BY expense_category_assigned_to_user_id";

            $db = static::getDB();
            $stmt = $db->prepare($expensePieChart);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':dayFirst', $dayFirst, PDO::PARAM_STR);
            $stmt->bindValue(':dayLast', $dayLast, PDO::PARAM_STR);
            
            $stmt->execute();

            $expensePie = array();
            $expenseResult = $stmt->fetchAll(\PDO::FETCH_OBJ);

            $expensesArray = json_decode(json_encode($expenseResult), True);

            foreach ($expensesArray as $expenseChart) {
                array_push($expensePie, array("label"=>$expenseChart['name'], "y"=>$expenseChart['SUM(e.amount)']));
            }

            json_encode($expensePie, JSON_NUMERIC_CHECK);

            return $expensesArray;

        } else {

            return false;
        }
    }

    public function showBalanceIncome($dayFirst, $dayLast, $id) {

        if (empty($this->errors)) {

            $incomePieChart = "SELECT ec.name, SUM(e.amount) 
            FROM incomes AS e, incomes_category_assigned_to_users AS ec 
            WHERE e.user_id=:id 
            AND ec.user_id=:id 
            AND ec.id=e.income_category_assigned_to_user_id 
            AND e.date_of_income 
            BETWEEN :dayFirst 
            AND :dayLast
            GROUP BY income_category_assigned_to_user_id";

            $db = static::getDB();
            $stmt = $db->prepare($incomePieChart);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':dayFirst', $dayFirst, PDO::PARAM_STR);
            $stmt->bindValue(':dayLast', $dayLast, PDO::PARAM_STR);
            
            $stmt->execute();

            $incomePie = array();
            $expenseResult = $stmt->fetchAll(\PDO::FETCH_OBJ);

            $incomeArray = json_decode(json_encode($expenseResult), True);

            foreach ($incomeArray as $incomeChart) {
                array_push($incomePie, array("label"=>$incomeChart['name'], "y"=>$incomeChart['SUM(e.amount)']));
            }

            json_encode($incomePie, JSON_NUMERIC_CHECK);

            return $incomeArray;

        } else {

            return false;
        }
    }

    public function show()
    {

    }

    public function validate()
    {
       
	}

}

