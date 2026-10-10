<?php if ($message = session()->getFlashdata('success')): ?>
    <p role="status"><?= esc($message) ?></p>
<?php endif; ?>

<?php if ($message = session()->getFlashdata('error')): ?>
    <p role="alert"><?= esc($message) ?></p>
<?php endif; ?>

<?php $errors = session()->getFlashdata('errors'); ?>
<?php if (is_array($errors) && $errors !== []): ?>
    <ul role="alert">
        <?php foreach ($errors as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
