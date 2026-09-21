<?php
require_once 'config/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT l.*, c.libelle as categorie_nom, 
            GROUP_CONCAT(CONCAT(a.prenom, ' ', a.nom) SEPARATOR ', ') as auteurs
            FROM livre l
            JOIN categorie c ON l.id_categorie = c.id_categorie
            LEFT JOIN livre_auteur la ON l.id_livre = la.id_livre
            LEFT JOIN auteur a ON la.id_auteur = a.id_auteur
            WHERE l.titre LIKE ?
            GROUP BY l.id_livre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%"]);
} else {
    $sql = "SELECT l.*, c.libelle as categorie_nom, 
            GROUP_CONCAT(CONCAT(a.prenom, ' ', a.nom) SEPARATOR ', ') as auteurs
            FROM livre l
            JOIN categorie c ON l.id_categorie = c.id_categorie
            LEFT JOIN livre_auteur la ON l.id_livre = la.id_livre
            LEFT JOIN auteur a ON la.id_auteur = a.id_auteur
            GROUP BY l.id_livre";
    $stmt = $pdo->query($sql);
}
$livres = $stmt->fetchAll();

include 'includes/header.php';
?>

<h2>Catalogue des Livres</h2>

<form method="GET" action="livres.php" class="search-form">
    <input type="text" name="search" placeholder="Rechercher par titre..." value="<?= htmlspecialchars($search); ?>">
    <button type="submit">Rechercher</button>
    <?php if ($search !== ''): ?>
        <a href="livres.php" class="btn-reset">Réinitialiser</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Auteur(s)</th>
            <th>Catégorie</th>
            <th>Année</th>
            <th>ISBN</th>
            <th>Disponibilité</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($livres) > 0): ?>
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= htmlspecialchars($livre['titre']); ?></td>
                    <td><?= htmlspecialchars($livre['auteurs'] ?? 'Inconnu'); ?></td>
                    <td><?= htmlspecialchars($livre['categorie_nom']); ?></td>
                    <td><?= htmlspecialchars($livre['annee_publication']); ?></td>
                    <td><?= htmlspecialchars($livre['isbn']); ?></td>
                    <td>
                        <?php if ($livre['disponible']): ?>
                            <span class="badge disponible">Disponible</span>
                        <?php else: ?>
                            <span class="badge emprunte">Emprunté</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Aucun livre trouvé.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>