<?php

namespace App\Http\Controllers;


use App\HelperObjects\Note;
use App\HelperObjects\Song;
use App\Services\CzechSyllable;
use ChordPro\Parser;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function start()
    {
        return view('start');
    }

    public function submitChords(Request $request)
    {
        $chordpro = $request['chordpro'];

        $exploded = explode(" ", $chordpro);

        $blocks = $this->parseChordPro($chordpro);

        $words = [];

        foreach ($exploded as $block)
        {
            $block_words = explode(' ', $block);
            $block_words = array_filter($block_words);

            $words = array_merge($words, $block_words);
        }

        $hyphens = [];

        // Song is
        $song = new Song();

        foreach ($words as $word)
        {
            $c = new CzechSyllable();
            $hyphen_arr = $c->hyphenate($word);

            // Create notes
            foreach ($hyphen_arr as $key => $hyphen)
            {
                $note = new Note();
                $note->text = $hyphen;

                if ($key !== array_key_last($hyphen_arr))
                {
                    $note->text_continues = true;
                }

                $song->notes[] = $note;
            }

            $hyphens = array_merge($hyphens, $hyphen_arr);
        }

        return view('step_2', [
            'song' => $song,
            'hyphens' => $hyphens,
            'chordpro' => $chordpro,
        ]);
    }

    private function parseChordPro($chordpro)
    {
        $parser = new Parser();
        $a = $parser->parse($chordpro);

        $blocks = [];

        foreach ($a->lines as $line)
        {
            foreach ($line->getBlocks() as $block)
            {
                $blocks[] = $block;
            }
        }

        return $blocks;
    }


    public function submitChordProWithScore(Request $request)
    {
        $input = $request->input('input');

        $song = Song::parseFromMixedInput($input);

        return view('step_3', [
            'song' => $song,
            'input' => $input
        ]);
    }
}
