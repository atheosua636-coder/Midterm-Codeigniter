<?= $this->include('partials/header', ['title' => 'Staff']) ?>
<?= $this->include('partials/messages') ?>

<p><a href="<?= site_url('staff/new') ?>">Add staff account</a></p>

<?php if (empty($staff)): ?>
    <p>No staff accounts found.</p>
<?php else: ?>
    <table>
        <thead><tr><th>ID</th><th>Avatar</th><th>Username</th><th>Full name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($staff as $person): ?>
            <tr>
                <td><?= esc($person['id']) ?></td>
                <td>
                    <?php if (! empty($person['avatar'])): ?>
                        <img src="<?= base_url('uploads/avatars/' . rawurlencode($person['avatar'])) ?>" alt="Avatar for <?= esc($person['full_name']) ?>" width="60">
                    <?php endif; ?>
                </td>
                <td><?= esc($person['username']) ?></td>
                <td><?= esc($person['full_name']) ?></td>
                <td>
                    <a href="<?= site_url('staff/edit/' . $person['id']) ?>">Edit</a>
                    <form action="<?= site_url('staff/delete/' . $person['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->include('partials/footer') ?>
