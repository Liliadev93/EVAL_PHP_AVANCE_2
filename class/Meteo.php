<?php

require_once(__DIR__ . '/../config.php');

class Meteo
{
    // importe les données d'un fichier CSV météo et les enregistre en BDD
    // Pour chaque ligne : met à jour la prévision si elle existe déjà (même date+ville+periode),
    // sinon l'insère comme nouvelle prévision.
    public function insertCsvData($filename)
    {
        if (!file_exists($filename)) {
            return "Le fichier $filename est introuvable.";
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (strtolower($extension) != 'csv') {
            return "Le fichier $filename n'est pas un fichier CSV.";
        }

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($mysqli->connect_errno) {
            return "Echec de la connexion à la base de données : " . $mysqli->connect_error;
        }

        $handle = fopen($filename, 'r');
        if ($handle === false) {
            return "Impossible d'ouvrir le fichier $filename.";
        }

        $ligne_num = 0;
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            $ligne_num++;

            // ---- 1. VALIDATION DES DONNÉES DE LA LIGNE ----
            if (count($data) < 8) {
                fclose($handle);
                return "Ligne $ligne_num invalide : nombre de colonnes incorrect.";
            }

            $date_meteo  = trim($data[0]);
            $ville       = trim($data[1]);
            $periode     = trim($data[2]);
            $resume      = trim($data[3]);
            $id_resume   = trim($data[4]);
            $temp_min    = trim($data[5]);
            $temp_max    = trim($data[6]);
            $commentaire = trim($data[7]);

            if (empty($date_meteo) || empty($ville) || empty($periode)) {
                fclose($handle);
                return "Ligne $ligne_num invalide : champ obligatoire manquant.";
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_meteo)) {
                fclose($handle);
                return "Ligne $ligne_num invalide : format de date incorrect ($date_meteo).";
            }

            if (!is_numeric($temp_min) || !is_numeric($temp_max) || !is_numeric($id_resume)) {
                fclose($handle);
                return "Ligne $ligne_num invalide : température ou id_resume non numérique.";
            }

            // ---- 2. VÉRIFIE SI CETTE PRÉVISION EXISTE DÉJÀ ----
            $check = $mysqli->query("SELECT COUNT(*) as total FROM meteo WHERE date_meteo = '$date_meteo' AND ville = '$ville' AND periode = '$periode'");
            $row_check = $check->fetch_array();

            if ($row_check['total'] > 0) {
                // existe déjà -> UPDATE
                $success = $mysqli->query("UPDATE meteo SET resume = '$resume', id_resume = '$id_resume', Temp_min = '$temp_min', Temp_max = '$temp_max', commentaire = '$commentaire' WHERE date_meteo = '$date_meteo' AND ville = '$ville' AND periode = '$periode'");
            } else {
                // n'existe pas -> INSERT
                $success = $mysqli->query("INSERT INTO meteo (date_meteo, ville, periode, resume, id_resume, Temp_min, Temp_max, commentaire) VALUES ('$date_meteo', '$ville', '$periode', '$resume', '$id_resume', '$temp_min', '$temp_max', '$commentaire')");
            }

            if (!$success) {
                fclose($handle);
                return "Erreur lors de l'enregistrement de la ligne $ligne_num : " . $mysqli->error;
            }
        }

        fclose($handle);
        return true;
    }

    // récupère les prévisions météo du lendemain et du surlendemain
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

        if (!$result) {
            return "Erreur lors de la récupération des données : " . $mysqli->error;
        }

        $meteo_data = [];
        while ($row = $result->fetch_array()) {
            $meteo_data[] = $row;
        }

        return $meteo_data;
    }
}
