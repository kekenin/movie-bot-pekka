<?php
include 'Unirest.php';

class GetPoster
{
    function __construct() {
    }

    public function getPoster($id)
    {


        $api_key = "[YOUR_MOVIEDB_TOKEN_HERE]";

        $returnValue = "";


        $response = Unirest\Request::get("https://api.themoviedb.org/3/find/".$id."?api_key=".$api_key."&language=en-US&external_source=imdb_id", $headers=array(), $parameters=null);

        if ($response->code == 200) {
            $returnValue = $response->raw_body;
        }

        $json = json_encode($returnValue);
        $json= json_decode($json);


        $manage = (array) json_decode($json);
        $manage=$manage['movie_results'];
        $manage=json_encode($manage);
        $manage=json_decode($manage);

        $posterPath =$manage[0]->poster_path;

        $base_url = "https://image.tmdb.org/t/p/";

        $size = "w342";
        $imageUrl=$base_url.$size.$posterPath;
        return $imageUrl;
    }
}

?>