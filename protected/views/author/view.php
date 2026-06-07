<div class="card">
    <h2><?php echo CHtml::encode($author['name']); ?></h2>
    <p><?php echo nl2br(CHtml::encode($author['bio'])); ?></p>

    <?php if (!empty($author['books'])): ?>
        <h3>Books</h3>
        <ul>
            <?php foreach ($author['books'] as $book): ?>
                <li><?php echo CHtml::link(CHtml::encode($book['title']), ['/book/view', 'id' => $book['id']]); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!Yii::app()->user->isGuest && Yii::app()->user->checkAccess('user')): ?>
        <div class="actions">
            <?php echo CHtml::link('Edit author', ['update', 'id' => $author['id']], ['class' => 'button']); ?>
            <?php echo CHtml::beginForm(['delete', 'id' => $author['id']], 'post', ['class' => 'action-form', 'onsubmit' => "return confirm('Are you sure you want to delete this author?');"]); ?>
            <button class="button button-danger" type="submit">Delete</button>
            <?php echo CHtml::endForm(); ?>
        </div>

        <?php if (Yii::app()->user->checkAccess('admin') && !empty($author['subscriptions'])): ?>
            <h3 class="mt-32">Subscribers</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($author['subscriptions'] as $subscription): ?>
                        <tr>
                            <td><?php echo CHtml::encode($subscription['phone']); ?></td>
                            <td>
                                <?php echo CHtml::link('Edit', ['updateSubscription', 'id' => $subscription['id']], ['class' => 'button button-sm']); ?>
                                <?php echo CHtml::beginForm(['deleteSubscription', 'id' => $subscription['id']], 'post', ['class' => 'action-form', 'onsubmit' => "return confirm('Are you sure you want to delete this subscription?');"]); ?>
                                <button class="button button-danger button-sm" type="submit">Delete</button>
                                <?php echo CHtml::endForm(); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Subscribe to Author</h3>
    <?php $form = $this->beginWidget('CActiveForm', [
        'action' => ['subscribe', 'id' => $author['id']],
        'enableClientValidation' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
        ],
    ]); ?>
        <div class="form-row">
            <?php echo $form->labelEx($subscribeForm, 'phone'); ?>
            <?php echo $form->textField($subscribeForm, 'phone', ['placeholder' => '+7 (999) 000-00-00', 'type' => 'tel']); ?>
            <?php echo $form->error($subscribeForm, 'phone'); ?>
        </div>
        <button class="button" type="submit">Subscribe</button>
    <?php $this->endWidget(); ?>
</div>
