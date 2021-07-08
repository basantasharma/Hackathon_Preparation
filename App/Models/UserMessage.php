<?php

namespace App\Models;

use App\Auth;
use PDO;
use \App\Token;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class UserMessage extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errorsMessage= [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function saveMessage()
    {
        $sender = Auth::getUser();
        $this->validateMessage();
        if (empty($this->errorsMessage)) 
        {
            $sql = 'INSERT INTO message (sender,receiver,msg_text) 
            VALUES(:sender, :receiver, :msg_text)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':sender', $sender->id, PDO::PARAM_INT);
            $stmt->bindValue(':receiver', $this->data['receiver'], PDO::PARAM_INT);
            $stmt->bindValue(':msg_text', $this->data['message'], PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    public function validateMessage()
    {
        //validate message title
        if ($this->message == "") {
            $this->errorsMessage[] = 'message field is required';
        }

        

    }

    //gets all message from database, 
    //used for Post Action only
    public static function getMessage($receiver)
    {
        $sender = Auth::getUser();
        $senderid = $sender->id;
        $sql = "SELECT * FROM message WHERE sender = :sid and receiver = :rid";
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':sid', $senderid, PDO::PARAM_INT);
        $stmt->bindValue(':rid', $receiver, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    // //used when update Action is called
    // public static function updatepost($data, $post_id)
    // {
    //     //var_dump($data);
    //     $type = $data['type'];
    //     $title = $data['title'];
    //     $details = $data['details'];
    //     $sql = 'UPDATE user_posts 
    //             SET catogery = :post_type,
    //                 title = :post_title,
    //                 discription = :post_discription
    //             WHERE id = :post_id ';

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);
        
    //     $stmt->bindValue(':post_type', $type, PDO::PARAM_STR);
    //     $stmt->bindValue(':post_title', $title, PDO::PARAM_STR);
    //     $stmt->bindValue(':post_discription', $details, PDO::PARAM_STR);
    //     $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

    //     if($stmt->execute())
    //     {    
    //         return true;
    //     }
    //     else
    //         return false;
    // }

    // //used when delete Action is called
    // public static function deletePost($post_id)
    // {
    //     $sql = 'DELETE FROM user_posts WHERE post_id =:post_id';

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);
        
    //     $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

    //     $stmt->execute();
    // }

    // //used while edit Acion is called
    // public static function getUserPostById($id)
    // {
    //     $user = Auth::getUser();
    //     $sql = 'SELECT * FROM user_posts WHERE id =:post_id AND user_id = :user_id';

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':post_id', $id, PDO::PARAM_INT);
    //     $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);

    //     $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    //     $stmt->execute();
    //     return $stmt->fetch();
    // }

    // public static function isUserPost($id)
    // {
    //     $userPost = static::getUserPostById($id);
    //     if($userPost)
    //     {
    //         return true;
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // } 

    // //used for profile viewing and many more
    // public static function getPostByUserId($id)
    // {
    //     $sql = 'SELECT * FROM user_posts WHERE user_id =:user_id';

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);

    //     $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    //     $stmt->execute();
    //     return $stmt->fetchAll();
    // }

}