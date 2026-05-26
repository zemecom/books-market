<div class="card">
    <h2>Top Authors Report</h2>
    <?php echo CHtml::beginForm(['report/topAuthors'], 'get'); ?>
    <div class="form-row">
        <label for="year">Year</label>
        <input type="text" id="year" name="year" value="<?php echo CHtml::encode((string) $year); ?>">
    </div>
    <button class="button" type="submit">Show report</button>
    <?php echo CHtml::endForm(); ?>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-size: 0.85em; color: #888; font-weight: normal; padding-bottom: 4px;">
                    <?php echo $year ? "Year: {$year} · Top 10 authors by books count" : 'Top 10 authors by books count'; ?>
                </th>
            </tr>
            <tr>
                <th>#</th>
                <th>Author</th>
                <th>Books Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo CHtml::encode($row['author_name']); ?></td>
                    <td><?php echo CHtml::encode((string) $row['books_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>