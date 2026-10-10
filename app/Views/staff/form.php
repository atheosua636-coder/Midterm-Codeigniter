<?php $isEdit = ! empty($staff); ?>
<?= $this->include('partials/header', ['title' => $isEdit ? 'Edit staff account' : 'Add staff account']) ?>
<?= $this->include('partials/messages') ?>

<form action="<?= site_url($isEdit ? 'staff/update/' . $staff['id'] : 'staff') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <p>
        <label for="username">Username</label><br>
        <input id="username" name="username" type="text" maxlength="50" required value="<?= esc(old('username', $staff['username'] ?? '')) ?>">
    </p>
    <p>
        <label for="full_name">Full name</label><br>
        <input id="full_name" name="full_name" type="text" maxlength="100" required value="<?= esc(old('full_name', $staff['full_name'] ?? '')) ?>">
    </p>
    <p>
        <label for="password">Password <?= $isEdit ? '(leave blank to keep current password)' : '(at least 8 characters)' ?></label><br>
        <input id="password" name="password" type="password" <?= $isEdit ? '' : 'required' ?> minlength="8" autocomplete="new-password">
    </p>
    <p>
        <label for="avatar">Avatar (JPG, PNG, or WEBP; up to 2 MB)</label><br>
        <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp">
    </p>
    <?php if ($isEdit && ! empty($staff['avatar'])): ?>
        <p>Current avatar:<br><img src="<?= base_url('uploads/avatars/' . rawurlencode($staff['avatar'])) ?>" alt="Current avatar" width="100"></p>
    <?php endif; ?>
    <button type="submit"><?= $isEdit ? 'Save changes' : 'Add staff account' ?></button>
    <a href="<?= site_url('staff') ?>">Cancel</a>
</form>

<?= $this->include('partials/footer') ?>
