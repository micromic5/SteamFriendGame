<html><body><?php
//server request timeout after 3000seconds
ini_set('max_execution_time', 3000);
//setup
$api_key = "D50A7D85688E2A0688F03F404F8291E1";
$steamid = "76561197977068643";
//basic user information
$api_url_user_info = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api_key&steamids=$steamid";
$json = json_decode(file_get_contents($api_url_user_info), true);
//echo "Wellcome ".$json["response"]["players"][0]["personaname"];
$join_date = date("D, M j, Y", $json["response"]["players"][0]["timecreated"]);

function personaState($state)
{
    if ($state == 1)
    {
        return "Online";
    }
    elseif ($state == 2)
    {
        return "Busy";
    }
    elseif ($state == 3)
    {
        return "Away";
    }
    elseif ($state == 4)
    {
        return "Snooze";
    }
    elseif ($state == 5)
    {
        return "Looking to trade";
    }
    elseif ($state == 6)
    {
        return "Looking to play";
    }
    else
    {
        return "Offline";
    }
}

//games owned
$api_url_games_owned = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=$api_key&steamid=$steamid&include_appinfo=1&format=json";
$json_games_owned = json_decode(file_get_contents($api_url_games_owned), true);
$overall_time = 0;

$free_games = 0;
$money_used_for_Games = 0;
$owned_games_array = [];
$owned_games_ids_string = "";
$steam_url_game_info = "https://store.steampowered.com/api/appdetails?appids=";
$gameCounter = 0;
//gameInfo.json file contains infos(genre,categorie,price) over steam games
$str = file_get_contents('gameInfo.json');
$json_current_game_info = json_decode($str, true); // decode the JSON into an associative array
//var_dump($json_current_game_info);
$first_row_write_into_file = true;
$do_break = false;
$do_break_counter = 0;  //if I have to make to many steam request give it a break and start over
$write_into_file ="";
$should_write_into_file = false;
//gather information about all games of one user
foreach($json_games_owned["response"]["games"] as $game_owned)
{
    array_push($owned_games_array,$game_owned["appid"]);
    $categories = "";
    $genres = "";
    $price = 0;
    //found the needed infos inside the gameInfo.json file
    if(!is_null($json_current_game_info) && array_key_exists($game_owned["appid"],$json_current_game_info))
    {
        //im array nach appid suchen und geld, spiele kategorienzusammenrechnen usw.
        $price = $json_current_game_info[$game_owned["appid"]]["price"];
        echo "existing in array ".($gameCounter+$free_games)."<br>";
    }
    else
    {
        $do_break_counter++;
        if($do_break_counter > 500)
        {
            echo "counted request, to many requests";
            echo "<script>setTimeout(function(){ location.reload(); }, 30000);</script>";
            $do_break = true;
        }
        //open the file to write new infos into it
        if(!$should_write_into_file){
            $should_write_into_file = true;
            $myfile = fopen("gameInfo.json", "w") or die("Unable to open file!");
        }
        try{
            $content = file_get_contents($steam_url_game_info.$game_owned["appid"]."&filters=categories,genres,price_overview");
            sleep(3);
            if($content === false)
            {
                echo "false don't know why";
                echo "<script>setTimeout(function(){ location.reload(); }, 30000);</script>";
                $do_break = true;
            }
            else
            {
                $json_new_game_info = json_decode($content, true);
            }
        }
        catch(Exception $e)
        {
            echo "propably too many requests";
            echo "<script>setTimeout(function(){ location.reload(); }, 30000);</script>";
            $do_break = true;
        }
        //found new infos which weren't in the file
        if(!$do_break && $json_new_game_info[$game_owned["appid"]]["success"])
        {
            echo "found new in array ".($gameCounter+$free_games)."<br>";
            if(array_key_exists("categories",$json_new_game_info[$game_owned["appid"]]["data"]))
            {
                foreach($json_new_game_info[$game_owned["appid"]]["data"]["categories"] as $categorie)
                {
                    $categories .="\"".$categorie["description"]."\",";
                }
                $categories = substr($categories, 0, -1);
            }
            if(array_key_exists("genres",$json_new_game_info[$game_owned["appid"]]["data"]))
            {
                foreach($json_new_game_info[$game_owned["appid"]]["data"]["genres"] as $genre)
                {
                    $genres .="\"".$genre["description"]."\",";
                }
                $genres = substr($genres, 0, -1);
            }
            if(array_key_exists("price_overview",$json_new_game_info[$game_owned["appid"]]["data"]))
            {
                $price = $json_new_game_info[$game_owned["appid"]]["data"]["price_overview"]["initial"];
            }
            if(!$first_row_write_into_file)
            {
                $write_into_file .=",";
                $write_into_file .="\"".$game_owned["appid"]."\":{\"categories\":[".$categories."],\"genres\":[".$genres."],\"price\":".$price."}";
            }
            else
            {
                $write_into_file .="{\"".$game_owned["appid"]."\":{\"categories\":[".$categories."],\"genres\":[".$genres."],\"price\":".$price."}";
                $first_row_write_into_file = false;
            }
        }
        else
        {
            $price = -1;
            if(!$first_row_write_into_file)
            {
                $write_into_file .=",";
                $write_into_file .="\"".$game_owned["appid"]."\":{\"categories\":[".$categories."],\"genres\":[".$genres."],\"price\":".$price."}";
            }
            else
            {
                $write_into_file .="{\"".$game_owned["appid"]."\":{\"categories\":[".$categories."],\"genres\":[".$genres."],\"price\":".$price."}";
                $first_row_write_into_file = false;
            }
            echo "not found in array ".($gameCounter+$free_games)."<br>";
        }
    }
    //genre und kategorie speichern damit ich nacher anfragen sparen kann die bereits gemachten anfragen in ein file speichern bzw array und so nicht so viele Anfragen haben.
    //   $owned_games_array[$game_owned["appid"]][0] = json_decode(file_get_contents($steam_url_game_info.$game_owned["appid"]."&filters=categories,genres"), true);

    //var_dump($owned_games_array[$game_owned["appid"]][0]);
    //$owned_games_ids_string = $owned_games_ids_string.$game_owned["appid"].",";
    $overall_time += $game_owned["playtime_forever"];
    if($price > 0)
    {
        $money_used_for_Games += $price;
        $gameCounter++;
    }
    elseif($price == -1)
    {
        echo "game was not found and has no price";
    }
    else
    {
        $free_games++;
    }
    //20 games together much more requests didn't work
   // if($gameCounter%20==0){
   //     countMoney($owned_games_ids_string);
   //     $owned_games_ids_string = "";
   // }
    /*if($do_break >= 5000)
    {
        break;
    }*/
    if($do_break)
    {
        break;
    }
}
if($should_write_into_file)
{
    writeFile($write_into_file,$str,$myfile);
}
//the rest of the owned_games_ids_string between 0 and 20 requests
//countMoney($owned_games_ids_string);
//$owned_games_ids_string = "";
echo "<br>";
echo "you played: ".$free_games." free games<br>and you own: ".$gameCounter." games<br> your owned games have a value of: ".($money_used_for_Games/100)." Fr.";
echo "<br/>overall Time played: ".($overall_time/60);

