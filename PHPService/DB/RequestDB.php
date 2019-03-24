<?php 

// Transforme la response PDO en format json
function formatToJson($response) {
    $results = $response->fetchAll(PDO::FETCH_ASSOC);
    $json= json_encode($results);
    return $json;
}

function getMusic() {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM musique";
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getMusicRandom() {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM musique ORDER BY RAND() LIMIT 10";
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getAlbum() {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM album";
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getMusicByAlbum($id) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM musique where id_album=" . $id;
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getMusicById($id) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM musique where id=" . $id;
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getPlaylist() {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM playlist";
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function getPlaylistById($id) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    $result = "";
    try {
        $sql = "SELECT * FROM playlist where id=" . $id;
        $result = $connection->query($sql);
        $result = formatToJson($result);
    } catch (PDOException $e) {
        echo $e;
    }
    return $result;
}

function deletePlaylistById($id) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    try {
        $sql = "DELETE FROM playlist where id=" . $id;
        $connection->query($sql);
    } catch (PDOException $e) {
        echo $e;
    }
}

function createPlaylist($title) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd
    try {
        $sql = "INSERT INTO playlist (title) values ('$title')";
        $connection->query($sql);
    } catch (PDOException $e) {
        echo $e;
    }
}

function putMusiqueInPlaylistById($id_p,$id_m) {
    $connection = connectionDataBase(); //Récupérer la connection à la bdd

    $playlist = json_decode(getPlaylistById($id_p));

    $array = explode(",",$playlist[0]->liste_musique);
    array_push($array,$id_m);
    $str = implode(",", $array);

    try {
        $sql = "UPDATE playlist set liste_musique= \"". $str ."\" where id=" . $id_p;
        $result = $connection->query($sql);
    } catch (PDOException $e) {
        echo $e;
    }
}