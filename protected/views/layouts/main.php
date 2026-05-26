<?php /** @var Controller $this */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
</head>
<body>
<header>
    <h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>
    <nav>
        <?php echo CHtml::link('Books', ['/book/index']); ?>
        <?php echo CHtml::link('Authors', ['/author/index']); ?>
        <?php echo CHtml::link('Top Authors', ['/report/topAuthors']); ?>
        <?php if (Yii::app()->user->checkAccess('admin')): ?>
            <?php echo CHtml::link('SMS Logs', ['/log/index']); ?>
        <?php endif; ?>
        <?php if (Yii::app()->user->isGuest): ?>
            <?php echo CHtml::link('Login', ['/site/login']); ?>
        <?php else: ?>
            <?php echo CHtml::link('Logout (' . CHtml::encode(Yii::app()->user->name ?: Yii::app()->user->getState('login')) . ')', ['/site/logout']); ?>
        <?php endif; ?>
    </nav>
</header>
<main>
    <?php foreach (['success', 'warning', 'error'] as $flashType): ?>
        <?php if (Yii::app()->user->hasFlash($flashType)): ?>
            <div class="flash-<?php echo $flashType; ?>"><?php echo CHtml::encode(Yii::app()->user->getFlash($flashType)); ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php echo $content; ?>
</main>
</body>
</html>
