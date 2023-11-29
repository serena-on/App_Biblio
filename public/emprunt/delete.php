<?php
require_once('../../include/db.inc.php');

$cn = createConnection();
if (!$cn) {
    header('Location: ./liste.php?error=1');
    die();
}

$emprunt = array(
    'id_emprunt' => intval(htmlspecialchars(strip_tags($_GET['id']))),
);

$query = 'DELETE FROM `Emprunts` WHERE id_emprunt = :id_emprunt;';
$ps = $cn->prepare($query);
$ps->bindParam(':id_emprunt', $emprunt['id_emprunt']);

if ($ps->execute()) {
    header('Location: ./liste.php?delete_success=1');
    die();
} else {
    header('Location: ./liste.php?error=1');
    die();
}
?>