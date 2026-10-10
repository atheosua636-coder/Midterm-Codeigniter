<?= $this->include('partials/header', ['title' => 'Customers']) ?>
<?= $this->include('partials/messages') ?>

<p><a href="<?= site_url('customers/new') ?>">Add customer</a></p>

<?php if (empty($customers)): ?>
    <p>No customers found.</p>
<?php else: ?>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= esc($customer['id']) ?></td>
                <td><?= esc($customer['full_name']) ?></td>
                <td><?= esc($customer['email']) ?></td>
                <td><?= esc($customer['phone'] ?? '') ?></td>
                <td>
                    <a href="<?= site_url('customers/edit/' . $customer['id']) ?>">Edit</a>
                    <form action="<?= site_url('customers/delete/' . $customer['id']) ?>" method="post">
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
