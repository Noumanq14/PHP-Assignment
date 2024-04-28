<?php

namespace repository;

use cache\PostCache;

class PostRepository
{
    private $postCache;

    public function __construct(PostCache $postCache)
    {
        $this->postCache = $postCache;
    }

    public function getPost($id)
    {
        $cache = $this->postCache->connect();

        if ($id != "")
        {
            $posts = $cache->get("post_".$id);
            if(!$posts){
                $posts = $this->postCache->getPosts($id);
                $cache->setex("post_".$id,60,json_encode($posts));
                header("X-Cache-Status: MISS");
                return $posts;
            }

            $posts = json_decode($posts,true);
            foreach ($posts as $post) {
                if (isset($post['id']))
                {
                    if ($post['id'] === $id) {
                        header("X-Cache-Status: HIT");
                        return $post;
                    }
                }
            }

            return [
                "error" => "No data found"
            ];
        }
        else
        {
            $posts = $cache->get("posts");
            if(!$posts){
                $posts = $this->postCache->getPosts("");
                $cache->setex("posts",60,json_encode($posts));
                header("X-Cache-Status: MISS");
                return $posts;
            }

            header("X-Cache-Status: HIT");
            return json_decode($posts,true);
        }
    }

    public function createPost($requestBody)
    {
        return $this->postCache->createPost($requestBody);
    }
}