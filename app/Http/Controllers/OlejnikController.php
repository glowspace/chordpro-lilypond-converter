<?php

namespace App\Http\Controllers;

use App\Services\CzechSyllable;
use Illuminate\Http\Request;

class OlejnikController extends Controller
{
    public function form()
    {
        return view('aleluja_form');
    }


    public function submit(Request $request)
    {
        $text = $request['text'];

        // Get two half of the verse
        $exploded = explode('>', $text);
        $text_1   = explode(' ', $exploded[0]);
        $text_2   = explode(' ', $exploded[1]);

        $text_1_hyphenated = $this->hyphenate_text($text_1);
        $text_2_hyphenated = $this->hyphenate_text($text_2);

        $count_text_1 = $this->count_syllables($text_1);
        $count_text_2 = $this->count_syllables($text_2);

        $score = implode(' ', $this->getScore($count_text_1, $count_text_2));

        $text_template = implode(' ', $text_1_hyphenated);
        $text_template .= "\n  " . implode(' ', $text_2_hyphenated);

        return view('aleluja_form', [
            'result' => $this->getLilyPondTemplate($score, $text_template),
        ]);
    }


    private function count_syllables(array $words_array): int
    {
        $count = 0;

        foreach ($words_array as $word)
        {
            $syl   = new CzechSyllable();
            $count = $count + count($syl->hyphenate($word));
        }

        return $count;
    }


    private function hyphenate_text(array $words_array): array
    {
        $hyphenated_result = [];

        foreach ($words_array as $word)
        {
            $syl                 = new CzechSyllable();
            $hyphenated_result[] = implode(' -- ', $syl->hyphenate($word));
        }

        return $hyphenated_result;
    }


    private function getScore(int $part_1_syllable_count, int $part_2_syllable_count): array
    {
        // Part 1
        $score = [
            'r8',
            'f8',
            'g',
        ];

        $tone_a_count = $part_1_syllable_count - 5;

        for ($i = 1; $i <= $tone_a_count; $i++)
        {
            $score[] = 'a';
        }

        $score[] = 'g';
        $score[] = 'a';
        $score[] = 'f2';
        $score[] = '\bar "|"';

        // Part 2
        $tone_a_count = $part_2_syllable_count - 5;
        for ($i = 1; $i <= $tone_a_count; $i++)
        {
            $score[] = 'a8';
        }

        $score[] = 'f';
        $score[] = 'g';
        $score[] = '(a)';
        $score[] = 'g';
        $score[] = 'f';
        $score[] = 'f4';
        $score[] = '\bar "||"';

        return $score;
    }


    public function getLilyPondTemplate($score, $text)
    {
        $template = <<<TEMPLATE
solo = \\relative c' {
  \cadenzaOn
  $score
}

soloText = \lyricmode {
  $text
}
TEMPLATE;

        return $template;
    }
}
