<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Balance_m;

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

        if (isset($_POST['month'])) {

            $id = $_SESSION['user_id'];
            $month = $_POST['month'];

            if($month == "current_month"){
              
            $first_day_of_month = date('Y-m-01');
            $last_day_of_month = date('Y-m-t');
            
            } else if ($month == "last_month") {
                $first_day_of_month = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1));
                $last_day_of_month = date("Y-m-d", mktime(0, 0, 0, date("m"), 0));
            
            } else if ($month == "current_year") {
              
                $first_day_of_month = date(('Y').'-01-01');
                $last_day_of_month = date('Y') . '-12-31';
            
            } else if($month == "custom") {
                
                $first_day_of_month = $_POST['date1'];
                $last_day_of_month = $_POST['date2'];
                $first_day_of_month = date("Y-m-d", strtotime($first_day_of_month));
                $last_day_of_month = date("Y-m-d", strtotime($last_day_of_month));
            }

            $balance = new Balance_m($_POST);

            $arg['incomes'] = $balance->getIncomes($first_day_of_month, $last_day_of_month, $id);
            $arg['expenses'] = $balance->getExpenses($first_day_of_month, $last_day_of_month, $id);
            $arg['pieChart'] = $balance->showBalance($first_day_of_month, $last_day_of_month, $id);
            //echo '<pre>' , var_dump($arg['pieChart']) , '</pre>';

            View::renderTemplate('Balance/range.html', $arg); 
            
        }
    }
}
