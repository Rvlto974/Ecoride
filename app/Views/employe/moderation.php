<!-- Espace employe : moderation des avis -->
<h1>Moderation des avis</h1>
<p>Validez ou refusez les avis laisses par les passagers.</p>

<!-- Message flash (apres validation/refus) -->
<?php if (!empty($_SESSION['message'])): ?>
    <div role="alert">
        <p><?= htmlspecialchars($_SESSION['message']) ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (empty($avis)): ?>
    <p>Aucun avis en attente de moderation.</p>
<?php else: ?>
    <ul>
        <?php foreach ($avis as $unAvis): ?>
            <li>
                <strong>Note : <?= htmlspecialchars((string) $unAvis['note']) ?>/5</strong><br>
                Passager : <?= htmlspecialchars($unAvis['pseudo_passager']) ?><br>
                Chauffeur concerne : <?= htmlspecialchars($unAvis['pseudo_chauffeur']) ?><br>
                Commentaire : <?= htmlspecialchars($unAvis['commentaire']) ?><br>

                <!-- Boutons de moderation : chaque action est un mini formulaire POST -->
                <form action="/employe/avis/<?= htmlspecialchars((string) $unAvis['_id']) ?>/valider" method="post" style="display:inline; background:none; border:none; padding:0; max-width:none;">
                    <button type="submit">Valider</button>
                </form>
                <form action="/employe/avis/<?= htmlspecialchars((string) $unAvis['_id']) ?>/refuser" method="post" style="display:inline; background:none; border:none; padding:0; max-width:none;">
                    <button type="submit">Refuser</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
