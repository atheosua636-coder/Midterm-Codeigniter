<?= $this->include('partials/header', ['title' => 'Record sale']) ?>
<?= $this->include('partials/messages') ?>

<?php if (empty($products)): ?>
    <p>There are no products in stock. Add stock before recording a sale.</p>
<?php else: ?>
    <form action="<?= site_url('sales') ?>" method="post">
        <?= csrf_field() ?>
        <p>
            <label for="product_id">Product</label><br>
            <select id="product_id" name="product_id" required>
                <option value="">Choose a product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= esc($product['id']) ?>" <?= (string) old('product_id') === (string) $product['id'] ? 'selected' : '' ?>>
                        <?= esc($product['name']) ?> — <?= esc(number_format((float) $product['price'], 2)) ?> (stock: <?= esc($product['stock_quantity']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="customer_id">Customer (optional)</label><br>
            <select id="customer_id" name="customer_id">
                <option value="">No customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= esc($customer['id']) ?>" <?= (string) old('customer_id') === (string) $customer['id'] ? 'selected' : '' ?>>
                        <?= esc($customer['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="quantity">Quantity</label><br>
            <input id="quantity" name="quantity" type="number" min="1" step="1" required value="<?= esc(old('quantity', '1')) ?>">
        </p>
        <button type="submit">Record sale</button>
    </form>
<?php endif; ?>

<?= $this->include('partials/footer') ?>
