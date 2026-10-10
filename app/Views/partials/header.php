<?php $title = $title ?? 'Point of Sale'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/css.css') ?>">
</head>
<body>
<header>
    <h1><?= esc($title) ?></h1>
    <?php if (session()->get('staff_id')): ?>
        <nav>
            <a href="<?= site_url('products') ?>">Products</a> |
            <a href="<?= site_url('customers') ?>">Customers</a> |
            <a href="<?= site_url('staff') ?>">Staff</a> |
            <a href="<?= site_url('sales/new') ?>">Record sale</a> |
            <a href="<?= site_url('sales/history') ?>">Sales history</a>
        </nav>
        <p>Signed in as <?= esc(session()->get('staff_name') ?? '') ?></p>
        <form action="<?= site_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit">Log out</button>
        </form>
    <?php endif; ?>
</header>
<main>
