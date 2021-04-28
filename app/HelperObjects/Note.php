<?php


namespace App\HelperObjects;


class Note
{
    public $pitch;

    public $chord_change;

    public $text;

    public $text_continues = false;

    public $is_rest = false;

    public function setLength($length)
    {
        $this->length = $length;
    }
}
