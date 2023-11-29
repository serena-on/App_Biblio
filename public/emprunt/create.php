<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./create.php?error=1');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all fields are filled and not empty and date_emprunt < date_retour_prévue
    if (empty($_POST['id_livre']) || empty($_POST['id_etudiant']) || empty($_POST['date_emprunt']) || empty($_POST['date_retour_prévue']) || $_POST['date_emprunt'] > $_POST['date_retour_prévue']) {
        header('Location: ./create.php?invalid_input=1');
        die();
    } else {
        $emprunt = array(
            "id_livre"  => htmlspecialchars(strip_tags($_POST["id_livre"])),
            "id_etudiant"  => htmlspecialchars(strip_tags($_POST["id_etudiant"])),
            "date_emprunt" => htmlspecialchars(strip_tags($_POST["date_emprunt"])),
            "date_retour_prévue"  => htmlspecialchars(strip_tags($_POST["date_retour_prévue"])),
            "date_retour_effective"  => htmlspecialchars(strip_tags($_POST["date_retour_effective"]))
        );

        $query = 'INSERT INTO `Emprunts`(id_livre, id_etudiant, date_emprunt, date_retour_prévue)
        VALUES (:idLivre,:idEtudiant,:dateEmprunt,:dateRetourPrevue);';
        $ps = $cn->prepare($query);
        $ps->bindParam(':idLivre', $emprunt["id_livre"], PDO::PARAM_INT);
        $ps->bindParam(':idEtudiant', $emprunt["id_etudiant"], PDO::PARAM_INT);
        $ps->bindParam(':dateEmprunt', $emprunt["date_emprunt"]);
        $ps->bindParam(':dateRetourPrevue', $emprunt["date_retour_prévue"]);

        if ($ps->execute()) {
            header('Location: ./liste.php?create_success=1');
            die();
        } else {
            header('Location: ./create.php?error=1');
            die();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $ps = $cn->prepare('SELECT * FROM `Livres`;');
    $ps->execute();
    $livres = $ps->fetchAll(PDO::FETCH_ASSOC);

    $ps = $cn->prepare('SELECT * FROM `Étudiants`;');
    $ps->execute();
    $etudiants = $ps->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr-BJ">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un emprunt</title>
    <link rel="stylesheet" href="../bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form method="post">
        <fieldset>
            <legend>Ajouter un emprunt</legend>
            <div class="container">
                <label for="id_livre"> Livre </label>
                <select id="id_livre" name="id_livre">
                    <option value="" selected>Sélectionner une livre</option>
                    <?php
                    foreach ($livres as $key => $livre) {
                        echo "<option value=\"" . $livre["id_livre"] . "\">" . $livre["titre"] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="container">
                <label for="id_etudiant"> Etudiant </label>
                <select id="id_etudiant" name="id_etudiant">
                    <option value="" selected>Sélectionner un etudiant</option>
                    <?php
                    foreach ($etudiants as $key => $etudiant) {
                        echo "<option value=\"" . $etudiant["id_étudiant"] . "\">" . $etudiant["nom"] . " " . $etudiant["prénom"] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="container">
                <label for="date_emprunt"> Date de l'emprunt </label>
                <input id="date_emprunt" type="date" name="date_emprunt" required />
            </div>

            <div class="container">
                <label for="date_retour_prévue"> Date de retour prévue du livre emprunté </label>
                <input id="date_retour_prévue" type="date" name="date_retour_prévue" required />
            </div>

            <div class="button-container container">
                <button type="submit">
                    Enregister
                </button>
                <button type="reset">
                    Annuler
                </button>
            </div>
        </fieldset>
    </form>
    <script>
        const urlParams = new URLSearchParams(window.location.search)

        const invalidInput = urlParams.get('invalid_input')
        if (invalidInput) {
            alert('Toute les informations sont requises.')
        }

        const error = urlParams.get('error')
        if (error) {
            alert('Impossible d\'enregistrer un emprunt, veuillez réessayer plus tard.')
        }

        const success = urlParams.get('success')
        if (success) {
            alert('Données enregistrer avec succès.')
        }
    </script>
</body>

</html>