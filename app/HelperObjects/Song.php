<?php


namespace App\HelperObjects;

/**
 * Song mean collection of note objects.
 */
class Song
{
    public $notes;

    public static function parseFromMixedInput($input)
    {
        $song = new Song();

        $ex = explode(" ", $input);

        $text_continues = false;

        foreach ($ex as $part) {
            if($part == "--"){
                $text_continues = true;
                continue;
            }

            $note = Note::parseFromMixedPart($part);

            if($text_continues)
            {
                $text_continues = false; // Reset text continues for foreach cycle
                $note->text_continues = true; // This note text continues previous note
            }

            $song->notes[] = $note;
        }


        return $song;
    }

    /**
     * Get LilyPond hyphenated template text.
     *
     * @return string
     */
    public function getLilyPondScaffold()
    {
        $text = '';

        foreach ($this->notes as $note)
        {
            $text .= '()' . $note->text;

            if ($note->text_continues)
            {
                $text .= ' -- ';
            }
            else
            {
                $text .= ' ';
            }
        }

        return $text;
    }

    public function getTemplateText()
    {
        $text = '';

        foreach ($this->notes as $note)
        {
            if ($note->text_continues)
            {
                $text .= ' -- ' . $note->text . " ";
            }
            else
            {
                $text .= $note->text . " ";
            }
        }

        return $text;
    }

    public function getTemplateChords()
    {
        $text = '';

        foreach ($this->notes as $note)
        {
            if ($note->chord_change_ly)
            {
                $text .= $note->chord_change_ly . " ";
            }

        }

        return $text;
    }

    public function getTemplateScore()
    {
        $text = '';

        foreach ($this->notes as $note)
        {
            if ($note->legato_continues)
            {
                $text .= "(" . $note->getNoteString() . ") ";
            }
            else
            {
                $text .= $note->getNoteString() . " ";
            }
        }

        return $text;
    }

    public function getLilyPondTemplate()
    {
        $text = $this->getTemplateText();
        $score = $this->getTemplateScore();
        $chords = $this->getTemplateChords();

        $template = <<<TEMPLATE
solo = \\relative {
	$score
}

soloText = \lyricmode {
	$text
}

akordy = \chordmode {
	$chords
}
TEMPLATE;

        return $template;
    }
}