function writeFile($write_into_file,$str,$myfile)
{

    if(strlen($write_into_file) > 0 && strlen($str) > 0)
    {
        $write_into_file .= ",".substr($str, 1);
        fwrite($myfile, $write_into_file);
    }
    else if(strlen($write_into_file) > 0)
    {
        $write_into_file .= "}";
        fwrite($myfile, $write_into_file);
    }
    else if(strlen($str) > 0)
    {
        $write_into_file = $str;
        fwrite($myfile, $write_into_file);
    }
    fclose($myfile);
}

function countMoney($owned_games_ids_string) {
    $json_games_infos = json_decode(file_get_contents($GLOBALS['steam_url_game_info'].$owned_games_ids_string."&filters=price_overview"), true);
    foreach($json_games_infos as $json_game_info){
        //check if the game is found on steam
        if($json_game_info["success"])
        {
            //check if the game has a price
            if(count($json_game_info["data"]) > 0){
                $GLOBALS['money_used_for_Games'] += $json_game_info["data"]["price_overview"]["initial"];
            }
            else
            {
                $GLOBALS['free_games'] += 1;
            }
        }
    }
}




 /*$steam_url_game_info=$steam_url_game_info.$owned_games_ids_string."&filters=price_overview";
 echo $steam_url_game_info;
 $a =json_decode(file_get_contents($steam_url_game_info), true);
 var_dump($a);*/
 //var_dump(json_decode(file_get_contents($steam_url_game_info.$owned_games_ids_string."&filters=price_overview"), true));
