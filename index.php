<?php

$server = 'irc.twitch.tv';
$port = 6667;
$nickname = ""; //Put nickname here
$OAuth = ""; //put authkey here
$channel = ""; //Channel to connect to
$owner = "JustJollyJonah"; //Owner of the bot
$youtube = "http://www.youtube.com/justjollyjonah"; //youtube channel
$facebook = "http://www.facebook.com/justjollyjonah"; //facebook
$nowplayingFile = "C:/Users/Jolly/Documents/nowplaying.txt"; //nowplaying for use with scribbler
$clientId = ""; //clientID
$lastTime = time();


//<editor-fold defaultstate="collapsed" desc="Initialization">
print("Initializing Bot\n");
$followerUrl = "https://api.twitch.tv/kraken/channels/".$channel2."/follows/?client_id=".$clientId;
$followers = file_get_contents($followerUrl);
$jsonarray = json_decode($followers,true);
$followerAmount = $jsonarray['_total'];
$socket = fsockopen($server, $port);
print("Succesfully opened socket\n");
$nowPlaying = file_get_contents($nowplayingFile);
fputs($socket, "PASS " . $OAuth . "\r\n");
fputs($socket, "NICK " . $nickname . "\r\n");
sleep(0.01);
fputs($socket, "JOIN " . $channel . "\r\n");
print("Authenticated and joined channel\n");
sleep(0.1);
//fputs($socket, "PRIVMSG ".$channel." :Jolly Bot initialized Keepo\n");!
fputs($socket, "CAP REQ :twitch.tv/membership\n");
fputs($socket, "PRIVMSG $channel :The cancer is here daddies Kappa\n");
//fputs($socket, "PRIVMSG ".$channel." :/mods\n");
//</editor-fold>

while (is_resource($socket)) {
    $data = fgets($socket);
    echo('received'. nl2br($data));
    flush();
    $ex = explode(' ', $data);
    $explodedNameArray = explode('!', $ex[0]);
    $explodedName = ltrim($explodedNameArray[0], ':');
//    foreach($ex as $value) {
//        echo nl2br($value);
//    }
//    print_r($ex);
    
    if ((time() - $lastTime >= 1)) {
        $lastTime = time();
        $followersNew = file_get_contents($followerUrl);
        $newJsonarray = json_decode($followersNew,true);
        $newFollowerAmount = $newJsonarray['_total'];
        echo $newFollowerAmount." ".$followerAmount;
        echo $newJsonarray['follows'][$newFollowerAmount-1]['user']['name'];
        if ($newFollowerAmount > $followerAmount || $newFollowerAmount < $followerAmount) {
        echo $newFollowerAmount." ".$followerAmount;
        $followAmount = $newFollowerAmount;
        fputs($socket, "PRIVMSG $channel :Welcome new follower: ".$newJsonarray['follows'][$newFollowerAmount-1]['user']['name']);
        echo $newJsonarray['follows'][$newFollowerAmount-1]['user']['name'];
    }
}
    if ($ex[0] == 'PING') {
        fputs($socket, "PONG :" . $ex[1] . "\n");
        print("I ponged back the ping\n");
    } elseif ($ex[1] == "JOIN") {
        
        if ($explodedName != "justjollybot") {
            print("User joined the Channel\n");
//        echo $explodedName;
//            fputs($socket, "PRIVMSG " . $channel . " :Welcome @" . $explodedName . "\n");
        }
    }
    
    $cmd = str_replace(array(chr(10), chr(13)), '', $ex[3]);
    switch ($cmd) {
        case ":!hello":
            fputs($socket, "PRIVMSG $channel :Hello\n");
            print("Senpai noticed me\n");
            break;
        case ":!commands":
            fputs($socket, "PRIVMSG $channel :Current commands are: !hello, !commands, !youtube, !facebook, !nowPlaying, !request\n");
            break;
        case ":!youtube":
            fputs($socket, "PRIVMSG $channel : $owner's current YouTube channel: $youtube \n");
            print("YouTube fame incoming\n");
            break;
        case ":!facebook":
            fputs($socket, "PRIVMSG $channel : $owner's Facebook: $facebook\n");
            print("Does anyone even still use this?\n");
            break;
        case ":!nowplaying":
            $contents = file_get_contents($nowplayingFile);
            $replace = array(' ', ".", "(", ")","!","-");
            $searchContents = str_replace($replace, "", $contents);
            $query = "youtube.com/results?search_query=".urlencode($searchContents) ;
            
            fputs($socket, "PRIVMSG $channel :Now Playing: $contents Link: $query \n");
            print("Dank music much\n");
            break;
        case ":!ban":
            switch ($explodedName) {
            case "justjollyjonah" || "stijn2ling" || "matthaia" || "ibeast_m0de":
                fputs($socket, "PRIVMSG ".$channel." :/ban $ex[4] $ex[5]\n");
                print("bye cunt");
                break;
            default:
                fputs($socket, "PRIVMSG ".$channel." :Nice try Kappa\n");
                break;
            }
            
    }
    
}

