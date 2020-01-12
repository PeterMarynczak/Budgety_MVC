<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\User;
use \App\Models\Profile_m;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
{
    
    
    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }
    
    
    /**
     * Show the profile
     *
     * @return void
     */
    public function showAction()
    {

      $profile = new Profile_m;
      $id = $_SESSION['user_id'];

      $arg['payment'] = $profile->getPaymentMethods($id);
      $arg['expense'] = $profile->getExpensesCategories($id);
      $arg['income'] = $profile->getIncomeCategories($id);
      $arg['user'] = $this->user;

       View::renderTemplate('Profile/show.html', $arg);
    }
    
    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }
    
    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {
            
            Flash::addMessage('Zmiany zapisano');
            
            $this->redirect('/profile/show');
            
        } else {
            
            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);
        }
    }

    /**
     * delete the profile
     *
     * @return void
     */
    public function delete()
    {
        User::deleteUser();
        Flash::addMessage('Twoje konto zostało usunięte');
        $this->redirect('/login');
    }


    public function addMethodAction()
    {

        $newMethod = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newMethod->saveMethod($id)) {

            Flash::addMessage('Metodę dodano pomyślnie');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się dodać nowej metody, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
        }
    }

    public function addCategoryIncome()
    {
        $newCategory = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newCategory->saveIncomeCategory($id)) {

            Flash::addMessage('Kategorię dodano pomyślnie');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się dodać nowej kategorii, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
        }
    }

    public function addCategoryExpense()
    {
        $newCategory = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if(isset($_POST['price']))
        {
          $price = $_POST['price'];
        }
        else 
        {
          $price = 0;
        }

        if ($newCategory->saveExpenseCategory($id, $price)) {

            Flash::addMessage('Kategorię dodano pomyślnie');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się dodać nowej kategorii, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
        }
    }

    public function updateMethodAction()
    {

        $newMethod = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newMethod->updateMethod($id)) {
            //echo '<pre>' , var_dump($newMethod) , '</pre>';
            Flash::addMessage('Nazwę metody zmieniono pomyślnie');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się zmienić nazwy metody, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
            //View::renderTemplate('/');
        }
    }

    public function updateIncomeCategory()
    {
        $newCategory = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newCategory->updateIncomeCategory($id)) {

            Flash::addMessage('Nazwę kategorii zmieniono pomyślnie');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się zmienić nazwy kategorii, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
            //View::renderTemplate('/');
        }
    }

    public function deleteMethodAction()
    {

        $newMethod = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newMethod->deleteMethod($id)) {

            Flash::addMessage('Metoda została usunięta');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się usunąć metody, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
        }
    }

    public function deleteIncomeAction()
    {

        $newIncome = new Profile_m($_POST);
        $id = $_SESSION['user_id'];

        if ($newIncome->deleteIncomeCategory($id)) {

            Flash::addMessage('Kategoria została usunięta');
            $this->redirect('/profile/show');

        } else {

            Flash::addMessage('Nie udało się usunąć kategorii, spróbuj ponownie', Flash::WARNING);
            $this->redirect('/profile/show');
        }
    }

}


















































