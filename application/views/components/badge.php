<?php
/**
 * VyrnForge UI Badge Component
 * 
 * Parameters:
 * $text - (string) Badge text
 * $variant - (string) success, warning, danger, info, neutral (default)
 * $size - (string) sm, md (default: sm)
 * $class - (string) additional classes
 */
$variant = isset($variant) ? $variant : 'neutral';
$size = isset($size) ? $size : 'sm';

$base_class = 'vf-badge';
if ($variant !== 'neutral') {
    $base_class .= ' vf-badge--' . $variant;
}
if ($size !== 'md') {
    $base_class .= ' vf-badge--' . $size;
}
if (isset($class)) {
    $base_class .= ' ' . $class;
}
?>
<span class="<?php echo $base_class; ?>">
    <?php echo $text; ?>
</span>
