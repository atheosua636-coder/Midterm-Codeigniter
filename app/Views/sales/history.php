<?= $this->include('partials/header', ['title' => 'Sales history']) ?>
<?= $this->include('partials/messages') ?>

<?php if (empty($sales)): ?>
    <p>No sales have been recorded.</p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Sale ID</th><th>Product</th><th>Customer</th><th>Staff</th><th>Quantity</th><th>Total</th><th>Date</th></tr>
        </thead>
        <tbody>
        <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?= esc($sale['id']) ?></td>
                <td><?= esc($sale['product_name']) ?></td>
                <td><?= esc($sale['customer_name'] ?? '—') ?></td>
                <td><?= esc($sale['staff_name']) ?></td>
                <td><?= esc($sale['quantity']) ?></td>
                <td><?= esc(number_format((float) $sale['total_price'], 2)) ?></td>
                <td><?= esc($sale['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->include('partials/footer') ?>
