<?php
require_once 'config/db.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_adherent = $_POST['id_adherent'] ?? '';
    $id_livre = $_POST['id_livre'] ?? '';

    if (!empty($id_adherent) && !empty($id_livre)) {
        try {
            $pdo->beginTransaction();

            $check = $pdo->prepare("SELECT disponible FROM livre WHERE id_livre = ?");
            $check->execute([$id_livre]);
            $livre = $check->fetch();

            if ($livre && $livre['disponible'] == 1) {
                $date_emprunt = date('Y-m-d');
                $date_retour_prevue = date('Y-m-d', strtotime('+14 days'));

                $sqlEmprunt = "INSERT INTO emprunt (id_adherent, id_livre, date_emprunt, date_retour_prevue) VALUES (?, ?, ?, ?)";
                $stmtEmprunt = $pdo->prepare($sqlEmprunt);
                $stmtEmprunt->execute([$id_adherent, $id_livre, $date_emprunt, $date_retour_prevue]);

                $sqlUpdate = $pdo->prepare("UPDATE livre SET disponible = 0 WHERE id_livre = ?");
                $sqlUpdate->execute([$id_livre]);

                $pdo->commit();
                $message = "Emprunt enregistré avec succès ! (Retour prévu le $date_retour_prevue)";
            } else {
                $pdo->rollBack();
                $error = "Ce livre n'est plus disponible.";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez sélectionner un adhérent et un livre.";
    }
}

$adherents = $pdo->query("SELECT id_adherent, nom, prenom FROM adherent ORDER BY nom")->fetchAll();
$livresDispos = $pdo->query("SELECT id_livre, titre FROM livre WHERE disponible = 1 ORDER BY titre")->fetchAll();

include 'includes/header.php';
?>

<h2>Enregistrer un Nouvel Emprunt</h2>

<?php if ($message): ?>
    <div class="alert success"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" action="emprunter.php" class="form-emprunt">
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="id_adherent" style="display:block; margin-bottom:5px;">Adhérent :</label>
        <select name="id_adherent" id="id_adherent" required>
            <option value="">-- Choisir un adhérent --</option>
            <?php foreach ($adherents as $adh): ?>
                <option value="<?= $adh['id_adherent']; ?>"><?= htmlspecialchars($adh['nom'] . ' ' . $adh['prenom']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group" style="margin-bottom: 15px;">
        <label for="id_livre" style="display:block; margin-bottom:5px;">Livre disponible :</label>
        <select name="id_livre" id="id_livre" required>
            <option value="">-- Choisir un livre --</option>
            <?php foreach ($livresDispos as $l): ?>
                <option value="<?= $l['id_livre']; ?>"><?= htmlspecialchars($l['titre']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit">Valider l'emprunt</button>
</form>

<?php include 'includes/footer.php'; ?>