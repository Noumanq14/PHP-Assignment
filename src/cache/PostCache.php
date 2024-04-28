<?php

namespace cache;

use mysqli;
use Predis\Client;

class PostCache
{
    private $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function connect(){
        return new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 32768,
        ]);
    }

    public function getPosts($id){
        if ($id != "")
        {
            $query = "SELECT * FROM posts WHERE id = '$id'";
            $result = $this->mysqli->query($query);
            $posts = [];
            if ($result->num_rows > 0)
                $posts[] = $result->fetch_assoc();

            return $posts;
        }
        else
        {
            $query = "SELECT * FROM posts";
            $result = $this->mysqli->query($query);
            $posts = [];
            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc())
                    $posts[] = $row;
            }

            return $posts;
        }
    }

    public function createPost($requestBody)
    {
        $postTitle = $requestBody['title'];
        $checkPost = "SELECT * FROM posts WHERE title = '$postTitle' ";
        $result = $this->mysqli->query($checkPost);

        if ($result->num_rows > 0)
            return false;

        $query = "INSERT INTO posts (title, content, author) VALUES (?, ?, ?)";
        $statement = $this->mysqli->prepare($query);

        // Bind parameters to the prepared statement
        $statement->bind_param("sss", $postTitle, $requestBody['content'], $requestBody['author']);

        // Execute the statement
        return $statement->execute();
    }

}