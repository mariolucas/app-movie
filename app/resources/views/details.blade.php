@extends('layouts.app')

@section('content')

    @if ( isset( $movie['backdrop_path'] ))
        <div class="container-details" style="background-image: url(https://image.tmdb.org/t/p/w500{{ $movie['backdrop_path'] }})">
    @else
        <div class="container-details">
    @endif
        <div class="bg-details">
            <div class="container details">
                <div class="col-md-6 pt-5">
                    <h1>
                        {{ $movie['title'] }}
                    </h1>
                    <p>
                        <small>
                            <span>{{ $movie['release_date']}}</span>
                            <span>{{ $movie['runtime']}} min</span>
                        </small>
                    </p>
                    <div class="details-overview">
                        {{ $movie['overview']}}
                    </div>
                </div>

                <div class="col-md-6 pt-5">
                    <strong>Gênero:</strong> {{ $movie['genre_name'] }} <br>
                    <strong>Avaliação:</strong> {{ $movie['vote_average'] }}
                </div>
            </div>
        </div>
    </div>

@endsection