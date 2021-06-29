<?php

namespace App\Controllers;

use App\Models\User;
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
        $user_details = User::search($searched);
        if($searched)
        {

            View::renderTemplate('/people/index.html', [
                'searched' => $searched,
                'user' => $user_details
            ]);
        }
        else
        {
            $this->redirect('/post/show');
        }
    }


}