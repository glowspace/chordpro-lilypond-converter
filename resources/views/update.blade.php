@extends('layout')
@section('content')
    <div class="container-fluid">
        <h3>2. krok</h3>
        <p>Do textu s akordy byly automaticky doplněny místa na noty.
            Doplňte noty do závorek.</p>

        <div class="row">
            <div class="col-7">
                <form method="post"
                      action="/">
                    @csrf

                    <label>Noty k doplnění</label><br>
                    <textarea rows="15"
                              cols="150"
                              name="chordpro" class="form-control">{{$song->getLilyPondTemplateText()}}</textarea>

                    <br>
                    <input type="submit">
                </form>

                <h4>Debug data</h4>
                Rozpad na slabiky:
                @dump($hyphens)

            </div>
            <div class="col-5">
                <div class="card">
                    <div class="card-header">LilyPond text</div>
                    <div class="card-body">{{$song->getLilyPondText()}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

