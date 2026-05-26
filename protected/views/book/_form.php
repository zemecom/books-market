<div class="card">
    <?php $widget = $this->beginWidget('CActiveForm', ['htmlOptions' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'title'); ?>
            <?php echo $widget->textField($form, 'title'); ?>
            <?php echo $widget->error($form, 'title'); ?>
        </div>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'description'); ?>
            <?php echo $widget->textArea($form, 'description'); ?>
            <?php echo $widget->error($form, 'description'); ?>
        </div>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'isbn'); ?>
            <?php echo $widget->textField($form, 'isbn'); ?>
            <?php echo $widget->error($form, 'isbn'); ?>
        </div>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'publishYear'); ?>
            <?php echo $widget->textField($form, 'publishYear'); ?>
            <?php echo $widget->error($form, 'publishYear'); ?>
        </div>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'authorIds'); ?>
            <?php echo $widget->listBox($form, 'authorIds', $authorOptions, ['multiple' => true, 'size' => 8]); ?>
            <?php echo $widget->error($form, 'authorIds'); ?>
        </div>
        <div class="form-row">
            <?php echo $widget->labelEx($form, 'coverFile'); ?>
            <?php echo $widget->fileField($form, 'coverFile'); ?>
            <?php echo $widget->error($form, 'coverFile'); ?>
        </div>
        <button class="button" type="submit">Save</button>
    <?php $this->endWidget(); ?>
</div>
