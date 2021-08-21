<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{

    private $url = "https://api.themoviedb.org/3/";
    private $key = "?api_key=4ec327e462149c3710d63be84b81cf4f";

    private function getApi($param, $param2 = null){

        switch ($param) {
            case 'genres':
                $url = $this->url."genre/movie/list".$this->key;
                $data = Http::get($url)->json()["genres"];
                break;
            
            case 'trending':
                $url = $this->url."trending/all/day".$this->key.$param2;
                $data = Http::get($url)->json();
                break;
            
            case 'movie':
                $url = $this->url."movie/".$param2.$this->key;
                $data = Http::get($url)->json();
                break;
            case 'search':
                $url = $this->url."search/movie/".$this->key.$param2;
                // dd($url);
                $data = Http::get($url)->json();
                break;
            
            default:
                # code...
                break;
        }

        return $data;
    }

    public function trending($param = null){
        if($param){
            $param = '&page='.$param;
        }

        $dataMovies = $this->getApi('trending', $param);
        $movies = $dataMovies["results"];
        $genresApi = $this->getApi('genres');

        foreach($movies as $index => $movie){

            // Tratamento de daos
            $movies[$index] = $this->dataCorrection($movies[$index]);

            // Formatando a data            
            if( array_key_exists('release_date', $movie)){
                $movies[$index]['release_date'] = $this->formatDate( $movies[$index]['release_date'] );
            }else {
                $movies[$index]['release_date'] = null;
            }

            //  Formataçao dos gêneros dos filmes
            $movies[$index] = $this->getGenres($movies[$index], $genresApi );
        }

        

        // Ordenando pelo nome do filme
        $movies = collect($movies)->sortBy('title');

        $pages['page'] = $dataMovies["page"];
        $pages['total'] = $dataMovies["total_pages"];

        return view('index', [
            'movies' => $movies,
            'pages' => $pages
        ]);
    }
    
    public function details($id){

        $movie = $this->getApi('movie', $id);
        $genresApi = $this->getApi('genres');

        // Tratamento dos dados
        $movie = $this->dataCorrection($movie);

        // Formatação da data de lançamento

        if( array_key_exists('release_date', $movie)){
            $movie['release_date'] = $this->formatDate( $movie['release_date'] );
        }else {
            $movie['release_date'] = null;
        }

        // Formatação do gênero do filme
        $movie = $this->getGenres($movie, $genresApi);

        // dd($movie);
        
        return view('details', [
            'movie' => $movie
        ]);
    }

    public function search(Request $request, $search = null, $page = null){
        $data = explode(" ", $request->search);
        $data = implode('+',$data);

        $dataMovies = $this->getApi('search', "&query=".$data);

        $movies = $dataMovies["results"];
        $genresApi = $this->getApi('genres');

        foreach($movies as $index => $movie){

            // Tratamento de daos
            $movies[$index] = $this->dataCorrection($movies[$index]);

            // Formatando a data
            if( array_key_exists('release_date', $movie)){
                $movies[$index]['release_date'] = $this->formatDate( $movies[$index]['release_date'] );
            }else {
                $movies[$index]['release_date'] = null;
            }
             

            //  Formataçao dos gêneros dos filmes
            $movies[$index] = $this->getGenres($movies[$index], $genresApi );
        }

        // Ordenando pelo nome do filme
        $movies = collect($movies)->sortBy('title');

        return view('search', [
            'movies' => $movies
        ]);
    }

    private function getGenres($movie, $genresApi){
        
        if( array_key_exists('genre_ids', $movie) ){
            if(empty( $movie["genre_ids"])){
                $movie["genre_name"][] = "Undefined";
            } else {
                foreach($movie["genre_ids"] as $ids){
                    // Obtendo os nomes dos gêneros de filmes
                    foreach($genresApi as $genres){
                        if($genres["id"] == $ids ){
                            // Criando uma nova chave e adicionando os nomes de gênero
                            $movie["genre_name"][] = $genres["name"];
                        }
                    }
                }
            }
        }

        if( array_key_exists('genres', $movie) ){
            foreach($movie["genres"] as $genres){
                $movie["genre_name"][] = $genres["name"];
            }
        }

        if( isset( $movie["genre_name"] )){
            // Formatando nomes de gêneros
            $movie["genre_name"] = implode(", ", $movie["genre_name"]);
        }
        return $movie;
    }

    public function formatDate($date){
        $data = $date = date('Y', strtotime( $date ));
        return $date;
    }

    /*
    CORREÇÃO DE DADOS
    O bloco de código a seguir é necessário para corrigir uns erros na API;
    Filme está vindo com: 
            "name" no lugar de "title"
            "first_air_date" no lugar de "release_date"
    */
    public function dataCorrection($movie){

        // Verifica se existe a chave "name"
        if(array_key_exists('name', $movie) ){
            // Adiciona uma nova chave
            $movie['title'] = $movie['name'];

            // Excuíndo a chave com erro
            unset($movie['name']);
        }

        // Verifica se existe a chave "first_air_date"
        if( array_key_exists('first_air_date', $movie) ){
            // Adiciona uma nova chave
            $movie['release_date'] = $movie['first_air_date'];

            // Excuíndo a chave com erro
            unset($movie['first_air_date']);
        }
        return $movie;
    }
}