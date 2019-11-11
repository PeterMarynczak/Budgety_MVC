<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
//use \App\Models\AddIncome;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Balance extends Authenticated
{

	 /**
     * Show the login page
     *
     * @return void
     */
    public function rangeAction()
    {
        View::renderTemplate('Balance/range.html');
    }

    /**
     * Items index
     *
     * @return void
     */
    public function showAction()
    {
        /*
    	if (isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
        } 

        //$AddIncome = new AddIncome($_POST);

        if ($AddIncome->save($id)) {

        	//echo '<pre>' , var_dump($AddIncome) , '</pre>';
        	Flash::addMessage('Przychód dodano pomyślnie');
            $this->redirect('/');

        } else {

        	Flash::addMessage('Nie udało się dodać przychodu, spróbuj ponownie', Flash::WARNING);
            View::renderTemplate('Income/new.html');
        }

        //echo "<pre>", var_dump($income), "</pre>";
        //Flash::addMessage('Przychód dodano pomyślnie');
    }
    */
    }
}
