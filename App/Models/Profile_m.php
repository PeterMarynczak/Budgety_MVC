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
class Profile_m extends \Core\Model
{

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public $errors = [];

    public function getLimit($id, $expenseCategory)
    {

        $sql = 'SELECT limit_value FROM expenses_category_assigned_to_users
                WHERE name = :expenseCategory AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':expenseCategory', $expenseCategory, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $price = $row['limit_value'];
        }
        
        return $price;
    }

    public function getExpenseId($id, $expenseCategory)
    {

        $sql = 'SELECT id FROM expenses_category_assigned_to_users
                WHERE name = :expenseCategory AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':expenseCategory', $expenseCategory, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $price = $row['id'];
        }
        
        return $price;
    }

    public function getOtherIncomeIdAssignedToUSer($id)
    {
        $category = "Inne";

        $sql = 'SELECT id FROM incomes_category_assigned_to_users
                WHERE name = :category AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $price = $row['id'];
        }
        
        return $price;
    }




    public function getSumOfExpenses($id, $expenseId)
    {
        $dayFirst = date('Y-m-01');
        $dayLast = date('Y-m-t');

        $sql = 'SELECT SUM(amount) 
                FROM expenses 
                WHERE expense_category_assigned_to_user_id = :expenseId
                AND user_id = :id
                AND date_of_expense >= :dayFirst 
                AND date_of_expense <= :dayLast';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':expenseId', $expenseId, PDO::PARAM_STR);
        $stmt->bindValue(':dayFirst', $dayFirst, PDO::PARAM_STR);
        $stmt->bindValue(':dayLast', $dayLast, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $price = $row['SUM(amount)'];
            if ($price == "")
            $price = 0;
        }
        
        return $price;
    }




    public function getPaymentMethods($id)
    {
        $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       
    }

    public function getExpensesCategories($id)
    {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       
    }

    public function getIncomeCategories($id)
    {
        $sql = 'SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
       
    }

    public function saveMethod($id)
    {
        $methodName = ucfirst($this->methodName);

        $this->methodExists($methodName, $id);

        if (empty($this->errors)) {

            $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name)
                    VALUES (:id, :methodName)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':methodName', $methodName, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function saveIncomeCategory($id) 
    {
        $categoryName = ucfirst($this->categoryName);

        $this->categoryIncomeExists($categoryName, $id);

        if (empty($this->errors)) {

            $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
                    VALUES (:id, :categoryName)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function saveExpenseCategory($id, $price)
    {
        $categoryName = ucfirst($this->categoryName);

        if ($price != 0) 
        {
        $this->validate($price);
        }

        $this->categoryExpenseExists($categoryName, $id);

        if (empty($this->errors)) {

            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name, limit_value)
                    VALUES (:id, :categoryName, :price)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function updateMethod($id)
    {
        $changeMethod = ucfirst($this->changeMethod);

        $this->methodExists($changeMethod, $id);

        if (empty($this->errors)) {

            $sql = 'UPDATE payment_methods_assigned_to_users 
                    SET name = :changeMethod 
                    WHERE payment_methods_assigned_to_users.id = :methodID';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':changeMethod', $changeMethod, PDO::PARAM_STR);
            $stmt->bindValue(':methodID', $this->methodID, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function updateIncomeCategory($id)
    {
        $changeIncomeCategory = ucfirst($this->changeIncomeCategory);

        $this->methodExists($changeIncomeCategory, $id);

        if (empty($this->errors)) {

            $sql = 'UPDATE incomes_category_assigned_to_users 
                    SET name = :changeIncomeCategory 
                    WHERE incomes_category_assigned_to_users.id = :incomeID';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':changeIncomeCategory', $changeIncomeCategory, PDO::PARAM_STR);
            $stmt->bindValue(':incomeID', $this->incomeID, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function updateExpenseCategory($id, $price)
    {
        $categoryName = ucfirst($this->changeExpenseCategory);

        if ($price != 0) 
        {
        $this->validate($price);
        }

        if (empty($this->errors)) {

            $sql = 'UPDATE expenses_category_assigned_to_users 
                    SET name = :categoryName, limit_value = :price 
                    WHERE expenses_category_assigned_to_users.id = :expenseID';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
            $stmt->bindValue(':expenseID', $this->expenseID, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            

            return $stmt->execute();
        }
        return false;
    }

    public function deleteMethod($id)
    {
        $methodDelete = ucfirst($this->methodDelete);

            $sql = 'DELETE FROM payment_methods_assigned_to_users 
                    WHERE payment_methods_assigned_to_users.id = :methodID';
                    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':methodID', $this->methodID, PDO::PARAM_STR);

            if ($stmt->execute()) 
                return true;
            else 
                return false;

    }

    public function deleteIncomeCategory($id, $OtherCategoryId)
    {
            $incomeDelete = ucfirst($this->incomeDelete);

            $sql = 'UPDATE incomes 
                    SET income_category_assigned_to_user_id = :OtherCategoryId 
                    WHERE income_category_assigned_to_user_id = :incomeID 
                    AND user_id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':incomeID', $this->incomeID, PDO::PARAM_STR);
            $stmt->bindValue(':OtherCategoryId', $OtherCategoryId, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();


            $sql = 'DELETE FROM incomes_category_assigned_to_users 
                    WHERE incomes_category_assigned_to_users.id = :incomeID';
                    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':incomeID', $this->incomeID, PDO::PARAM_STR);

            if ($stmt->execute()) 
                return true;
            else 
                return false;

    }

    public function deleteExpenseCategory($id)
    {
        $expenseDelete = ucfirst($this->expenseDelete);

            $sql = 'DELETE FROM expenses_category_assigned_to_users 
                    WHERE expenses_category_assigned_to_users.id = :expenseID';
                    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':expenseID', $this->expenseID, PDO::PARAM_STR);

            if ($stmt->execute()) 
                return true;
            else 
                return false;

    }

    public function methodExists($methodName, $id)
    {
        $sql = 'SELECT * FROM payment_methods_assigned_to_users 
        WHERE name = :methodName 
        AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':methodName', $methodName, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count > 0) 
        {
            $this->errors[] = 'Taka metoda już istnieje';
        }
    }


    public function categoryIncomeExists($categoryName, $id)
    {
        $sql = 'SELECT * FROM incomes_category_assigned_to_users 
        WHERE name = :categoryName 
        AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count > 0) 
        {
            $this->errors[] = 'Taka kategoria już istnieje';
        }
    }

    public function categoryExpenseExists($categoryName, $id)
    {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users 
        WHERE name = :categoryName 
        AND user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count > 0) 
        {
            $this->errors[] = 'Taka kategoria już istnieje';
        }
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


// ["methodDelete"]=>
//   string(11) "Gotóweczka"
//   ["methodID"]=>
//   string(1) "7"
// }