<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\AddIncome;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Income extends Authenticated
{

	 /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Income/new.html');
    }

    /**
     * Items index
     *
     * @return void
     */
    public function addAction()
    {

    	if (isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
        } 

        $income = new Income($_POST);

        echo "<pre>", var_dump($income), "</pre>";
        //$income = User::authenticate($_POST['email'], $_POST['password']);
    }

}
