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

class Fam extends \Geekish\Gedcom\Parser\Component
{
    protected static $_eventTypes = [
        'ANUL',
        'CENS',
        'DIV',
        'DIVF',
        'ENGA',
        'MARR',
        'MARB',
        'MARC',
        'MARL',
        'MARS',
    ];

    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $fam = new \Geekish\Gedcom\Record\Fam();
        $fam->setId($identifier);

        $parser->getGedcom()->addFam($fam);

        $parser->forward();

        while (! $parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();

                break;
            }

            switch ($recordType) {
                case 'RESN':
                    $fam->setResn(trim((string) $record[2]));

                    break;
                case 'EVEN':
                case 'ANUL':
                case 'CENS':
                case 'DIV':
                case 'DIVF':
                case 'ENGA':
                case 'MARR':
                case 'MARB':
                case 'MARC':
                case 'MARL':
                case 'MARS':
                    $className = ucfirst(strtolower($recordType));
                    $class = '\\Geekish\Gedcom\\Parser\\Fam\\' . $className;

                    $even = $class::parse($parser);
                    $fam->addEven($recordType, $even);

                    break;
                case 'HUSB':
                    $fam->setHusb($parser->normalizeIdentifier($record[2]));

                    break;
                case 'WIFE':
                    $fam->setWife($parser->normalizeIdentifier($record[2]));

                    break;
                case 'CHIL':
                    $fam->addChil($parser->normalizeIdentifier($record[2]));

                    break;
                case 'NCHI':
                    if (isset($record[2])) {
                        $fam->setNchi(trim((string) $record[2]));
                    }

                    break;
                case 'SUBM':
                    $fam->addSubm($parser->normalizeIdentifier($record[2]));

                    break;
                case 'SLGS':
                    $slgs = \Geekish\Gedcom\Parser\Fam\Slgs::parse($parser);
                    $fam->addSlgs($slgs);

                    break;
                case 'REFN':
                    $ref = \Geekish\Gedcom\Parser\Refn::parse($parser);
                    $fam->addRefn($ref);

                    break;
                case 'RIN':
                    $fam->setRin(trim((string) $record[2]));

                    break;
                case 'CHAN':
                    $chan = \Geekish\Gedcom\Parser\Chan::parse($parser);
                    $fam->setChan($chan);

                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $fam->addNote($note);
                    }

                    break;
                case 'SOUR':
                    $sour = \Geekish\Gedcom\Parser\SourRef::parse($parser);
                    $fam->addSour($sour);

                    break;
                case 'OBJE':
                    $obje = \Geekish\Gedcom\Parser\ObjeRef::parse($parser);
                    $fam->addObje($obje);

                    break;

                default:
                    if (strpos($recordType, '_') === 0) {
                        $fam->addExtensionTag($recordType, $record[2]);
                    }

                    $parser->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $parser->forward();
        }

        return $fam;
    }
}
