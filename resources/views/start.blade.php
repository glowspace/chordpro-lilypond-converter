@extends('layout')
@section('content')
<div class="container-fluid">

    <h3>1. krok</h3>
    <p>Vložte text v ChordPro formátu.</p>

    <form method="post"
          action="/step/2/chords">
        @csrf

        <textarea rows="15"
                  cols="150"
                  name="chordpro" class="form-control">Chval Ho, [C]ó duše [G]má.</textarea>

        <br>
        <input type="submit">
    </form>
</div>
@endsection
