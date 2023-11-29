<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./edit.php?id_livre=' . strval($livre['id_livre']).'&error=1');
    die();
}

$livre = array(
    'id_livre' => intval(htmlspecialchars(strip_tags($_GET['id_livre']))),
);

$query = 'SELECT * FROM `Livres` WHERE id_livre = :id_livre;';
$ps = $cn->prepare($query);
$ps->bindParam(':id_livre', $livre['id_livre']);
$ps->execute();

$livre = $ps->fetchAll(PDO::FETCH_ASSOC)[0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $livre["titre"]  = htmlspecialchars(strip_tags($_POST["titre"]));
        $livre["auteur"]  = htmlspecialchars(strip_tags($_POST["auteur"]));
        $livre["résumé"]  = htmlspecialchars(strip_tags($_POST["résumé"]));
        $livre["disponibilité"] = htmlspecialchars(strip_tags($_POST["disponibilité"]));
        $livre["localisation"]  = htmlspecialchars(strip_tags($_POST["localisation"]));

    if ((empty($livre['titre']) || empty($livre['auteur']) || empty($livre['résumé']) || empty($livre['disponibilité']) || empty($livre['localisation'])) || ($livre['disponibilité'] != "disponible" && $livre['disponibilité'] != "emprunté")) {
        header('Location: ./edit.php?id_livre=' . strval($livre['id_livre']).'&invalid_input=1');
        die();
    }

    $query = 'UPDATE `Livres` SET titre = :titre, auteur = :auteur, résumé = :theresume, disponibilité = :disponibilite, localisation = :localisation WHERE id_livre = :id_livre;';
    $ps = $cn->prepare($query);
    $ps->bindParam(':id_livre', $livre['id_livre']);
    $ps->bindParam(':titre', $livre["titre"]);
    $ps->bindParam(':auteur', $livre["auteur"]);
    $ps->bindParam(':theresume', $livre["résumé"]);
    $ps->bindParam(':disponibilite', $livre["disponibilité"]);
    $ps->bindParam(':localisation', $livre["localisation"]);
    if ($ps->execute()) {
        header('Location: ./liste.php?update_success=1');
        die();
    } else {
        header('Location: ./liste.php?error=1');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="fr-BJ">

<head>
    <meta charset="UTF-8">
    <title>Edit Livre</title>
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
                <input id="titre" type="text" name="titre" value="<?= $livre['titre'] ?>" />
            </div>

            <div class="container">
                <label for="auteur"> Auteur du livre </label>
                <input id="auteur" type="text" name="auteur" value="<?= $livre['auteur'] ?>" />
            </div>

            <div class="container">
                <label for="résumé"> Résumé du livre </label>
                <textarea id="résumé" type="" name="résumé"><?= $livre['résumé'] ?></textarea>
            </div>

            <div class="container">
                <label for="disponible"> Disponible: </label>
                <input id="disponible" type="radio" name="disponibilité" value="disponible" <?= ($livre['disponibilité']=='disponible') ? " checked " : "";?> />
                <label for="emprunté"> Emprunté: </label>
                <input id="emprunté" type="radio" name="disponibilité" value="emprunté" <?= ($livre['disponibilité']=='emprunté') ? " checked " : "";?>/>
            </div>

            <div class="container">
                <label for="localisation"> Localisation du livre </label>
                <input id="localisation" type="text" name="localisation" value="<?= $livre['localisation'] ?>" />
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
            alert('Impossible de mettre à jour les donnée du joueur, veuillez réessayer plus tard.')
        }
    </script>
</body>

</html>