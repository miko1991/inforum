<?php class_exists('Kernel\Template') or exit; ?>
<h1>Members List</h1>

<div class="list">
    <?php foreach($members as $member): ?>
        <div class="list__item">
            <?php echo $member->displayName ?>
        </div>
    <?php endforeach; ?>
</div>