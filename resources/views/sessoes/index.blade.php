@extends('home')

@section('content')
<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
    <div class="col mb-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-center" name="sess" id="idSess">
                    

                    <form class="sess-search" action="#" method="GET">
                        <div class="search-item">
                            <label for="idSess">Disc:</label>
                            <div name="Fil" id="idFilme">
                            @foreach ($FilmeSessoes as $fil)
                            <a value="{{$fil->id}}">{{$fil->data}} - {{$fil->horario_inicio}}</a>
                                
                            @endforeach
                            </div>
                        </div>
                        <div class="search-item">
                            <button type="submit" class="bt" id="btn-filter">Filtrar</button>
                        </div>
                    </form>
                                        
                </div>
            </div>
            <!-- Product actions-->
            
        </div>
        </div>
    </div>
    <div>
</div>
@endsection