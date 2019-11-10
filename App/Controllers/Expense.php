<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\AddExpense;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Expense extends Authenticated
{

	 /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Expense/new.html');
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

        $AddExpense = new AddExpense($_POST);

        if ($AddExpense->save($id)) {

        	//echo '<pre>' , var_dump($AddExpense) , '</pre>';
        	Flash::addMessage('Wydatek dodano pomyślnie');
            $this->redirect('/');

        } else {

        	Flash::addMessage('Nie udało się dodać wydatku, spróbuj ponownie', Flash::WARNING);
            View::renderTemplate('Expense/new.html');
        }

        //echo "<pre>", var_dump($income), "</pre>";
        //Flash::addMessage('Przychód dodano pomyślnie');
    }

}
