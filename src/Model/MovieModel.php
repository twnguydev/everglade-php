<?php

namespace App\Model;

use Core\ORM;
use App\Entity\Movie;

class MovieModel extends ORM
{
    public function createMovieInstance(): Movie
    {
        return new Movie();
    }
}