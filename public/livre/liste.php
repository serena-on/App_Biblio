<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./liste.php?error=1');
    die();
}

$ps = $cn->prepare('SELECT * FROM `Livres`;');
$ps->execute();
$result = $ps->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr-BJ">

<head>
    <meta charset="UTF-8">
    <title>Liste livre</title>
    <link rel="stylesheet" href="../bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
    <script src="../bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" async>
        function delete_livre(id) {
            if (confirm('Voulez-vous vraiment supprimer ce livre ?')) {
                window.location.href = './delete.php?id=' + id
            }
        }
    </script>
</head>

<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Titre</th>
                <th scope="col">Auteur</th>
                <th scope="col">Résumé</th>
                <th scope="col">Disponibilité</th>
                <th scope="col">Localisation</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($result) == 0) {
                echo '<tr><td colspan="4">Nothing to display</td></tr>';
            } else {
                foreach ($result as $row) {
                    // "titre"
                    // "auteur"
                    // "résumé"
                    // "disponibilité"
                    // "localisation"


                    echo '<tr>';
                    echo '<td>' . htmlentities($row["titre"]) . '</td>';
                    echo '<td>' . htmlentities($row["auteur"]) . '</td>';
                    echo '<td>' . htmlentities($row["résumé"]) . '</td>';
                    echo '<td>' . htmlentities($row["disponibilité"]) . '</td>';
                    echo '<td>' . htmlentities($row["localisation"]) . '</td>';
                    echo '<td>
                            <button type="button" class="btn btn-primary" onclick="window.location.href=\'./edit.php?id_livre=' . $row['id_livre'] . '\'">Modifier</button>
                            <button type="button" class="btn btn-danger" onclick="delete_livre(' . $row['id_livre'] . ')">Supprimer</button>
                          </td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <script>
        const urlParams = new URLSearchParams(window.location.search)

        const error = urlParams.get('error')
        if (error) {
            header("HTTP/1.0 500 Internal Server Error");
        }

        if (urlParams.get('update_success')) {
            alert('Données mise à jour avec succès.')
        }

        if (urlParams.get('create_success')) {
            alert('Livre enregistrer avec succès.')
        }

        if (urlParams.get('delete_success')) {
            alert('Livre supprimer avec succès.')
        }
    </script>
</body>

</html>