<?php
//Hacer una pagina web que al ingresar 7 integers busque la ciudad del codigo postal y me el clima para los siguientes dias y el mapa del lugar.
//AIzaSyBw00YBqJJ8o6VOYNlT9BjrQAeidjtpi34 api key
// api key clima: cOmY1ysYw1xsQ5ylsn8PLwF5VCX8Y2x0
//AIzaSyConls06pEGtIyPTA8_D0lyTm8k3ODuamc API KEY Gmaps
//echo 'PHP version: ' . phpversion();
date_default_timezone_set("Japan");
$h = new DateTime();
$d = new DateTime('+1day');
$af = new DateTime('+2day');
$today = $h->format('d/m/Y');
$tomorrow = $d->format('d/m/Y');
$dayafter= $af->format('d/m/Y');
$postcod="";


?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Anton|Chilanka|Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <title></title>
  </head>
  <body>
    <header class="form-container">


        <form class="searcher" action="" method="get"enctype="multipart/form-data">
          <label for="">Enter your Post Code</label>
          <input id="psearch" type="text" placeholder="Ex:XXX-XXXX" name="postcode" value="<?=$postcod?>">
          <button type="submit"id="buttonId"><strong>Search</strong></button>
        </form>
      </header>


  <?php  if(isset($_GET["postcode"]))
    {
      //Here i use get to take the value of submit and only take the numbers of the postal code, so if you write letters, the program ignore it
      $postcod=$_GET["postcode"];
      $limit=7;
      $postcod = preg_replace('/\D/', '', $postcod);
      $postcod = substr($postcod, 0, 7);

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://postcodejp-api.p.rapidapi.com/postcodes?postcode=".$postcod,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "x-rapidapi-host: postcodejp-api.p.rapidapi.com",
          "x-rapidapi-key: 3326c4fea1msh3352fd81cad4962p17d7ffjsnaa7087b43881"
        ),
      ),);

      $response = curl_exec($curl);

      $err = curl_error($curl);
      $dat=json_decode($response,true);
      //var_dump($dat);
      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      }
      //catch errors
      if(!array_key_exists("data", $dat))
      {

        $vamo= "Please Enter a Valid Postcode";
        ?>



        <div class="fail">


        <h1>The postal code is invalid</h1>

        <h2>Please try another one</h2>
        </div>

        <?php
      }

       else
      {
        // Here I catch if the combinations of numbers is correct but there is not a city for that postal code
        if(!array_key_exists(0, $dat["data"]))
        {
          ?>
          <div class="fail">


          <h1>The postal code is invalid</h1>

          <h2>Please try another one</h2>

          </div>
          <?php

        }
        else {


          $posto = $postcod."->".$dat["data"][0]["allAddress"];
          $city = $dat["data"][0]["town"];
          $prefcap = $dat["data"][0]["state"];
          $fwurl="http://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&cnt=1&appid=f3f7220c8457e9013bf4182b6cd5f5e4";

          @$fwdata = json_decode(file_get_contents($fwurl),true);
          //There are cases in small japanese islands where openweathermap might not have data of that particular islands
          //so i catch the null in the json or 404 and change the city to the prefecture.
          if ($fwdata==NULL) {
            $upsy = "My api does not have that city ($city) in the database, but i will give you the prefecture capital data ($prefcap) data";
            $dat["data"][0]["town"]=$dat["data"][0]["state"];
            $city = $dat["data"][0]["town"];
            $fwurl="http://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&cnt=1&appid=f3f7220c8457e9013bf4182b6cd5f5e4";
            $fwdata = json_decode(file_get_contents($fwurl),true);
          }
          //because i use a japanese post code api they do not give romanji data, so I use the city from the current weather
          // and the current weather gives the lat and log for the forecast, that because i have the free apikey
          $lat= $fwdata["city"]["coord"]["lat"];
          $lon=$fwdata["city"]["coord"]["lon"];
          $cityname=$fwdata["city"]["name"];

          $dwurl="https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=hourly,minutely&units=metric&appid=f3f7220c8457e9013bf4182b6cd5f5e4";
          $ocwdata = json_decode(file_get_contents($dwurl),true);
          // i show the current weather, after that todays forecast, tomorrow and the day after.
          // the forcast change in the index after daily being 0 today 1 tomorrow and 2 they after.
          //starts now
          $tempnow=$ocwdata["current"]["temp"];
          $weathernow=$ocwdata["current"]["weather"][0]["main"];
          $descnow=$ocwdata["current"]["weather"][0]["description"];
          $iconnow=$ocwdata["current"]["weather"][0]["icon"];
          //starts today
          $todaytmax=$ocwdata["daily"][0]['temp']['max'];
          $todaytmin=$ocwdata["daily"][0]['temp']['min'];
          $todayweather=$ocwdata["daily"][0]["weather"][0]["main"];
          $todaydesc=$ocwdata["daily"][0]["weather"][0]["description"];
          $todayicon=$ocwdata["daily"][0]["weather"][0]["icon"];
          //starts tomorrow
          $tomorrowmax=$ocwdata["daily"][1]['temp']['max'];
          $tomorrowmin=$ocwdata["daily"][1]['temp']['min'];
          $tomweather=$ocwdata["daily"][1]["weather"][0]["main"];
          $tomdesc=$ocwdata["daily"][1]["weather"][0]["description"];
          $tomicon=$ocwdata["daily"][1]["weather"][0]["icon"];
          //starts the day after tomorrow.
          $dayaftermax=$ocwdata["daily"][2]['temp']['max'];
          $dayaftermin=$ocwdata["daily"][2]['temp']['min'];
          $afweather=$ocwdata["daily"][2]["weather"][0]["main"];
          $afdesc=$ocwdata["daily"][2]["weather"][0]["description"];
          $aficon=$ocwdata["daily"][2]["weather"][0]["icon"];
          ?>
          <div class="container">
            <h1><?=$posto?></h1>
          <section class="ahora">


          <div class="now" id="nw">
            <h2><?=$today?></h3>
            <h2>Now in : <?=$cityname?> The temperature is:<?=$tempnow?>°C</h2>
            <img src=" http://openweathermap.org/img/wn/<?=$iconnow?>@2x.png" alt="">
            <h3><?=$weathernow?>: <?=$descnow?> </h3>
          </div>


        </section>
        <div class="forca">


          <div class="forecast" id="f1">
            <h2>Today in: <?=$cityname?></h2>
            <h2>(<?=$today?>)</h2>

            <h3>The maximum temperature will be:<?=$todaytmax?>°C </h3>
            <h3>The Minimun Tempere will be <?=$todaytmin?>°C</h3>
            <img src=" http://openweathermap.org/img/wn/<?=$todayicon?>@2x.png" alt="">
            <h3><?=$todayweather?>: <?=$todaydesc?> </h3>

          </div>
          <div class="forecast" id="f2">
            <h2>Tomorrow in: <?=$cityname?> </h2>
            <h2>(<?=$tomorrow?>)</h2>
            <h3>The maximun temperature will be:<?=$tomorrowmax?>°C </h3>
            <h3>The Minimun Tempere will be: <?=$tomorrowmin?>°C</h3>

            <img src=" http://openweathermap.org/img/wn/<?=$tomicon?>@2x.png" alt="">
            <h3><?=$tomweather?>, <?=$tomdesc?></h3>

          </div>
          <div class="forecast" id="f3">
            <h2>The day after tomorrow in: <?=$cityname?> </h2>
            <h2>(<?=$dayafter?>)</h2>
            <h3>The maximun temperature will be:<?=$dayaftermax?>°C </h3>
            <h3>The Minimun Tempere will be: <?=$dayaftermin?>°C</h3>

            <img src=" http://openweathermap.org/img/wn/<?=$aficon?>@2x.png" alt="">
            <h3><?=$afweather?>,<?=$afdesc?> </h3>

            </div>
          </div>
          <div class="map">


          <iframe width="600" height="450" frameborder="10" style="border:1"
              src="https://www.google.com/maps/embed/v1/view?zoom=15&center=<?=$lat?>,<?=$lon?>&key=AIzaSyConls06pEGtIyPTA8_D0lyTm8k3ODuamc" allowfullscreen>

          </iframe>
          </div>
        </div>

          <?php
      }

      }
      }
    else {;
        ?>
          <div class="fail">



        <h1>Welcome to Nippost</h1>
        <h2>Enter a 7 digit postal code in the search bar above to get data of the weather there</h2>
      

      </div>

      <?php
    };

        ?>



<script src="validator.js" charset="utf-8"></script>
</body>
</html>
