<!-- Gestion des comptes (admin) -->
<h1>Gestion des comptes</h1>

<!-- Message flash -->
<?php if (!empty($_SESSION['message'])): ?>
    <div role="alert">
        <p><?= htmlspecialchars($_SESSION['message']) ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<p><a href="/admin/employe/creer">+ Creer un compte employe</a></p>

<table class="stats-table">
    <thead>
        <tr>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Role</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comptes as $compte): ?>
            <tr>
                <td><?= htmlspecialchars($compte['pseudo']) ?></td>
                <td><?= htmlspecialchars($compte['email']) ?></td>
                <td><?= htmlspecialchars($compte['role']) ?></td>
                <td><?= htmlspecialchars($compte['statut']) ?></td>
                <td>
                    <?php if ($compte['statut'] === 'actif'): ?>
                        <!-- Bouton suspendre -->
                        <form action="/admin/compte/<?= htmlspecialchars((string) $compte['id_utilisateur']) ?>/suspendre" method="post" style="display:inline; background:none; border:none; padding:0; max-width:none;">
                            <button type="submit">Suspendre</button>
                        </form>
                    <?php else: ?>
                        <!-- Bouton reactiver -->
                        <form action="/admin/compte/<?= htmlspecialchars((string) $compte['id_utilisateur']) ?>/reactiver" method="post" style="display:inline; background:none; border:none; padding:0; max-width:none;">
                            <button type="submit">Reactiver</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
