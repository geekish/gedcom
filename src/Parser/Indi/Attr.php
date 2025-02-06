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

namespace Geekish\Gedcom\Parser\Indi;

abstract class Attr extends \Geekish\Gedcom\Parser\Component
{
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $className = '\\Geekish\Gedcom\\Record\\Indi\\'.ucfirst(strtolower(trim((string) $record[1])));
            $attr = new $className();

            $attr->setType(trim((string) $record[1]));
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        if (isset($record[2])) {
            $attr->setAttr(trim((string) $record[2]));
        }

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'TYPE':
                    $attr->setType(trim((string) $record[2]));
                    break;
                case 'DATE':
                    $attr->setDate(trim((string) $record[2]));
                    break;
                case 'PLAC':
                    $plac = \Geekish\Gedcom\Parser\Indi\Even\Plac::parse($parser);
                    $attr->setPlac($plac);
                    break;
                case 'ADDR':
                    $attr->setAddr(\Geekish\Gedcom\Parser\Addr::parse($parser));
                    break;
                case 'PHON':
                    $phone = \Geekish\Gedcom\Parser\Phon::parse($parser);
                    $attr->addPhon($phone);
                    break;
                case 'CAUS':
                    $attr->setCaus(trim((string) $record[2]));
                    break;
                case 'AGE':
                    $attr->setAge(trim((string) $record[2]));
                    break;
                case 'AGNC':
                    $attr->setAgnc(trim((string) $record[2]));
                    break;
                case 'SOUR':
                    $sour = \Geekish\Gedcom\Parser\SourRef::parse($parser);
                    $attr->addSour($sour);
                    break;
                case 'OBJE':
                    $obje = \Geekish\Gedcom\Parser\ObjeRef::parse($parser);
                    $attr->addObje($obje);
                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $attr->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $attr;
    }
}
