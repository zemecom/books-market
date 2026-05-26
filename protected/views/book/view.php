<div class="card">
    <h2><?php echo CHtml::encode($book['title']); ?></h2>
    <p><strong>ISBN:</strong> <?php echo CHtml::encode($book['isbn']); ?></p>
    <p><strong>Publish Year:</strong> <?php echo CHtml::encode((string) $book['publish_year']); ?></p>
    <p><strong>Authors:</strong>
        <?php echo CHtml::encode(implode(', ', array_map(static fn(array $author): string => $author['name'], $book['authors']))); ?>
    </p>
    <?php if ($book['cover_url']): ?>
        <p><img src="<?php echo CHtml::encode($book['cover_url']); ?>" alt="Cover" class="cover-image"></p>
    <?php endif; ?>
    <p><?php echo nl2br(CHtml::encode($book['description'])); ?></p>

    <?php if (!Yii::app()->user->isGuest && Yii::app()->user->checkAccess('user')): ?>
        <div class="actions">
            <?php echo CHtml::link('Edit', ['update', 'id' => $book['id']], ['class' => 'button']); ?>
            <?php echo CHtml::beginForm(['delete', 'id' => $book['id']], 'post', ['class' => 'action-form', 'onsubmit' => "return confirm('Are you sure you want to delete this book?');"]); ?>
            <button class="button button-danger" type="submit">Delete</button>
            <?php echo CHtml::endForm(); ?>
        </div>
    <?php endif; ?>
</div>
