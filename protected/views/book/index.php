<div class="card">
    <h2>Books Catalog</h2>
    <div class="list-controls" style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <?php if (!Yii::app()->user->isGuest && Yii::app()->user->checkAccess('user')): ?>
            <span><?php echo CHtml::link('Create book', ['create'], ['class' => 'button', 'style' => 'margin: 0;']); ?></span>
        <?php else: ?>
            <span></span>
        <?php endif; ?>

        <form method="get" action="" class="per-page-form" style="margin: 0;">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'perPage' && $key !== 'page' && is_string($value)): ?>
                    <input type="hidden" name="<?php echo CHtml::encode($key); ?>" value="<?php echo CHtml::encode($value); ?>" />
                <?php endif; ?>
            <?php endforeach; ?>
            <label for="perPageSelect" style="margin-right: 5px; font-weight: bold; font-size: 0.9em; color: #555;">Items per page:</label>
            <select name="perPage" id="perPageSelect" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #ccc; font-size: 0.9em; background-color: #fff; cursor: pointer;">
                <?php foreach ([5, 10, 25, 50] as $val): ?>
                    <option value="<?php echo $val; ?>" <?php echo $perPage === $val ? 'selected' : ''; ?>><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Authors</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $index = ($pages->getCurrentPage() * $pages->getPageSize()) + 1;
        foreach ($rows as $row):
            ?>
            <tr>
                <td style="text-align: center; color: #888;"><?php echo $index++; ?></td>
                <td><?php echo CHtml::link(CHtml::encode($row['title']), ['view', 'id' => $row['id']]); ?></td>
                <td><?php echo CHtml::encode($row['isbn']); ?></td>
                <td><?php echo CHtml::encode($row['authors']); ?></td>
                <td><?php echo CHtml::encode((string) $row['publish_year']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pager-container" style="margin-top: 20px; text-align: center;">
        <?php $this->widget('CLinkPager', [
                'pages' => $pages,
                'header' => '',
                'nextPageLabel' => 'Next &raquo;',
                'prevPageLabel' => '&laquo; Prev',
                'firstPageLabel' => '&laquo;&laquo; First',
                'lastPageLabel' => 'Last &raquo;&raquo;',
                'htmlOptions' => ['class' => 'yiiPager'],
            ]); ?>
    </div>
</div>
