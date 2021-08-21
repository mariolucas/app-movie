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
                        @if (isset( $movie['genre_name'] ))
                            <strong>Gênero:</strong> {{ $movie['genre_name'] }} <br>
                        @endif
                        
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

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
                
                @if ( $pages["page"] != 1 )
                    <li class="page-item"><a class="page-link" href="{{ route('movies.trending.page', $pages["page"]-1 ) }}">Previous</a></li>
                @endif
                <li class="page-item"><a class="page-link" > {{ $pages["page"] }} </a></li>
                @if ( $pages["page"] != $pages["total"] )
                    <li class="page-item"><a class="page-link" href="{{ route('movies.trending.page', $pages["page"]+1 ) }}">Next</a></li>
                @endif
            </ul>
    </nav>

@endsection