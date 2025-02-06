<?php

/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Geekish\Gedcom\Record;

/**
 * Class Repo.
 */
class Repo extends \Geekish\Gedcom\Record implements Noteable
{
    /**
     * @var string
     */
    protected $repo;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Addr
     */
    protected $addr;

    /**
     * @var array
     */
    protected $phon = [];
    /**
     * @var array
     */
    protected $email = [];
    /**
     * @var array
     */
    protected $fax = [];
    /**
     * @var array
     */
    protected $www = [];
    /**
     * @var string
     */
    protected $rin;

    /**
     * @var Chan
     */
    protected $chan;

    /**
     * @var array
     */
    protected $refn = [];

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @param null
     *
     * @return Repo
     */
    public function addPhon($phon = null)
    {
        $this->phon[] = $phon;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhon()
    {
        return $this->phon;
    }

    /**
     * @param null
     *
     * @return Repo
     */
    public function addEmail($email = null)
    {
        $this->email[] = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null
     *
     * @return Repo
     */
    public function addFax($fax = null)
    {
        $this->fax[] = $fax;

        return $this;
    }

    /**
     * @return array
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param null
     *
     * @return Repo
     */
    public function addWww($www = null)
    {
        $this->www[] = $www;

        return $this;
    }

    /**
     * @return array
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param null|\Geekish\Gedcom\Record\Refn $refn
     *
     * @return Repo
     */
    public function addRefn($refn = null)
    {
        if (empty($refn)) {
            $refn = new \Geekish\Gedcom\Record\Refn();
        }
        $this->refn[] = $refn;

        return $this;
    }

    /**
     * @return array
     */
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @param null|\Geekish\Gedcom\Record\NoteRef $note
     *
     * @return Repo
     */
    public function addNote($note = null)
    {
        if (empty($node)) {
            $note = new \Geekish\Gedcom\Record\NoteRef();
        }
        $this->note[] = $note;

        return $this;
    }

    /**
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $repo
     *
     * @return Repo
     */
    public function setRepo($repo = '')
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param string $name
     *
     * @return Repo
     */
    public function setName($name = '')
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|\Geekish\Gedcom\Record\Addr $addr
     *
     * @return Repo
     */
    public function setAddr($addr = null)
    {
        if (empty($addr)) {
            $addr = new \Geekish\Gedcom\Record\Addr();
        }
        $this->addr = $addr;

        return $this;
    }

    /**
     * @return \Geekish\Gedcom\Record\Addr
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param string $rin
     *
     * @return Repo
     */
    public function setRin($rin = '')
    {
        $this->rin = $rin;

        return $this;
    }

    /**
     * @return string
     */
    public function getRin()
    {
        return $this->rin;
    }

    /**
     * @param \Geekish\Gedcom\Record\Chan $chan
     *
     * @return Repo
     */
    public function setChan($chan = [])
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return \Geekish\Gedcom\Record\Chan
     */
    public function getChan()
    {
        return $this->chan;
    }
}
