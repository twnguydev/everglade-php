<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="history")
 */
class History extends \Core\Entity\EntityManagement
{
    use \EntityTrait;

    /**
     * @Id
     * @Column(type="integer", nullable=false, autoincrement=true)
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="integer", autoincrement=false, nullable=false)
     */
    public $id_movie;

    /**
     * @Column(type="integer", autoincrement=false, nullable=false)
     */
    public $id_user;

    /**
     * @Column(type="datetime", nullable=false)
     */
    public $date;
}