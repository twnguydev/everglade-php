<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="movie_genre")
 */
class MovieGenre extends \Core\Entity\EntityManagement
{
    use \EntityTrait;

    /**
     * @Id
     * @Column(type="integer", nullable=false, autoincrement=true)
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $name;
}