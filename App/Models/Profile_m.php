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

    public function saveMethod($id)
    {
        $methodName = $this->methodName;
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

    public function updateMethod($id)
    {
        $changeMethod = $this->changeMethod;
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

    public function deleteMethod($id)
    {
        $methodDelete = $this->methodDelete;
        $methodDelete = ucfirst($this->methodDelete);

        //$this->methodExists($methodDelete, $id);

        //if (empty($this->errors)) {

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

        if ($count > 0) {
            $this->errors[] = 'Taka kategoria już istnieje';
            }
    }

}


// ["methodDelete"]=>
//   string(11) "Gotóweczka"
//   ["methodID"]=>
//   string(1) "7"
// }