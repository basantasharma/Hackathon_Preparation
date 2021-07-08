<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\User;
use App\Models\UserMessage;
use App\Models\UserPost;
use Core\View;


class Message extends \Core\Controller
{

    public function addAction()
    {
        $this->requireLogin();
        
        View::renderTemplate('Message/message.html');
        
    }

    public function createAction()
    {
        $userMessage = new UserMessage($_POST);
        if ($userMessage->saveMessage()) 
        {
            $this->redirect('/'); 
        }
        else
        {
            View::renderTemplate('Message/new.html', [
                'userPost' => $userMessage
            ]);
        }   
    }

    public function showAction()
    {
        $this->messageModel = new UserMessage();
        $msgDetails = $this->messageModel::getMessage($_GET['id']);
        View::renderTemplate('Message/message.html' ,[
            'msgDetails' => $msgDetails
        ]);

        //var_dump($postDetails);
        
    }


}