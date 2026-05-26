<div class="card">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
        <div class="form-row">
            <?php echo $form->labelEx($author, 'name'); ?>
            <?php echo $form->textField($author, 'name'); ?>
            <?php echo $form->error($author, 'name'); ?>
        </div>
        <div class="form-row">
            <?php echo $form->labelEx($author, 'bio'); ?>
            <?php echo $form->textArea($author, 'bio'); ?>
            <?php echo $form->error($author, 'bio'); ?>
        </div>
        <button class="button" type="submit">Save</button>
    <?php $this->endWidget(); ?>
</div>
