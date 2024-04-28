<?php

namespace cache;

use mysqli;
use Predis\Client;

class MovieCache
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

    public function getMovies(){
        $query = "SELECT * FROM movies";
        $result = $this->mysqli->query($query);

        $movies = [];

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $movieId = $rows['id'];

                $movie["name"] = $rows["name"];
                $movie["release_date"] = date("d-m-Y", strtotime($rows["release_date"]));
                $movie["director"] = $rows["director"];

                $castQuery = "SELECT * FROM movie_casts WHERE movie_id = '$movieId'";
                $castResult = $this->mysqli->query($castQuery);

                if ($castResult->num_rows > 0)
                {
                    $castRow = $castResult->fetch_assoc();
                    $castsArray = explode(",", $castRow["cast_name"]);
                    $movie["casts"] = $castsArray;
                }
                else
                    $movie["casts"] = [];

                $ratingQuery = "SELECT * FROM movie_ratings WHERE movie_id = '$movieId'";
                $ratingResult = $this->mysqli->query($ratingQuery);

                if ($ratingResult->num_rows > 0)
                {
                    $ratingRow = $ratingResult->fetch_assoc();
                    $movie["ratings"] = array(
                        "imdb" => $ratingRow["imdb"],
                        "rotten_tomatto" => $ratingRow["rotten_tomatto"],
                    );
                }
                else
                    $movie["ratings"] = [];

                $movies[] = $movie;
            }
        }
        return $movies;
    }

    public function getMovie($id){
        $query = "SELECT * FROM movies WHERE id = '$id' ";
        $result = $this->mysqli->query($query);
        $movies = [];
        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();
            $movies["id"] = $rows["id"];
            $movies["name"] = $rows["name"];
            $query = "SELECT * FROM movie_casts WHERE movie_id = '$id'";
            $result = $this->mysqli->query($query);
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $castsArray = explode(",",$row["cast_name"]);
                $movies["casts"] = $castsArray;
                $movies["release_date"] = date("d-m-Y",strtotime($rows["release_date"]));
                $movies["director"] = $rows["director"];

                $query = "SELECT * FROM movie_ratings WHERE movie_id = '$id'";
                $result = $this->mysqli->query($query);

                if ($result->num_rows > 0)
                {
                    $ratingRow = $result->fetch_assoc();
                    $movies["ratings"] = array(
                        "imdb" => $ratingRow["imdb"],
                        "rotten_tomatto" => $ratingRow["rotten_tomatto"]
                    );
                }
                else
                    $movies = [];
            }
            else
                $movies = [];
        }
        return $movies;
    }

    public function createMovie($requestBody)
    {
        $movieName = $requestBody['name'];
        $checkMovie = "SELECT * FROM movies WHERE name = '$movieName' ";
        $result = $this->mysqli->query($checkMovie);

        if ($result->num_rows > 0)
            return false;

        $query = "INSERT INTO movies (name, release_date, director) VALUES (?, ?, ?)";
        $statement = $this->mysqli->prepare($query);
        $date = date("y-m-d",strtotime($requestBody['release_date']));
        // Bind parameters to the prepared statement
        $statement->bind_param("sss", $movieName, $date, $requestBody['director']);

        // Execute the statement
        if ($statement->execute())
        {
            $LastInsertedId = $this->mysqli->insert_id;
            $query = "INSERT INTO movie_casts (movie_id, cast_name) VALUES (?, ?)";
            $statementCasts = $this->mysqli->prepare($query);
            $casts = implode(",",$requestBody['casts']);

            // Bind parameters to the prepared statement
            $statementCasts->bind_param("is", $LastInsertedId,$casts);

            if ($statementCasts->execute())
            {
                $query = "INSERT INTO movie_ratings (movie_id, imdb,rotten_tomatto) VALUES (?, ?, ?)";
                $statementRatings = $this->mysqli->prepare($query);
                $ratings = $requestBody['ratings'];
                $imdb = $ratings['imdb'];
                $rotten_tomatto = $ratings['rotten_tomatto'];

                // Bind parameters to the prepared statement
                $statementRatings->bind_param("iss", $LastInsertedId,$imdb,$rotten_tomatto);

                if ($statementRatings->execute())
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
        else
            return false;
    }

}