<?php

namespace repository;
use cache\MovieCache;

class MovieRepository
{
    private $movieCache;

    public function __construct(MovieCache $movieCache)
    {
        $this->movieCache = $movieCache;
    }

    public function getMovies()
    {
        $cache = $this->movieCache->connect();
        $movies = $cache->get("movies");
        if(!$movies){
            $movies = $this->movieCache->getMovies();
            $cache->setex("movies",60,json_encode($movies));
            header("X-Cache-Status: MISS");
            return $movies;
        }

        header("X-Cache-Status: HIT");
        return json_decode($movies,true);
    }

    public function getMovie($id)
    {
        $cache = $this->movieCache->connect();
        $movies = $cache->get("movie_".$id);
        if(!$movies){
            $movie =  $this->movieCache->getMovie($id);
            $movies[] = $movie;
            $cache->setex("movie_".$id,60,json_encode($movies));
            header("X-Cache-Status: MISS");
            return $movie;
        }

        $movies = json_decode($movies,true);
        foreach ($movies as $movie) {
            if (isset($movie['id']))
            {
                if ($movie['id'] === $id) {
                    header("X-Cache-Status: HIT");
                    return $movie;
                }
            }
        }

        return [
            "error" => "No data found"
        ];
    }

    public function createMovie($requestBody)
    {
        return $this->movieCache->createMovie($requestBody);
    }

}