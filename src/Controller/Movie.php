<?php

namespace App\Controller;

use Core\Request;

class Movie extends \Core\Controller
{
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @view
     * @route /movie
     * @component movie
     * @middleware Auth
     * @method GET
     */
    public function movieAction()
    {
        $movies = $this->getModel('Movie')->findAll('movie');
    
        $moviesData = [];
        foreach ($movies as $movie) {
            $id = $movie->id;
            $title = $movie->title;
            $id_genre = $movie->id_genre;
            $director = $movie->director;
            $release_date = $movie->release_date;
    
            $moviesData[] = [
                'id' => $id,
                'title' => $title,
                'genre' => $this->getModel('MovieGenre')->findOneBy('movie_genre', ['id' => $id_genre])->name,
                'director' => $director,
                'release_date' => date_format(date_create($release_date), 'd/m/Y'),
                'viewers' => $this->getModel('History')->count('history', ['id_movie' => $id]) . " utilisateur" . ($this->getModel('History')->count('history', ['id_movie' => $id]) > 1 ? 's ont' : ' a') . " vu ce film",
            ];
        }
    
        return [
            'movies' => $moviesData,
        ];
    }
}
