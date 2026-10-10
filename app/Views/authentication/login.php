<?= $this->include('partials/header', ['title' => 'Staff login']) ?>
<?= $this->include('partials/messages') ?>

<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <p>
        <label for="username">Username</label><br>
        <input id="username" name="username" type="text" maxlength="50" required autocomplete="username" value="<?= esc(old('username') ?? '') ?>">
    </p>
    <p>
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required autocomplete="current-password">
    </p>
    <button type="submit">Log in</button>
</form>

<?= $this->include('partials/footer') ?>
