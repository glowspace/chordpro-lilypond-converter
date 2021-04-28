<?php


namespace App\Services;


class CzechSyllable
{
    private $debug_mode = false;

    private $word;
    private $word_letters;

    private $syllables = [];
    private $current_syllable;

    private $found_samohlaska = false;

    /**
     * @param string $word
     * @return array
     */
    public function hyphenate(string $word): array
    {
        $this->word = $word;
        $this->word_letters = mb_str_split($this->word);


        foreach ($this->word_letters as $key => $letter)
        {
            $this->dump("Zpracovávám znak: " . $letter);

            $next_letter = @$this->word_letters[$key + 1];
            $next_next_letter = @$this->word_letters[$key + 2];

            if (!key_exists($key + 1, $this->word_letters))
            {
//                dump("Toto bylo poslední písmeno.");
                $this->current_syllable .= $letter;
                $this->saveCurrentSyllable();
            }

            if (!$this->isLetter($letter))
            {
                $this->current_syllable .= $letter;

                $this->dump("Výsledek: Znak $letter není písmeno, přidávám do slabiky $this->current_syllable.");

//                if(!key_exists($key + 1, $this->word_letters))
//                {
//                    $this->saveCurrentSyllable();
//                }

                continue;
            }

            // Samohláska ještě nebyla nalezena
            if (!$this->found_samohlaska)
            {
                $this->current_syllable .= $letter;

                // Slabika bude končit
                if ($this->isSamohlaska($letter, $next_letter))
                {
                    $this->found_samohlaska = true;
                }
            }

            // Samohláska už byla nalezena
            else
            {
                // Dvojhláska
                if ($this->isSamohlaska($letter, $next_letter))
                {
                    $this->dump("Pozor $letter je už druhá samohláska v řadě.");

                    $this->current_syllable .= $letter;

                    // This looks like new syllable - Save last syllable to array
                    $this->saveCurrentSyllable();


                }

                // Souhláska po samohlásce
                else
                {
                    // There is no next letter.
                    if (!$next_letter)
                    {
                        // This letter is still part of current syllable
                        $this->current_syllable .= $letter;

                        $this->saveCurrentSyllable();

                        $this->dump("Neexistuje další písmeno, končím slabiku.");
                    }

                    if (!$this->isLetter($next_letter))
                    {
                        $this->dump('Další znak není písmeno, přidávám.');

                        $this->current_syllable .= $letter;
                        continue;
                    }

                    // Next letter is not samohlaska
                    if (!$this->isSamohlaska($next_letter, $next_next_letter))
                    {
                        $this->dump("Další písmeno po $letter: $next_letter není samohláska.");

                        // This letter is still part of current syllable
                        $this->current_syllable .= $letter;

                        // Next letter is samohláska
                        $this->saveCurrentSyllable();
                    }
                    else
                    {
                        $this->dump("Další písmeno po $letter: $next_letter je samohláska.");

                        // Next letter is samohláska
                        $this->saveCurrentSyllable();

                        // This letter is still part of current syllable
                        $this->current_syllable .= $letter;
                    }


                }

            }
        }

        return $this->syllables;
    }

    /**
     * @param $letter
     * @param string|null $next_letter
     * @return bool
     */
    private function isSamohlaska($letter, ?string $next_letter)
    {
        $letter = mb_strtolower($letter);


        if ($next_letter)
        {
            if ($letter == 'r' && !$this->isSamohlaska($next_letter, null))
            {
                return true;
            }

            if ($letter == 'l' && !$this->isSamohlaska($next_letter, null))
            {
                return true;
            }
        }
        else
        {
            if ($letter == 'r')
            {
                return true;
            }

            if ($letter == 'l')
            {
                return true;
            }
        }

        switch ($letter)
        {
            case 'a':
            case 'e':
            case 'é':
            case 'ě':
            case 'i':
            case 'í':
            case 'o':
            case 'ó':
            case 'u':
            case 'ů':
            case 'ú':
            case 'y':
            case 'ý':
            case 'á':
                $this->dump("Písmeno $letter je samohláska!");
                return true;

            default:
                return false;
        }
    }

    private function saveCurrentSyllable()
    {
        $this->dump("[OK] Ukládám slabiku $this->current_syllable");

        $this->syllables[] = $this->current_syllable;
        $this->current_syllable = '';
        $this->found_samohlaska = false;
    }

    private function isLetter($letter)
    {
        if (preg_match_all('/\w/u', $letter))
        {
            return true;
        }

        $this->dump("$letter není písmeno");
        return false;
    }

    private function dump(string $string)
    {
        if ($this->debug_mode)
        {
            dump($string);
        }
    }
}
