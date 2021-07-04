<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserPost;
use Core\View;

class People extends \Core\Controller
{
    // public function showAction()
    // {
    //     View::renderTemplate('/people/index.html');
    // }

    public function searchAction()
    {
        if(isset($_GET['search']) && $_GET['search'] != null)
        {
            $searched = $_GET['search'];
            $user_details = User::search($searched);
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

    //used for externally viewing profile only
    public function profilevisitAction()
    {
        if(isset($_GET['id']) && User::findByID($_GET['id']) )
        {
            $user = User::findByID($_GET['id']);
            $post = UserPost::getPostByUserId($_GET['id']);
            View::renderTemplate('Profile/visit.html', [
                'user' => $user,
                'user_posts' => $post
            ]);
        }
        else
        {
            View::renderTemplate('404.html');
        }
    }


}