<?php include("steamapi.php");?>
<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>
        <h1><?=$json["response"]["players"][0]["personaname"];?></h1>
        <img src="<?=$json["response"]["players"][0]["avatarfull"];?>">
        <ul>
            <li>SteamID64: <?=$json["response"]["players"][0]["steamid"];?></li>
            <li>Display Name: <?=$json["response"]["players"][0]["personaname"];?></li>
            <li>URL: <?=$json["response"]["players"][0]["profileurl"];?></li>
            <li>Small Avatar: <?=$json["response"]["players"][0]["avatar"];?></li>
            <li>Medium Avatar: <?=$json["response"]["players"][0]["avatarmedium"];?></li>
            <li>Full Avatar: <?=$json["response"]["players"][0]["avatarfull"];?></li>
            <li>Status: <?=personaState($json['response']['players'][0]['personastate']);?></li>
            <?php
            if(array_key_exists("realname",$json["response"]["players"][0]) != null)
            {
            ?>
            <li>Real Name: 
                <?= $json["response"]["players"][0]["realname"];?>
            </li>
            <?php
            }
            ?>
            <li>Joined: <?=$join_date;?></li>
        </ul>
        </br>
        <ul>
            <li></li>
        </ul>
    </body>
</html>