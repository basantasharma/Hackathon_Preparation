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

        if (empty($this->errorsPost)) 
        {
            $sql = 'INSERT INTO user_posts (user_id, post_title, post_type, post_discription) 
            VALUES(:user_id, :post_title, :post_type, :post_discription)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
            $stmt->bindValue(':post_title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':post_type', $this->type, PDO::PARAM_STR);
            $stmt->bindValue(':post_discription', $this->details, PDO::PARAM_STR);

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

    public function getPost()
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
    

}