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

class Even extends \Geekish\Gedcom\Parser\Component
{
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $even = new \Geekish\Gedcom\Record\Fam\Even();

        if (isset($record[1]) && strtoupper(trim((string) $record[1])) != 'EVEN') {
            $even->setType(trim((string) $record[1]));
        }

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
                case 'TYPE':
                    $even->setType(trim((string) $record[2]));

                    break;
                case 'DATE':
                    $dat = \Geekish\Gedcom\Parser\Date::parse($parser);
                    $even->setDate($dat);

                    //$even->setDate(trim($record[2]));
                    break;
                case 'PLAC':
                    $plac = \Geekish\Gedcom\Parser\Plac::parse($parser);
                    $even->setPlac($plac);

                    break;
                case 'ADDR':
                    $addr = \Geekish\Gedcom\Parser\Addr::parse($parser);
                    $even->setAddr($addr);

                    break;
                case 'PHON':
                    $phone = \Geekish\Gedcom\Parser\Phon::parse($parser);
                    $even->addPhon($phone);

                    break;
                case 'CAUS':
                    $even->setCaus(trim((string) $record[2]));

                    break;
                case 'AGE':
                    $even->setAge(trim((string) $record[2]));

                    break;
                case 'AGNC':
                    $even->setAgnc(trim((string) $record[2]));

                    break;
                case 'HUSB':
                    $husb = \Geekish\Gedcom\Parser\Fam\Even\Husb::parse($parser);
                    $even->setHusb($husb);

                    break;
                case 'WIFE':
                    $wife = \Geekish\Gedcom\Parser\Fam\Even\Wife::parse($parser);
                    $even->setWife($wife);

                    break;
                case 'SOUR':
                    $sour = \Geekish\Gedcom\Parser\SourRef::parse($parser);
                    $even->addSour($sour);

                    break;
                case 'OBJE':
                    $obje = \Geekish\Gedcom\Parser\ObjeRef::parse($parser);
                    $even->addObje($obje);

                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $even->addNote($note);
                    }

                    break;
                default:
                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $even;
    }
}
