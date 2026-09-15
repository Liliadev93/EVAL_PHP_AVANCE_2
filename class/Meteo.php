<?php

require_once(__DIR__ . '/../config.php');

class Meteo
{
    // importe les donnees d'un fichier CSV meteo et les enregistre en BDD
    public function insertCsvData($filename)
    {
        // verification si fichier existe et si cʼest bien un format .csv
        if (!file_exists($filename)) {
            return "Le fichier $filename est introuvable.";
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (strtolower($extension) != 'csv') {
            return "Le fichier $filename n'est pas un fichier CSV.";
        }

        // connexion à la BDD
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($mysqli->connect_errno) {
            return "Echec de la connexion à la base de données : " . $mysqli->connect_error;
        }

        // on verifie si la table est deja remplie, pour eviter les doublons a chaque rechargement
        $check = $mysqli->query("SELECT COUNT(*) as total FROM meteo");
        $row_check = $check->fetch_array();

        if ($row_check['total'] == 0) {
            // si table vide -> on importe

            $handle = fopen($filename, 'r');
            //on ouvre le fichier csv
            if ($handle === false) {
                //si echoue on retourne erreur
                return "Impossible d'ouvrir le fichier $filename.";
            }

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                //tant que fgetcsv lit une ligne
                $success = $mysqli->query("INSERT INTO meteo (date_meteo, ville, periode, resume, id_resume, Temp_min, Temp_max, commentaire) VALUES ('" . $data[0] . "', '" . $data[1] . "', '" . $data[2] . "', '" . $data[3] . "', '" . $data[4] . "', '" . $data[5] . "', '" . $data[6] . "', '" . $data[7] . "')");
                //pour chaque lignes lue on exécute la rêq insert into dans la BDD 

                if (!$success) {
                    fclose($handle);
                    return "Erreur lors de l'insertion en base : " . $mysqli->error;
                }
                //si probleme dʼinsertion en BDD
            }

            fclose($handle);
            //comme closedir 
        }

        return true;
    }

    // recupere les previsions meteo du lendemain et du surlendemain (a partir dʼune date de reference fixe)
    public function getSortData()
    {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($mysqli->connect_errno) {
            return "Echec de la connexion à la base de données : " . $mysqli->connect_error;
        }

        $reference = '2100-12-05';
        $demain = date('Y-m-d', strtotime($reference . ' +1 day'));
        $apres_demain = date('Y-m-d', strtotime($reference . ' +2 days'));

        $result = $mysqli->query("SELECT * FROM meteo WHERE date_meteo IN ('$demain', '$apres_demain')");
        //on ne recupere que le lendemain et le surlendemain

        if (!$result) {
            return "Erreur lors de la récupération des données : " . $mysqli->error;
        }

        $meteo_data = []; // tableau vide
        while ($row = $result->fetch_array()) {
            //tant quʼil y a un resultat on insere ses derniers dans le tableau 
            $meteo_data[] = $row;
        }

        return $meteo_data;
    }
}
