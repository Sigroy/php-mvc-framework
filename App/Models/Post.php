<?php

namespace MVCFramework\App\Models;

class Post extends \MVCFramework\Core\Model
{

    /**
     * Get all the posts as an associative array
     *
     * @return array
     */
    public static function getAll(): array
    {

        try {
            $db = static::getDB();
            $statement = $db->query('SELECT id, title, content FROM posts
                                          ORDER BY created_at;');
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

    }

}