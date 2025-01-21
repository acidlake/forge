<?php
$variantClass = $variant === "primary" ? "btn-primary" : "btn-secondary";
$style = isset($color) ? "color: {$color};" : "";
?>
<button class="<?= $variantClass ?>" style="<?= $style ?>">
    <?= $slot ?>
</button>
