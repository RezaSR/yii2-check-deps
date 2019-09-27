<?php

use rsr\yii2\checkDeps\CheckDeps;
use yii\helpers\Html;
use yii\bootstrap\Alert;
use rsr\yii2\checkDeps\Asset;
use yii\helpers\Url;

/* @var $this yii\web\View */

$checkDeps = CheckDeps::getComponentInstance();
$neededProcess = $checkDeps->getNeededProcess();

Asset::register($this);
?>

<div id="overlay" class="overlay">
	<div class="overlay-loading">Loading...</div>
	<div id="overlay-content" class="overlay-content">
		<pre></pre>
		<button id="btn-continue" class="btn btn-primary">Continue</button>
	</div>
</div>

<?php if ($neededProcess === false): ?>
<?php print_r($neededProcess['newMigrations']); ?>
	<p>Nothing to do.</p>
	<?= Html::a('Go to home', Yii::$app->homeUrl, [
	    'class' => 'btn btn-primary',
	]) ?>
<?php elseif (isset($neededProcess['newMigrations'])): ?>
	<?php
    	$numOfMigrations = count($neededProcess['newMigrations']);
    
    	$alertBody = ($numOfMigrations > 1) ? "There are $numOfMigrations new migrations" : "There is $numOfMigrations new migration";
    	echo Alert::widget([
    	    'body' => $alertBody,
    	    'closeButton' => false,
    	    'options' => [
    	        'class' => 'alert-warning',
    	    ]
    	]);
	?>

    <p>What do you what to do with the following new <?= ($numOfMigrations > 1) ? 'migrations' : 'migration' ?>:</p>

    <ol>
        <?php
            foreach ($neededProcess['newMigrations'] as $migration) {
                echo Html::tag('li', $migration);
            }
        ?>
    </ol>

    <div>
    	<?= Html::button('Continue Without Changes', [
    	    'class' => 'btn btn-info',
    	    'id' => 'btn-ignore',
    	    'data-url' => Url::to([$checkDeps->getControllerId() . '/ignore'], true),
    	    'data-migrations' => serialize($neededProcess['newMigrations'])
    	]) ?>
        <?= Html::button('Install Migrations', [
            'class' => 'btn btn-primary',
            'id' => 'btn-apply',
            'data-url' => Url::to([$checkDeps->getControllerId() . '/apply'], true)
        ]) ?>
    </div>
<?php endif; ?>