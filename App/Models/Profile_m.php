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

}