/* foreach($json_games_owned["response"]["games"] as $game_owned){
    array_push($owned_games_ids_array,$game_owned["appid"]);
    $steam_url_current_game_info=$steam_url_game_info."".$game_owned["appid"];
    //var_dump(json_decode(file_get_contents($steam_url_current_game_info), true)[$game_owned["appid"]]["data"]["is_free"]);
    //var_dump(json_decode(file_get_contents($steam_url_current_game_info), true)[$game_owned["appid"]]);
    $free_games+=json_decode(file_get_contents($steam_url_current_game_info), true)[$game_owned["appid"]]["data"]["is_free"]?1:0;
 }*/
/*echo $free_games+=json_decode(file_get_contents($steam_url_game_info), true)["220"]["data"]["is_free"]?1:0;
echo "<br/> hours played: ";
echo $free_games;
echo "<br/>overall Time played: ";
echo ($overall_time/60);*/
/*

    $test = "http://steamcommunity.com/profiles/76561198047660789/games?tab=all&xml=1";
    var_dump(json_decode(file_get_contents($test), true));*/


/* 1 zu 1 eine Seite zeigen
    $api_url_two = "https://store.steampowered.com/app/374320/DARK_SOULS_III/";
    $json_two = json_decode(file_get_contents($api_url_two), true);
 	echo substr(file_get_contents($api_url_two),0,500000);
*/


 	//Alle achivements aus einem Spiel
 	//http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=374320&key=D50A7D85688E2A0688F03F404F8291E1&steamid=76561198047660789&l=%22de%22
 	//
 	//alle steamgames + spiel Zeit
 	//http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=D50A7D85688E2A0688F03F404F8291E1&steamid=76561198047660789&include_appinfo=1&include_played_free_games=1&format=json
 	//
 	//Logo oder icon des spiels http://media.steampowered.com/steamcommunity/public/images/apps/{appid}/{hash}.jpg
 	//    http://media.steampowered.com/steamcommunity/public/images/apps/374320/54f76fd3abdd1446260f28ccc0cbc76034b32de9.jpg
 	//
 	//
 	//achivements with extra info to one game
 	//http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?key=D50A7D85688E2A0688F03F404F8291E1&appid=374320&l=german&format=json
 	//
 	//Steampage with achivments to darksouls 3
 	//https://steamcommunity.com/profiles/76561198047660789/stats/374320
 	//
 	//
 	//
 	//Global achivements prozent
 	//http://api.steampowered.com/ISteamUserStats/GetGlobalAchievementPercentagesForApp/v0002/?gameid=374320&format=xml
 	//https://api.steampowered.com/ISteamUserStats/GetGlobalAchievementPercentagesForApp/v2/?key=D50A7D85688E2A0688F03F404F8291E1&gameid=374320
 	//
 	//etwas grösseres bild vom spiel
 	//https://steamcdn-a.akamaihd.net/steam/apps/374320/header.jpg
    //
    //https://store.steampowered.com/api/appdetails?appids=440&filters=categories
    //https://store.steampowered.com/api/appdetails?appids=440,438640,417800&cc=us&filters=price_overview
?><p>test</p></body>
</html>