<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./liste.php?error=1');
    die();
}

$livre = array(
    'id_livre' => intval(htmlspecialchars(strip_tags($_GET['id']))),
);

$query = 'DELETE FROM `Livres` WHERE id_livre = :id_livre;';
$ps = $cn->prepare($query);
$ps->bindParam(':id_livre', $livre['id_livre']);

if ($ps->execute()) {
    header('Location: ./liste.php?delete_success=1');
    die();
} else {
    header('Location: ./liste.php?error=1');
    die();
}
?>