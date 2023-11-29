<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./edit.php?id_emprunt=' . $emprunt["id_emprunt"] . '&error=1');
    die();
}

$emprunt["id_emprunt"] = intval(htmlspecialchars(strip_tags($_GET['id_emprunt'])));

$ps = $cn->prepare('SELECT * FROM `Emprunts` WHERE id_emprunt=:idEmprunt;');
$ps->bindParam(':idEmprunt', $emprunt['id_emprunt']);
$ps->execute();

$emprunt = $ps->fetchAll(PDO::FETCH_ASSOC)[0];


$ps = $cn->prepare('SELECT * FROM `Livres`;');
$ps->execute();
$livres = $ps->fetchAll(PDO::FETCH_ASSOC);

$ps = $cn->prepare('SELECT * FROM `Livres`;');
$ps->execute();
$livres = $ps->fetchAll(PDO::FETCH_ASSOC);

$ps = $cn->prepare('SELECT * FROM `Étudiants`;');
$ps->execute();
$etudiants = $ps->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all element are filled and not empty and date_emprunt <= date_retour_prévue and date_emprunt <= date_retour_effective
    if (empty($_POST['id_livre']) || empty($_POST['id_etudiant']) || empty($_POST['date_emprunt']) || empty($_POST['date_retour_prévue']) || empty($_POST['date_retour_effective']) || $_POST['date_emprunt'] > $_POST['date_retour_prévue'] || $_POST['date_emprunt'] > $_POST['date_retour_effective']) {
        header('Location: ./edit.php?id_emprunt=' . $emprunt["id_emprunt"] . '&invalid_input=1');
        die();
    } else {
        $emprunt["id_livre"]  = htmlspecialchars(strip_tags($_POST["id_livre"]));
        $emprunt["id_etudiant"]  = htmlspecialchars(strip_tags($_POST["id_etudiant"]));
        $emprunt["date_emprunt"] = htmlspecialchars(strip_tags($_POST["date_emprunt"]));
        $emprunt["date_retour_prévue"]  = htmlspecialchars(strip_tags($_POST["date_retour_prévue"]));
        $emprunt["date_retour_effective"]  = htmlspecialchars(strip_tags($_POST["date_retour_effective"]));

        $query = 'UPDATE `Emprunts` SET id_livre=:idLivre, id_etudiant=:idEtudiant, date_emprunt=:dateEmprunt, date_retour_prévue=:dateRetourPrevue, date_retour_effective=:dateRetourEffective WHERE id_emprunt=:idEmprunt;';
        $ps = $cn->prepare($query);
        $ps->bindParam(':idEmprunt', $emprunt["id_emprunt"], PDO::PARAM_INT);
        $ps->bindParam(':idLivre', $emprunt["id_livre"], PDO::PARAM_INT);
        $ps->bindParam(':idEtudiant', $emprunt["id_etudiant"], PDO::PARAM_INT);
        $ps->bindParam(':dateEmprunt', $emprunt["date_emprunt"]);
        $ps->bindParam(':dateRetourPrevue', $emprunt["date_retour_prévue"]);
        $ps->bindParam(':dateRetourEffective', $emprunt["date_retour_effective"]);


        if ($ps->execute()) {
            header('Location: ./liste.php?create_success=1');
            var_dump("Cool");
            die();
        } else {
            header('Location: ./edit.php?id_emprunt=' . $emprunt["id_emprunt"] . '&error=1');
            die();
        }
    }
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
                <select id="id_livre" name="id_livre" value="<?= $emprunt["id_livre"] ?>">
                    <option value="" selected>Sélectionner une livre</option>
                    <?php
                    foreach ($livres as $key => $livre) {
                        if ($livre["id_livre"] == $emprunt["id_livre"]) {
                            echo "<option value=\"" . $livre["id_livre"] . "\" selected>" . $livre["titre"] . "</option>";
                        } else {
                            echo "<option value=\"" . $livre["id_livre"] . "\">" . $livre["titre"] . "</option>";
                        }
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
                        if ($etudiant["id_étudiant"] == $emprunt["id_etudiant"]) {
                            echo "<option value=\"" . $etudiant["id_étudiant"] . "\" selected>" . $etudiant["nom"] . " " . $etudiant["prénom"] . "</option>";
                        } else {
                            echo "<option value=\"" . $etudiant["id_étudiant"] . "\">" . $etudiant["nom"] . " " . $etudiant["prénom"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="container">
                <label for="date_emprunt"> Date de l'emprunt </label>
                <input id="date_emprunt" type="date" name="date_emprunt" value="<?= $emprunt["date_emprunt"] ?>" required />
            </div>

            <div class="container">
                <label for="date_retour_prévue"> Date de retour prévue du livre emprunté </label>
                <input id="date_retour_prévue" type="date" name="date_retour_prévue" value="<?= $emprunt["date_retour_prévue"] ?>" required />
            </div>

            <div class="container">
                <label for="date_retour_effective"> Date de retour effectif du livre emprunté </label>
                <input id="date_retour_effective" type="date" name="date_retour_effective" value="<?= $emprunt["date_retour_effective"] ?>" required />
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
            alert('Impossible de modifier l\'emprunt, veuillez réessayer plus tard.')
        }

        const success = urlParams.get('success')
        if (success) {
            alert('Données enregistrer avec succès.')
        }
    </script>
</body>

</html>