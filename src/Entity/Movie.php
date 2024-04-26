<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="movie")
 */
class Movie extends \Core\Entity\EntityManagement
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
    public $title;

    /**
     * @Column(type="integer", nullable=false, autoincrement=false)
     */
    public $id_genre;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $director;

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $release_date;
}