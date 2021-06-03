<?php

namespace App\Controllers;

use App\Flash;
use App\Models\User;
use App\Models\UserPost;
use Core\View;


class Post extends \Core\Controller
{

    public function addAction()
    {
        $this->requireLogin();
        
        View::renderTemplate('post/new.html');
        
    }

    public function createAction()
    {
        $userPost = new UserPost($_POST);
        if ($userPost->savePost()) 
        {
            $this->redirect('/'); 
        }
        else
        {
            View::renderTemplate('post/new.html', [
                'userPost' => $userPost
            ]);
        }   
    }

    public function showAction()
    {
        $postModel = new UserPost();
        $postDetails = $postModel->getPost();
        View::renderTemplate('post/index.html',[
            'postDetails' => $postDetails
        ]);

        var_dump($postDetails);
        
    }

}