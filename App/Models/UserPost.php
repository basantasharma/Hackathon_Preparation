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
class UserPost extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errorsPost = [];

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

    public function savePost()
    {
        $user = Auth::getUser();
        $this->validatePost();
        $date = date("Y-m-d H:i:s");

        if (empty($this->errorsPost)) 
        {
            $sql = 'INSERT INTO user_posts (user_id, title, catogery, discription, date) 
            VALUES(:user_id, :post_title, :post_type, :post_discription, :post_date)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindValue(':post_title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':post_type', $this->type, PDO::PARAM_STR);
            $stmt->bindValue(':post_discription', $this->details, PDO::PARAM_STR);
            $stmt->bindValue(':post_date', $date, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validatePost()
    {
        //validate post title
        if ($this->title == "") {
            $this->errorsPost[] = 'Title is required';
        }

        //validate discription
        if ($this->details == "") {
            $this->errorsPost[] = 'Details is required';
        }

    }

    public static function getPost()
    {
        $sql = 'SELECT up.*, u.name FROM user_posts AS up INNER JOIN users AS u
        WHERE up.user_id = u.id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //$stmt->bindValue('');
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public static function updatepost($data, $post_id)
    {
        //var_dump($data);
        $type = $data['type'];
        $title = $data['title'];
        $details = $data['details'];
        $sql = 'UPDATE user_posts 
                SET post_type = :post_type,
                    post_title = :post_title,
                    post_discription = :post_discription
                WHERE post_id = :post_id ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':post_type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':post_title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':post_discription', $details, PDO::PARAM_STR);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

        if($stmt->execute())
        {    
            return true;
        }
        else
            return false;
    }

    public static function deletePost($post_id)
    {
        $sql = 'DELETE FROM user_posts WHERE post_id =:post_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

        $stmt->execute();
    }

    public static function getUserPostById($id)
    {
        $user = Auth::getUser();
        $sql = 'SELECT * FROM user_posts WHERE id =:post_id AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':post_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetch();
    }

    public static function isUserPost($id)
    {
        $userPost = static::getUserPostById($id);
        if($userPost)
        {
            return true;
        }
        else
        {
            return false;
        }
    } 

}