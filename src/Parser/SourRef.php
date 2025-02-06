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

namespace Geekish\Gedcom\Parser;

class SourRef extends \Geekish\Gedcom\Parser\Component
{
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[2])) {
            $sour = new \Geekish\Gedcom\Record\SourRef();
            $sour->setSour($parser->normalizeIdentifier($record[2]));
        } else {
            $parser->skipToNextLevel($depth);

            return null;
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
                case 'CONT':
                    $sour->setSour($sour->getSour() . "\n");

                    if (isset($record[2])) {
                        $sour->setSour($sour->getSour() . $record[2]);
                    }

                    break;
                case 'CONC':
                    if (isset($record[2])) {
                        $sour->setSour($sour->getSour() . $record[2]);
                    }

                    break;
                case 'TEXT':
                    $sour->setText($parser->parseMultiLineRecord());

                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $sour->addNote($note);
                    }

                    break;
                case 'DATA':
                    $sour->setData(\Geekish\Gedcom\Parser\SourRef\Data::parse($parser));

                    break;
                case 'QUAY':
                    $sour->setQuay(trim((string) $record[2]));

                    break;
                case 'PAGE':
                    $sour->setPage(trim((string) $record[2]));

                    break;
                case 'EVEN':
                    $even = \Geekish\Gedcom\Parser\SourRef\Even::parse($parser);
                    $sour->setEven($even);

                    break;
                case 'OBJE':
                    $obje = \Geekish\Gedcom\Parser\ObjeRef::parse($parser);
                    if ($obje) {
                        $sour->addNote($obje);
                    }

                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
