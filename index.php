<?php
require_once('class/Meteo.php');

$meteo = new Meteo();

$import = $meteo->insertCsvData('paris.csv');

if (true !== $import) {
    $msg_error = $import;
} else {
    $results = $meteo->getSortData();

    if (!is_array($results)) {
        $msg_error = $results;
        $results = [];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Meteo</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>

    <h1>Previsions meteo</h1>

    <?php if (isset($msg_error)): ?>
        <p class="message-error"><?php echo $msg_error; ?></p>
    <?php endif; ?>

    <?php if (!isset($msg_error) && empty($results)): ?>
        <p>Aucune prevision disponible pour le moment.</p>
    <?php endif; ?>

    <div class="meteo-grid">
        <?php foreach ($results as $meteo_ligne): ?>
            <div class="meteo-card">
                <p class="meteo-date"><?php echo date('d/m/Y', strtotime($meteo_ligne['date_meteo'])); ?></p>
                <p class="meteo-details"><?php echo $meteo_ligne['ville']; ?> — <?php echo $meteo_ligne['periode']; ?> </p>
                <p class="meteo-temp"><?php echo $meteo_ligne['Temp_min']; ?>°C / <?php echo $meteo_ligne['Temp_max']; ?>°C</p>
                <p class="meteo-comment"><?php echo $meteo_ligne['commentaire']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>