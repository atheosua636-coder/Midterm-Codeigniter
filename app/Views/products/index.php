<?= $this->include('partials/header', ['title' => 'Products']) ?>
<?= $this->include('partials/messages') ?>

<p><a href="<?= site_url('products/new') ?>">Add product</a></p>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= esc($product['id']) ?></td>
                <td>
                    <?php if (! empty($product['image'])): ?>
                        <img src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>" alt="<?= esc($product['name']) ?>" width="80">
                    <?php endif; ?>
                </td>
                <td><?= esc($product['name']) ?></td>
                <td><?= esc(number_format((float) $product['price'], 2)) ?></td>
                <td><?= esc($product['stock_quantity']) ?></td>
                <td>
                    <a href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a>
                    <form action="<?= site_url('products/delete/' . $product['id']) ?>" method="post">
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
