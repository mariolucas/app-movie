@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach ($movies as $movie)
        <div class="col-sm-3">
            <div class="card mb-4">
                @if (is_null( $movie['poster_path']))
                    <div class="no-img">
                        No image
                    </div>
                @else
                    <a href="{{ route('movies.details', $movie['id'])}}" >
                        <img class="card-img-top" src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="">
                    </a>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title"> {{ $movie['title'] }} </h5>
                    <p class="card-details">
                        <strong>Gênero:</strong> {{ $movie['genre_name'] }} <br>
                        <strong>Lançamento:</strong> {{ $movie['release_date']}}
                    </p>
                    <p class="card-text">
                        <strong>Sinopse</strong> <br>
                        {{ $movie['overview']}}
                    </p>
                    <a class="btn btn-primary btn-sm" href="{{ route('movies.details', $movie['id']) }}">Ver mais...</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection