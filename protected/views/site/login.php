<div class="card">
    <h2>Login</h2>
    <?php $form = $this->beginWidget('CActiveForm'); ?>
        <div class="form-row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username'); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>
        <div class="form-row">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password'); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
        <button class="button" type="submit">Login</button>
    <?php $this->endWidget(); ?>
</div>
