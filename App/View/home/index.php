<?= 'Hallo Test'.$data['user']->name; ?>

<div class="jumbotron">
    <div class="container">
    <h1 class="display-3">SBB Billetautomat</h1>
    <p><?= 'Hallo '. $data['user']->name; ?></p>
    <p>
        <a class="btn btn-primary btn-lg" href="#" role="button">Billet kaufen</a>
    </p>
</div>