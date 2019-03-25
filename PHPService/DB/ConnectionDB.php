<?php

function connectionDataBase()
{
    $servername = "localhost";
    // $servername = "192.168.1.36";
    $username = "root";
    $password = "";
    $dbname = "music";
    $connection = null;

    try {
        $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }

    return $connection;
}

function resetDataBase($connection)
{
    try {
        $sql = "DROP TABLE musique;DROP TABLE user;DROP TABLE playlist;DROP TABLE album;";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
}

function createDataBase($connection)
{
    try {
        $sql = "CREATE TABLE album (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(3000) DEFAULT NULL
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
    try {
        $sql = "CREATE TABLE user (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            mdp VARCHAR(3000) NOT NULL,
            email VARCHAR(3000) NOT NULL,
            nom VARCHAR(3000) NOT NULL,
            prenom VARCHAR(3000) NOT NULL,
            pseudo VARCHAR(3000) NOT NULL
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
    try {
        $sql = "CREATE TABLE playlist (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(3000) NOT NULL,
            liste_musique VARCHAR(3000) DEFAULT ''
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
        try {
        $sql = "CREATE TABLE musique (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(3000) DEFAULT NULL,
            album VARCHAR(3000) DEFAULT NULL,
            genre VARCHAR(3000) DEFAULT NULL,
            artist VARCHAR(3000) DEFAULT NULL,
            annee VARCHAR(3000) DEFAULT NULL,
            duree VARCHAR(3000) DEFAULT NULL,
            filepath VARCHAR(3000) DEFAULT NULL,
            id_album INT(6) DEFAULT NULL        
            ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
        try {
        $sql = "ALTER TABLE `musique` ADD KEY `id_album` (`id_album`);";
        $connection->exec($sql);
    } catch (PDOException $e) {
    }
}

function peopleDataBaseHard() {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    resetDataBase($connection); //Supprime toutes les tables
    createDataBase($connection); //Creer la BDD

    $dir = "D:/Musique/Radio/";
    $array = scandir($dir);

    for ($i=2; $i < sizeof($array); $i++) {
        $conn = connectionDataBase();
        try {
            $sql = "INSERT INTO album (titre) VALUES (\"$array[$i]\")";
            $conn->exec($sql);
        } catch (PDOException $e) {
        }
        peopleDataBase($conn,$dir . $array[$i],$i-1);
    }
    // $getID3 = new getID3();
    // print_r($getID3->analyze("D:/Alexandre/Musique/Radio/Avicii/13 - Avicii - Waiting For Love.mp3"));
}

function peopleDataBase($conn,$dir, $ref_album)
{
    $files = scandir($dir);
    $getID3 = new getID3();

    for ($i = 2; $i < count($files); $i++) {
        $ThisFileInfo = $getID3->analyze($dir . "/" . $files[$i]);

        $titre = " ";
        $album = " ";
        $genre = " ";
        $artist = " ";
        $annee = " ";
        $duree = " ";
        $filepath = " ";
        if (isset($ThisFileInfo['tags']['id3v2']['album'])) {
            $album = str_replace("100% ", "", implode(",", $ThisFileInfo['tags']['id3v2']['album']));
        }
        if (isset($ThisFileInfo['tags']['id3v2']['genre'])) {
            $genre = implode(",", $ThisFileInfo['tags']['id3v2']['genre']);
        }
        if (isset($ThisFileInfo['tags']['id3v2']['artist'])) {
            $artist = implode(",", $ThisFileInfo['tags']['id3v2']['artist']);
        }
        if (isset($ThisFileInfo['tags']['id3v2']['year'])) {
            $annee = implode(",", $ThisFileInfo['tags']['id3v2']['year']);
        }
        if (isset($ThisFileInfo['tags']['id3v2']['title'])) {
            $titre = preg_replace('#\((.+)\)#U', '', implode(",", $ThisFileInfo['tags']['id3v2']['title']));
        }
        if (isset($ThisFileInfo['playtime_string'])) {
            $duree = $ThisFileInfo['playtime_string'];
        }
        if (isset($ThisFileInfo['filenamepath'])) {
            $filepath = $ThisFileInfo['filenamepath'];
        }

        try {
            $sql = "INSERT INTO musique (titre, album, genre, artist, annee, duree, filepath, id_album) VALUES (\"$titre\",\"$album\",\"$genre\",\"$artist\",\"$annee\",\"$duree\",\"$filepath\",\"$ref_album\")";
            $conn->exec($sql);
        } catch (PDOException $e) {
        }
    }
}
