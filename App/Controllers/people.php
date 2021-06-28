<?php

namespace App\Controllers;

use Core\View;

class People extends \Core\Controller
{
    // public function showAction()
    // {
    //     View::renderTemplate('/people/index.html');
    // }

    public function searchAction()
    {
        $searched = $_GET['search'];
        View::renderTemplate('/people/index.html', [
            'searched' => $searched
        ]);
    }


}