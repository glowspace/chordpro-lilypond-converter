@extends('layout')
@section('content')
    <div class="container-fluid">

        <h3>Generování alelujového verše před evangeliem (podle nápěvu p. Josefa Olejníka)</h3>
        <p>Vložte text verše.</p>

        <div class="row">
            <div class="col-6">
                <form method="post"
                      action="/aleluja">
                    @csrf

                    <textarea rows="15"
                              cols="150"
                              name="text"
                              class="form-control">Veliký prorok povstal mezi námi,> Bůh navštívil svůj lid.</textarea>

                    <br>
                    <input type="submit">
                </form>
            </div>

            @isset($result)
                <div class="col-6">
                    <textarea rows="15" cols="150" disabled>{{$result}}</textarea>
                </div>
            @endisset
        </div>

    </div>
@endsection
