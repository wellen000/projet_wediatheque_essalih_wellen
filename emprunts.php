<?php
require_once 'config/db.php';

if (isset($_POST['id_emprunt']) && isset($_POST['id_livre'])) {
    $id_emprunt = $_POST['id_emprunt'];
    $id_livre = $_POST['id_livre'];
    $date_jour = date('Y-m-d');

    try {
        $pdo->beginTransaction();

        $stmtRet = $pdo->prepare("UPDATE emprunt SET date_retour = ? WHERE id_emprunt = ?");
        $stmtRet->execute([$date_jour, $id_emprunt]);

        $stmtLiv = $pdo->prepare("UPDATE livre SET disponible = 1 WHERE id_livre = ?");
        $stmtLiv->execute([$id_livre]);

        $pdo->commit();
        header("Location: emprunts.php?success=1");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $err = "Erreur lors du retour : " . $e->getMessage();
    }
}

$sql = "SELECT e.id_emprunt, e.date_emprunt, e.date_retour_prevue, 
        a.nom, a.prenom, l.titre, l.id_livre
        FROM emprunt e
        JOIN adherent a ON e.id_adherent = a.id_adherent
        JOIN livre l ON e.id_livre = l.id_livre
        WHERE e.date_retour IS NULL
        ORDER BY e.date_retour_prevue ASC";
$emprunts = $pdo->query($sql)->fetchAll();

$date_actuelle = date('Y-m-d');

include 'includes/header.php';
?>

<h2>Gestion des Emprunts en Cours</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success">Le retour du livre a bien été enregistré.</div>
<?php endif; ?>
<?php if (isset($err)): ?>
    <div class="alert error"><?= htmlspecialchars($err); ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Adhérent</th>
            <th>Livre</th>
            <th>Date d'emprunt</th>
            <th>Date de retour prévue</th>
            <th>Statut / Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($emprunts) > 0): ?>
            <?php foreach ($emprunts as $emp): ?>
                <?php 
                    $enRetard = ($emp['date_retour_prevue'] < $date_actuelle);
                ?>
                <tr class="<?= $enRetard ? 'row-retard' : ''; ?>">
                    <td><?= htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']); ?></td>
                    <td><?= htmlspecialchars($emp['titre']); ?></td>
                    <td><?= htmlspecialchars($emp['date_emprunt']); ?></td>
                    <td><?= htmlspecialchars($emp['date_retour_prevue']); ?></td>
                    <td>
                        <?php if ($enRetard): ?>
                            <span class="badge retard">EN RETARD</span>
                        <?php else: ?>
                            <span class="badge en-cours">En cours</span>
                        <?php endif; ?>
                        
                        <form method="POST" action="emprunts.php" style="display:inline-block; margin-left: 10px;">
                            <input type="hidden" name="id_emprunt" value="<?= $emp['id_emprunt']; ?>">
                            <input type="hidden" name="id_livre" value="<?= $emp['id_livre']; ?>">
                            <button type="submit" class="btn-retour" onclick="return confirm('Confirmer le retour de ce livre ?');">Marquer comme rendu</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucun emprunt en cours.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>