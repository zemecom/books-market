<?php
/**
 * @var AuthorSubscription $subscription
 * @var UpdateSubscriptionForm $form
 */
$this->pageTitle = 'Edit Subscription - ' . Yii::app()->name;
?>
<div class="card">
    <h2>Edit Subscription</h2>
    <p>Editing phone number for author: <?php echo CHtml::encode($subscription->author->name); ?></p>

    <?php $activeForm = $this->beginWidget('CActiveForm', [
        'id' => 'update-subscription-form',
        'enableClientValidation' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
        ],
    ]); ?>

    <div class="form-row">
        <?php echo $activeForm->labelEx($form, 'phone'); ?>
        <?php echo $activeForm->textField($form, 'phone', ['placeholder' => '+7 (999) 000-00-00', 'type' => 'tel']); ?>
        <?php echo $activeForm->error($form, 'phone'); ?>
    </div>

    <div class="form-row">
        <button class="button" type="submit">Save</button>
        <?php echo CHtml::link('Cancel', ['view', 'id' => $subscription->author_id], ['class' => 'button button-secondary button-cancel']); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
