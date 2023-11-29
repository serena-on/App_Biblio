<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./liste.php?error=1');
    die();
}

$itemsPerPage = 2;

$page = isset($_GET['page']) ?  intval(htmlspecialchars(strip_tags($_GET['page']))) : 1;

if (!empty($_GET['searchByDate'])) {
    $searchByDate = htmlspecialchars(strip_tags($_GET['searchByDate']));
    $ps = $cn->prepare('SELECT id_emprunt, nom, prénom, titre, date_retour_prévue FROM `Emprunts`, `Étudiants`, `Livres` WHERE `Étudiants`.id_étudiant =`Emprunts`.id_etudiant AND `Emprunts`.id_livre = `Livres`.id_livre AND datediff(`Emprunts`.date_emprunt,:searchByDate) = 0 LIMIT :start, :limit;');
    $ps->bindParam(':searchByDate', $searchByDate);
} else {
    $ps = $cn->prepare('SELECT id_emprunt, nom, prénom, titre, date_retour_prévue FROM `Emprunts`, `Étudiants`, `Livres` WHERE `Étudiants`.id_étudiant =`Emprunts`.id_etudiant AND `Emprunts`.id_livre = `Livres`.id_livre LIMIT :start, :limit;');
}
$start = ($page - 1) * $itemsPerPage;
$ps->bindParam(':start', $start, PDO::PARAM_INT);
$ps->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$ps->execute();
$result = $ps->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_GET['searchByDate'])) {
    $psCount = $cn->prepare('SELECT COUNT(*) FROM `Emprunts` WHERE datediff(`Emprunts`.date_emprunt,:searchByDate) = 0');
    $psCount->bindParam(':searchByDate', $searchByDate);
} else {
    $psCount = $cn->prepare('SELECT COUNT(*) FROM `Emprunts`');
}
$psCount->execute();
$totalItems = $psCount->fetchColumn();

$totalPages = ceil($totalItems / $itemsPerPage);
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
        function delete_emprunt(id) {
            if (confirm('Voulez-vous vraiment supprimer cet emprunt ?')) {
                window.location.href = './delete.php?id=' + id
            }
        }
    </script>
</head>

<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Titre du livre</th>
                <th scope="col">Nom et Prénom de l'emprunteur</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($result) == 0) {
                echo '<tr><td colspan="4">Nothing to display</td></tr>';
            } else {
                foreach ($result as $row) {
                    echo '<tr';
                    if (date('Y-m-d') == date('Y-m-d', strtotime($row['date_retour_prévue']))) {
                        echo ' class="table-danger"';
                    }
                    echo '>';
                    echo '<td>' . htmlentities($row["titre"]) . '</td>';
                    echo '<td>' . htmlentities($row["nom"]) . ' ' . htmlentities($row["prénom"]) . '</td>';
                    echo '<td>
                            <button type="button" class="btn btn-primary" onclick="window.location.href=\'./edit.php?id_emprunt=' . $row['id_emprunt'] . '\'">Modifier</button>
                            <button type="button" class="btn btn-danger" onclick="delete_emprunt(' . $row['id_emprunt'] . ')">Supprimer</button>
                          </td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <div class="container">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
                }

                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }

                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>


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