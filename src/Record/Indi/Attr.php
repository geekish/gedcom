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

namespace Geekish\Gedcom\Record\Indi;

use Geekish\Gedcom\Record\Noteable;
use Geekish\Gedcom\Record\Objectable;
use Geekish\Gedcom\Record\Sourceable;

class Attr extends \Geekish\Gedcom\Record\Indi\Even implements Sourceable, Noteable, Objectable
{
    protected $type;
    protected $_attr;

    protected $sour = [];

    protected $note = [];

    protected $obje = [];

    public function addSour($sour = [])
    {
        $this->sour[] = $sour;
    }

    public function addNote($note = [])
    {
        $this->note[] = $note;
    }

    public function addObje($obje = [])
    {
        $this->obje[] = $obje;
    }
}
