<?php

include 'RequestScore.php';
include 'GetPoster.php';



define('BOT_TOKEN', '[YOUR_TELEGRAM_BOT_TOKEN_HERE]');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

// read incoming info and grab the chatID
$content = file_get_contents("php://input");
$update = json_decode($content, true);


$chatID = "[YOUR_CHANNEL_ID_HERE]";
$message = $update["message"]["text"];


    if ($message[0] == "!" && $message[1]=="l"&&$message[2]=="e"&&$message[3]=="f"&&$message[4]=="f"&&$message[5]=="a") {



        $movieName = $message;
        $movieName = substr($movieName, 7);

        $imdb = new Imdb();
        $imdb->getPage($movieName);

        $json_imdb = $imdb->getScore($moviename);
        $imdbS = json_decode($json_imdb);




        $poster = new GetPoster();

        $url = $poster->getPoster($imdbS->imdbID);




        if($imdbS->name=="Could not find IMDB data.\n"){
            $reply = "Movie not found.";
            $sendto = API_URL . "sendmessage?chat_id=" . $chatID . "&text=" . $reply . "&parse_mode=markdown";
            file_get_contents($sendto);

        } else {


            $text = urlencode(

                $imdbS->name . "\n"
            );
            $reply = $text;
            // send reply
            if ($url == "https://image.tmdb.org/t/p/w342") {
                $sendto = API_URL . "sendmessage?chat_id=" . $chatID . "&text=" . $reply . $imdbS->link;
            } else {

            $sendto = API_URL . "sendphoto?chat_id=" . $chatID . "&photo=" . $url . "&caption=" . $reply . $imdbS->link;//url leffaan
            }

            //file_get_contents($sendto);
            file_get_contents($sendto);
        }





    }

?>
