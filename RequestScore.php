<?php

require_once 'Unirest.php';
require_once 'simple_html_dom.php';




class Imdb{


    private $response_body = "";

    function __construct() {

    }

    public function getPage($movieName)
    {

        $returnValue = "";

        $movieName = str_replace(' ', '+', $movieName);
        # Remove &<space>
        $movieName = str_replace(' & ', '%26', $movieName);
        # lowercase
        $movieName = str_replace('&', '%26', $movieName);
        # lowercase
        $movieName = str_replace(': ', '%3A', $movieName);
        # lowercase
        $movieName = str_replace(':', '%3A', $movieName);
        # lowercase
        $movieName = strtolower($movieName);
        # Remove all special chars execept a-z, digits, --sign, ?-sign, !-sign
        //$movieName = preg_replace('/[^a-z\d\?!\-]/', '', $movieName);
        $IMDBLink = "http://www.imdb.com/find?ref_=nv_sr_fn&q=".$movieName."&s=all";

        $response = Unirest\Request::get($IMDBLink, $headers = array(), $parameters = null);
        if($response->code == 200)
        {
            $returnValue = $response->raw_body;
        }
        $this->response_body = $returnValue;
    }



    public function getScore($moviename)
    {
        $moviename = strtolower($moviename);
	
        $html = str_get_html($this->response_body);

        $json_output = array();
        $error = false;

        $name = "";
        $nameArray=[];
        $urlArray=[];
        $metaScoreArray=[];
        $allInfo=[];
        $searchName=null;
        $found=false;


        if (!$html) {
            $json_output['error'] = "Page could not be loaded!";
            $error = true;
            $json_output['name']="";
            return json_encode($json_output);
        }

        if (!$error) {


            //names
            foreach ($html->find('td[class=result_text] a') as $element) {
                array_push($nameArray, strtolower($element->plaintext));

            }


            //ids
	        foreach ($html->find('td[class=result_text] a') as $element) {
                array_push($urlArray, $element->href);

            }

            //all info
            foreach ($html->find('td[class=result_text]') as $element) {
                $info=$element->plaintext;
                $info=strtolower($info);

                array_push($allInfo, $info);
            }

            for($i=0;$i<count($nameArray);$i++) {

                if (strpos($nameArray[$i], $moviename)!==false){
                    $searchName=$urlArray[$i];
                    $found=true;
                    break;
                }

            }
if($found==false) {
    for ($i = 0; $i < count($allInfo); $i++) {

        if ((strpos($allInfo[$i], $moviename) !== false) && (strpos($allInfo[$i], "tv") === false)) {
            $searchName = $urlArray[$i];
            break;

        } else if (strpos($allInfo[$i], "tv") === false) {
            $searchName = $urlArray[$i];
            break;
        }
        $searchName = $urlArray[0];

    }

}


    $response = Unirest\Request::get("http://www.imdb.com/" . $searchName, $headers = array(), $parameters = null);




            $link = "http://www.imdb.com".$searchName;
            $returnValue = $response->raw_body;

            $html = str_get_html($returnValue);


	          foreach ($html->find('div[class=summary_text]') as $element) {
                $imdb_text = $element->plaintext;
            }

            foreach ($html->find('span[class=rating]') as $element) {
                $imdb_score = floatval($element->plaintext);
            }

            foreach ($html->find('div[class=titleReviewBar] div[class=titleReviewBarItem] a') as $element) {
                array_push($metaScoreArray, $element->plaintext);



            }
            if($metaScoreArray==null){
            }
            foreach ($html->find('span[id=titleYear] a') as $element) {
                $year = $element->plaintext;
            }

            foreach ($html->find('h1[itemprop=name]') as $element) {
                $name = $element->plaintext;
            }
            //$name = substr($name, 0, strpos($name, '&'));
	    $name = $nameArray[0];
	    $name = ucwords($name);
        }

        if($name==""){
            $json_output['name']="\n";
            return json_encode($json_output);
        }

	$metaScoreImdb=substr($metaScoreArray[0], 1);

try {
$valueInt = intval($metaScoreImdb);
} catch (Exception $e) {
echo $e;
}



if($valueInt==0){
$metaScoreImdb=" -";
	
} else {
    $metaScoreImdb=$valueInt;
}

        $json_output['name'] =
                                "Name: ".$name ." (".$year.")" ."\n".
                                "IMDB score: " .$imdb_score."\n".
                                "Metascore:" ." ".$metaScoreImdb;

        $json_output['link'] = $link;
        $json_output['summary']=$imdb_text;
        $searchName=substr($searchName,7);
        $searchName = strtok($searchName, '/');

        $json_output['imdbID']=$searchName;

        return json_encode($json_output);


    }
}



?>
