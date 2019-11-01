<?php

namespace App\Controllers;

use \Core\View;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Items extends \Core\Controller
class Income extends Authenticated
{

    /**
     * Items index
     *
     * @return void
     */
    public function addAction()
    {
        View::renderTemplate('Income/add.html');
    }

}
