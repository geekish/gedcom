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

class Chan extends \Geekish\Gedcom\Parser\Component
{
    public static function parse(\Geekish\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $parser->forward();

        $chan = new \Geekish\Gedcom\Record\Chan();

        while (! $parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = trim((string) $record[1]);
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();

                break;
            }

            switch ($recordType) {
                case 'DATE':
                    $chan->setDate(trim((string) $record[2]));

                    break;
                case 'TIME':
                    $chan->setTime(trim((string) $record[2]));

                    break;
                case 'NOTE':
                    $note = \Geekish\Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $chan->addNote($note);
                    }

                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        $date = $chan->getYear().'-'.$chan->getMonth().'-'.$chan->getDay();
        $chan->setDatetime($date);

        return $chan;
    }
}
