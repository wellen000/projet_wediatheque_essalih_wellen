<?php
require_once 'config/db.php';
$stmt = $pdo->query("SELECT * FROM adherent ORDER BY nom, prenom");
$adherents = $stmt->fetchAll();

include 'includes/header.php';
?>

<h2>Liste des Adhérents</h2>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Date d'inscription</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($adherents as $adherent): ?>
            <tr>
                <td><?= htmlspecialchars($adherent['nom']); ?></td>
                <td><?= htmlspecialchars($adherent['prenom']); ?></td>
                <td><?= htmlspecialchars($adherent['email']); ?></td>
                <td><?= htmlspecialchars($adherent['date_inscription']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>