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

namespace Geekish\Gedcom\Parser\Fam;

class Slgs extends \Geekish\Gedcom\Parser\Component
{
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $slgs = new \Geekish\Gedcom\Record\Fam\Slgs();

        $parser->forward();

        while (! $parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();

                break;
            }

            switch ($recordType) {
                case 'STAT':
                    $stat = \Geekish\Gedcom\Parser\Fam\Slgs\Stat::parse($parser);
                    $slgs->setStat($stat);

                    break;
                case 'DATE':
                    $slgs->setDate(trim((string) $record[2]));

                    break;
                case 'PLAC':
                    $slgs->setPlac(trim((string) $record[2]));

                    break;
                case 'TEMP':
                    $slgs->setTemp(trim((string) $record[2]));

                    break;
                case 'SOUR':
                    $sour = \Geekish\Gedcom\Parser\SourRef::parse($parser);
                    $slgs->addSour($sour);

                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $slgs->addNote($note);
                    }

                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $slgs;
    }
}
