<?php
/**
 * VyrnForge UI Button Component
 * 
 * Parameters:
 * $text - (string) The button text
 * $type - (string) button type (button, submit) default: button
 * $variant - (string) default, primary, danger, ghost, subtle
 * $size - (string) sm, md, lg
 * $icon - (string) lucide icon name
 * $class - (string) additional classes
 * $attributes - (string) additional HTML attributes like onclick, disabled, etc.
 */
$type = isset($type) ? $type : 'button';
$variant = isset($variant) ? $variant : 'default';
$size = isset($size) ? $size : 'md';

$base_class = 'vf-button';
if ($variant !== 'default') {
    $base_class .= ' vf-button--' . $variant;
}
if ($size !== 'md') {
    $base_class .= ' vf-button--' . $size;
}
if (isset($class)) {
    $base_class .= ' ' . $class;
}
?>
<button type="<?php echo $type; ?>" class="<?php echo $base_class; ?>" <?php echo isset($attributes) ? $attributes : ''; ?>>
    <?php if (isset($icon)): ?>
        <i data-lucide="<?php echo $icon; ?>" class="w-4 h-4 mr-2"></i>
    <?php endif; ?>
    <span class="vf-button__label"><?php echo $text; ?></span>
</button>
