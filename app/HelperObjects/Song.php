<?php


namespace App\HelperObjects;


class Song
{
    public $notes;

    public function getLilyPondTemplateText()
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

    public function getLilyPondText()
    {
        $text = '';

        foreach ($this->notes as $note)
        {
            $text .= $note->text;

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
}
