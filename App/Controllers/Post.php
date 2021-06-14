<?php

namespace App\Controllers;

use App\Auth;
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
        $this->postModel = new UserPost();
        $postDetails = $this->postModel::getPost();
        View::renderTemplate('post/index.html',[
            'postDetails' => $postDetails
        ]);

        //var_dump($postDetails);
        
    }
    public function EditAction()
    {
        $this->requireLogin();
        //$user = Auth::getUser();
        if(UserPost::isUserPost($_GET['post_id']))
        {
            View::renderTemplate('post/edit.html',[
                'post_details' => UserPost::getUserPostById($_GET['post_id'])
                ]);        
        }
        else
        {
            $this->redirect('/profile/show');
        }
    }

    public function StartEditAction()
    {
        $this->requireLogin();
        $edit = UserPost::updatepost($_POST, $_GET['post_id']);
        if($edit)
        {
            $this->redirect('/profile/show');
        }        
    }

    public function deletePostAction()
    {
        $this->requireLogin();
        $post_id = $_GET['post_id'];
        //$user = Auth::getUser();
        if(UserPost::isUserPost($post_id))
        {
            UserPost::deletePost($post_id);
            $this->redirect('/profile/show');      
        }
        else
        {
            View::renderTemplate('profile/show.html');
        }

    }

}