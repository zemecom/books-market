<div class="card">
    <div class="header-with-actions">
        <h2>SMS Notification Logs</h2>
    </div>

    <?php if (empty($logs)): ?>
        <p>No SMS logs found.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Book</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Error Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo CHtml::encode($log['created_at']); ?></td>
                        <td><?php echo CHtml::encode($log['book_title'] ?: 'N/A'); ?></td>
                        <td><?php echo CHtml::encode($log['phone']); ?></td>
                        <td><?php echo CHtml::encode($log['message']); ?></td>
                        <td>
                            <span class="status-<?php echo CHtml::encode($log['status']); ?>">
                                <?php echo CHtml::encode(ucfirst($log['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo nl2br(CHtml::encode($log['error_text'] ?: '-')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
