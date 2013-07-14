<?php

/**
 * This file is part of the PackageName package
 *
 * @package    Acme
 * @subpackage DemoBundle
 * @author     Ken Hannel <Kenneth.Hannel@gmail.com>
 */

namespace Acme\DemoBundle\Entity;

/**
 * Holds user info
 */
class UserData
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $note;

    /** @var \DateTime */
    protected $time;

    /** @var integer */
    private $id;

    public function __construct()
    {
        $this->id = rand(1, 1000);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;
    }
}
