<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./create.php?error=1');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['titre']) || empty($_POST['auteur']) || empty($_POST['résumé']) || empty($_POST['disponibilité']) || empty($_POST['localisation'])) {
        header('Location: ./create.php?invalid_input=1');
        die();
    } else {
        $livre = array(
            "titre"  => htmlspecialchars(strip_tags($_POST["titre"])),
            "auteur"  => htmlspecialchars(strip_tags($_POST["auteur"])),
            "résumé"  => htmlspecialchars(strip_tags($_POST["résumé"])),
            "disponibilité" => boolval(htmlspecialchars(strip_tags($_POST["disponibilité"]))),
            "localisation"  => htmlspecialchars(strip_tags($_POST["localisation"]))
        );

        $query = 'INSERT INTO `Livres`(titre,auteur,résumé,disponibilité,localisation) VALUES (:titre, :auteur, :theresume, :disponibilite, :localisation);';
        $ps = $cn->prepare($query);
        $ps->bindParam(':titre', $livre["titre"]);
        $ps->bindParam(':auteur', $livre["auteur"]);
        $ps->bindParam(':theresume', $livre["résumé"]);
        $ps->bindParam(':disponibilite', $livre["disponibilité"]);
        $ps->bindParam(':localisation', $livre["localisation"]);

        if ($ps->execute()) {
            header('Location: ./liste.php?create_success=1');
            die();
        } else {
            header('Location: ./create.php?error=1');
            die();
        }
    }
}
// else {
// $disponibilite = $cn->prepare("SELECT SUBSTRING(COLUMN_TYPE,5) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='gestion_biblio' AND TABLE_NAME='Livres' AND COLUMN_NAME='disponibilité';");
// $disponibilite = $disponibilite -> execute();
// $disponibilite
// 
// }
?>
<!DOCTYPE html>
<html lang="fr-BJ">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="../bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form method="post">
        <fieldset>
            <legend>Ajouter un livre</legend>
            <div class="container">
                <label for="titre"> Titre du livre </label>
                <input id="titre" type="text" name="titre" />
            </div>

            <div class="container">
                <label for="auteur"> Auteur du livre </label>
                <input id="auteur" type="text" name="auteur" />
            </div>

            <div class="container">
                <label for="résumé"> Résumé du livre </label>
                <textarea id="résumé" type="" name="résumé"></textarea>
            </div>

            <div class="container">
                <label for="disponible"> Disponible: </label>
                <input id="disponible" type="radio" name="disponibilité" value="disponible" />
                <label for="emprunté"> Emprunté: </label>
                <input id="emprunté" type="radio" name="disponibilité" value="emprunté" />

                <!-- SELECT SUBSTRING(COLUMN_TYPE,5)
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA='gestion_biblio'
                AND TABLE_NAME='Livres'
                AND COLUMN_NAME='disponibilité'; -->
            </div>

            <div class="container">
                <label for="localisation"> Localisation du livre </label>
                <input id="localisation" type="text" name="localisation" />
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