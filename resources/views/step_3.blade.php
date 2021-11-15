@extends('layout')
@section('content')
    <div class="container-fluid">
        <h3>Výstup: Konverze z mix formátu</h3>
        <p>Do textu s akordy byly automaticky doplněny místa na noty.
            Doplňte noty do závorek.</p>

        <div class="row">
            <div class="col-7">
                <form method="post"
                      action="/step/3/score-mixing">
                    @csrf

                    <label>Noty k doplnění</label><br>
                    <textarea rows="15"
                              cols="150"
                              name="input" class="form-control">{{$input}}</textarea>

                    <br>
                    <input type="submit">
                </form>

            </div>
            <div class="col-5">
                <div class="card">
                    <div class="card-header">Vygenerovaná LilyPond šablona</div>
                    <div class="card-body">
                        <textarea readonly cols="50" rows="20">{!! $song->getLilyPondTemplate()!!}</textarea>
                       </div>
                </div>

                @dump($song)
            </div>
        </div>
    </div>
@endsection

