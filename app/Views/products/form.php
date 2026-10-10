<?php $isEdit = ! empty($product); ?>
<?= $this->include('partials/header', ['title' => $isEdit ? 'Edit product' : 'Add product']) ?>
<?= $this->include('partials/messages') ?>

<form action="<?= site_url($isEdit ? 'products/update/' . $product['id'] : 'products') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" maxlength="100" required value="<?= esc(old('name', $product['name'] ?? '')) ?>">
    </p>
    <p>
        <label for="price">Price</label><br>
        <input id="price" name="price" type="number" min="0" step="0.01" required value="<?= esc(old('price', $product['price'] ?? '')) ?>">
    </p>
    <p>
        <label for="stock_quantity">Stock quantity</label><br>
        <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" required value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? '0')) ?>">
    </p>
    <p>
        <label for="image">Product image (JPG, PNG, or WEBP; up to 2 MB)</label><br>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    </p>
    <?php if ($isEdit && ! empty($product['image'])): ?>
        <p>Current image:<br><img src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>" alt="Current product image" width="120"></p>
    <?php endif; ?>
    <button type="submit"><?= $isEdit ? 'Save changes' : 'Add product' ?></button>
    <a href="<?= site_url('products') ?>">Cancel</a>
</form>

<?= $this->include('partials/footer') ?>
