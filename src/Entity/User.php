<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="user")
 */
class User extends \Core\Entity\EntityManagement
{
    use \EntityTrait;

    /**
     * @Id
     * @Column(type="integer", nullable=false, autoincrement=true)
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string", length=30, nullable=false)
     */
    public $gender;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    public $token;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $email;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $username;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $password;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $phone;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $firstname;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $lastname;

    /**
     * @Column(type="date", nullable=false)
     */
    public $birthdate;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $role;

    /**
     * @Column(type="string", length=255, nullable=false)
     */
    public $status;

    /**
     * @Column(type="date", nullable=false)
     */
    public $created_at;

    /**
     * @Column(type="date", nullable=false)
     */
    public $updated_at;

    /**
     * @Column(type="date", nullable=true)
     */
    public $deleted_at;
}
