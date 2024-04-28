<?php


namespace model;

use repository\MovieRepository;

class MovieModel
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getMovies()
    {
        return $this->movieRepository->getMovies();
    }

    public function getMovie($id)
    {
        return $this->movieRepository->getMovie($id);
    }

    public function createMovie()
    {
        $requestBody = file_get_contents("php://input");
        $requestBody = json_decode($requestBody, true);

        if (!$this->validateParams($requestBody)) {
            http_response_code(400);

            // Return JSON response with error message
            $response = [
                'error' => 'Bad Request',
                'message' => 'Missing required parameters. Please provide name, release_date, director, casts, and ratings value'
            ];

            // Set Content-Type header to indicate JSON response
            header('Content-Type: application/json');

            // Encode the response data into JSON format and echo it
            return $response;
        }

        $result = $this->movieRepository->createMovie($requestBody);

        return [
            'data' => $result ? 'Movie Created Successfully' : "There was an error while creating Movie or Movie already exist."
        ];
    }

    public function validateParams($requestBody)
    {
        if (!isset($requestBody['name']) || !isset($requestBody['release_date']) || !isset($requestBody['director']) || !isset($requestBody['casts']) || !isset($requestBody['ratings'])) {
            return false;
        }

        return true;
    }


}