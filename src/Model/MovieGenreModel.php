<?php

namespace App\Model;

use Core\ORM;
use App\Entity\MovieGenre;

class MovieGenreModel extends ORM
{
    public function createMovieGenreInstance(): MovieGenre
    {
        return new MovieGenre();
    }
}