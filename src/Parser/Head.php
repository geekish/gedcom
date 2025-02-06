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

class Head extends \Geekish\Gedcom\Parser\Component
{
    /**
     * @return \Geekish\Gedcom\Record\Head
     */
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $head = new \Geekish\Gedcom\Record\Head();

        $parser->getGedcom()->setHead($head);

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
                case 'SOUR':
                    $sour = \Geekish\Gedcom\Parser\Head\Sour::parse($parser);
                    $head->setSour($sour);

                    break;
                case 'SUBM':
                    $head->setSubm($parser->normalizeIdentifier($record[2]));

                    break;
                case 'SUBN':
                    $head->setSubn($parser->normalizeIdentifier($record[2]));

                    break;
                case 'DEST':
                    $dest = \Geekish\Gedcom\Parser\Head\Dest::parse($parser);
                    $head->setDest($dest);

                    break;
                case 'FILE':
                    $head->setFile(trim((string) $record[2]));

                    break;
                case 'COPR':
                    $head->setCopr(trim((string) $record[2]));

                    break;
                case 'LANG':
                    $head->setLang(trim((string) $record[2]));

                    break;
                case 'DATE':
                    $date = \Geekish\Gedcom\Parser\Head\Date::parse($parser);
                    $head->setDate($date);

                    break;
                case 'GEDC':
                    $gedc = \Geekish\Gedcom\Parser\Head\Gedc::parse($parser);
                    $head->setGedc($gedc);

                    break;
                case 'CHAR':
                    $char = \Geekish\Gedcom\Parser\Head\Char::parse($parser);
                    $head->setChar($char);

                    break;
                case 'PLAC':
                    $plac = \Geekish\Gedcom\Parser\Head\Plac::parse($parser);
                    $head->setPlac($plac);

                    break;
                case 'NOTE':
                    $head->setNote($parser->parseMultiLineRecord());

                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $head;
    }
}
