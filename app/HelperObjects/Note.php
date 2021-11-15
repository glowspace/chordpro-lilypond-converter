<?php


namespace App\HelperObjects;


class Note
{
    public $pitch;

    public $length;

    public $chord_change;

    /**
     * @var void
     */
    public $chord_change_ly;

    public $text;

    public $text_continues = false;

    public $is_rest = false;

    public $legato_continues = false;


    /**
     * Input could be something like "[Em](g)Bůh"
     *
     * @param string $part
     */
    public static function parseFromMixedPart(string $part): Note
    {
        $note = new self();

        $chord = $note->findChordInPart($part);

        if ($chord) {
            $note->chord_change = $chord;
            $note->chord_change_ly = $note->convertChordNameToLilyPond($chord);
        }

        $pitch = $note->findNoteInPart($part);

        if ($pitch) {
            // Length
            $note->length = preg_replace('/[^0-9,.]+/', '', $pitch);

            // Pitch
            $note->pitch = preg_replace('/[0-9]+/', '', $pitch);
        }

        $lyrics = $note->findLyricsInPart($part);

        if ($lyrics) {
            $note->text = $lyrics;
        }


        return $note;
    }

    private static function findChordInPart(string $part): ?string
    {
        preg_match_all("/\[([^\]]*)\]/", $part, $matches);

        if (isset($matches[1][0])) {
            return $matches[1][0];
        } else {
            return null;
        }
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    private function convertChordNameToLilyPond($chord_name)
    {
        // Fmaj7 maj7

        $chord_name = mb_strtolower($chord_name);
        $chord_name = $this->addColonToChord($chord_name);

        return $chord_name;
    }

    private function addColonToChord(string $chord_name)
    {
        $replacements = [
            'c' => 'c',
            'cis' => 'cis',
            'c#' => 'cis',
            'db' => 'des',
            'des' => 'des',
            'd' => 'd',
            'dis' => 'dis',
            'd#' => 'dis',
            'es' => 'es',
            'eb' => 'es',
            'e' => 'e',
            'e#' => 'eis',
            'eis' => 'eis',
            'fes' => 'fes',
            'fb' => 'fes',
            'f' => 'f',
            'fis' => 'fis',
            'f#' => 'fis',
            'ges' => 'ges',
            'gb' => 'ges',
            'g' => 'g',
            'gis' => 'gis',
            'g#' => 'gis',
            'as' => 'as',
            'ab' => 'as',
            'a' => 'a',
            'ais' => 'ais',
            'a#' => 'ais',
            'bes' => 'bes',
            'b' => 'bes',
            'h' => 'b',

        ];

        foreach ($replacements as $search_chord => $replacement) {
            if($chord_name == $replacement)
            {
                return  $chord_name;
            }

            if (str_starts_with($chord_name, $search_chord)) {
                return str_replace($search_chord, $replacement . ":", $chord_name);
            }
        }

        return $chord_name;
    }

    private function replaceColonForChord($chord_name, $chord, $replace)
    {
        if (str_starts_with($chord_name, $chord)) {
            return str_replace($chord, "$replace:", $chord_name);
        }

        return $chord_name;
    }

    private function findNoteInPart(string $part)
    {
        preg_match_all("/\(([^\)]*)\)/", $part, $matches);

        if (isset($matches[1][0])) {
            return $matches[1][0];
        } else {
            return null;
        }
    }

    private function findLyricsInPart(string $part)
    {
        $lyrics = preg_replace("/\([^)]+\)/", "", $part); // 'ABC '
        $lyrics = preg_replace("/\[[^)]+\]/", "", $lyrics); // 'ABC '

        return $lyrics;
    }

    public function getNoteString()
    {
        return $this->pitch . $this->length;
    }
}
