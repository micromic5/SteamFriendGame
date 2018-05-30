<?php include("steamapi.php");?>
<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>

        <?php
        foreach($users_array as $user)
        {
            if($user[0]!=0){
            ?>
            <h1><?=$user[5]["response"]["players"][0]["personaname"];?></h1>
            <img src='<?=$user[5]["response"]["players"][0]["avatarfull"];?>'>
            <ul>
                <li>SteamID64: <?=$user[5]["response"]["players"][0]["steamid"];?></li>
                <li>Display Name: <?=$user[5]["response"]["players"][0]["personaname"];?></li>
                <li>URL: <?=$user[5]["response"]["players"][0]["profileurl"];?></li>
                <li>Small Avatar: <?=$user[5]["response"]["players"][0]["avatar"];?></li>
                <li>Medium Avatar: <?=$user[5]["response"]["players"][0]["avatarmedium"];?></li>
                <li>Full Avatar: <?=$user[5]["response"]["players"][0]["avatarfull"];?></li>
                <li>Status: <?=personaState($user[5]['response']['players'][0]['personastate']);?></li>
                <?php
                if(array_key_exists("realname",$user[5]["response"]["players"][0]) != null)
                {
                ?>
                <li>Real Name:
                    <?= $user[5]["response"]["players"][0]["realname"];?>
                </li>
                <?php
                }
                ?>
                <li>Joined: <?=$user[6];?></li>
                <li>Played: <?=$user[1];?> free games</li>
                <li>Owns: <?=$user[2];?> games</li>
                <li>Owned Games value: <?=$user[3];?> Fr.</li>
                <li>Overall Time played: <?=$user[4];?></li>
                <li>Free Games Time played: <?=$user[7];?></li>
                <li>Owned Games Time played: <?=$user[8];?></li>
                <li>Simulation Game hours: <?=$user[11];?></li>
                <li>Action Game hours: <?=$user[12];?></li>
                <li>Strategy Game hours: <?=$user[13];?></li>
                <li>RPG Game hours: <?=$user[14];?></li>
                <li>Racing Game hours: <?=$user[15];?></li>
                <li>Adventure Game hours: <?=$user[16];?></li>
                <li>MMO Game hours: <?=$user[17];?></li>
                <li>Sports Game hours: <?=$user[18];?></li>
                <li>Other Game hours: <?=$user[19];?></li>
                <li>Games played over 100 hours: <?=$user[9];?></li>
                <li>Most Played Game: <?=$user[10][1]."   ".($user[10][0]/60)."   ".(($user[10][2]/100)==0?"Free":($user[10][2]/100)." Fr.")?></li>
                <img src='<?=  json_decode(file_get_contents("https://store.steampowered.com/api/appdetails?appids=".$user[10][1]."&filters=basic"),true)[$user[10][1]]["data"]["header_image"];?>'>
            </ul>
        <?php
            }
        }
        ?>
    </body>
</html>