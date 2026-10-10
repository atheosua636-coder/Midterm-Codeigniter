<?php $isEdit = ! empty($customer); ?>
<?= $this->include('partials/header', ['title' => $isEdit ? 'Edit customer' : 'Add customer']) ?>
<?= $this->include('partials/messages') ?>

<form action="<?= site_url($isEdit ? 'customers/update/' . $customer['id'] : 'customers') ?>" method="post">
    <?= csrf_field() ?>
    <p>
        <label for="full_name">Full name</label><br>
        <input id="full_name" name="full_name" type="text" maxlength="100" required value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>">
    </p>
    <p>
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" maxlength="100" required value="<?= esc(old('email', $customer['email'] ?? '')) ?>">
    </p>
    <p>
        <label for="phone">Phone (optional)</label><br>
        <input id="phone" name="phone" type="tel" maxlength="20" value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>">
    </p>
    <button type="submit"><?= $isEdit ? 'Save changes' : 'Add customer' ?></button>
    <a href="<?= site_url('customers') ?>">Cancel</a>
</form>

<?= $this->include('partials/footer') ?>
