<?php include("steamapi.php");?>
<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>

        <?php
        foreach($users_array as $user)
        {

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
        </ul>
        <?php
        }
        ?>
    </body>
</html>