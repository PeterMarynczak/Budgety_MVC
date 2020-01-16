<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\AddExpense;
use \App\Models\Profile_m;

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
        $profile = new Profile_m;
        $id = $_SESSION['user_id'];

        $arg['payment'] = $profile->getPaymentMethods($id);
        $arg['expense'] = $profile->getExpensesCategories($id);
        View::renderTemplate('Expense/new.html', $arg);
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

    }


    public function limitAction()
    {

        $profile = new Profile_m;
        $id = $_SESSION['user_id'];

        if (isset($_POST['expenseValue']) && isset($_POST['expenseCategory'])) {

            $expenseValue = $_POST['expenseValue'];
            $expenseCategory = $_POST['expenseCategory'];
            //$expenseId = $_POST['expenseId'];
            $expenseId = $profile->getExpenseId($id, $expenseCategory);

            $imit_expense = $profile->getLimit($id, $expenseCategory);

            $sum_expenses = $profile->getSumOfExpenses($id, $expenseId);


            // echo $expenseCategory;
            // echo "<br>";
            // echo $expenseValue;
            // echo "<br>";
            // echo $imit_expense;
            // echo "<br>";
            // echo $sum_expenses;

            if($imit_expense > 0) {

                $sumExpenceValue = $sum_expenses + $expenseValue;

                if ($sumExpenceValue > $imit_expense) {

                    $difference = $sumExpenceValue - $imit_expense;



echo<<<END

          <div class="alert alert-danger center-block" role="alert">
                Uważaj! </br>
                Limit dla kategorii $expenseCategory to $imit_expense zł</br>
                W tym miesiącu wydałeś już $sum_expenses zł</br>
                Wydając kwotę: $expenseValue zł, przekroczysz limit o $difference zł.
          </div>
END;

                } else {

                $amountToSpend = $imit_expense - $sum_expenses;
echo<<<END

          <div class="alert alert-success center-block" role="alert">
                Przychód może zostać dodany</br>
                W tym miesiącu wydałeś $sum_expenses zł</br>
                Nadal masz do wydania w tym miesiącu: $amountToSpend zł
          </div>
END;


                }

            }
        }
    }
}

