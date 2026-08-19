<?php
/**
 * VyrnForge UI Input Component
 * 
 * Parameters:
 * $name - (string) Input name
 * $id - (string) Input id (defaults to name)
 * $label - (string) Input label
 * $type - (string) Input type (text, email, password) default: text
 * $placeholder - (string) Input placeholder
 * $value - (string) Input value
 * $required - (boolean) Is required
 * $icon - (string) lucide icon name (absolute left)
 * $class - (string) additional wrapper classes
 * $input_class - (string) additional input classes
 * $attributes - (string) additional HTML attributes on input (e.g., onkeyup)
 */
$type = isset($type) ? $type : 'text';
$id = isset($id) ? $id : $name;
$required_attr = isset($required) && $required ? 'required' : '';
$has_icon = isset($icon);
?>
<div class="vf-field <?php echo isset($class) ? $class : ''; ?>">
    <?php if (isset($label)): ?>
        <label for="<?php echo $id; ?>" class="vf-label vf-label--sm mb-1"><?php echo $label; ?> <?php echo $required_attr ? '*' : ''; ?></label>
    <?php endif; ?>
    
    <div class="relative w-full">
        <?php if ($has_icon): ?>
            <i data-lucide="<?php echo $icon; ?>" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3 z-10"></i>
        <?php endif; ?>
        
        <input 
            type="<?php echo $type; ?>" 
            name="<?php echo $name; ?>" 
            id="<?php echo $id; ?>" 
            <?php echo isset($placeholder) ? 'placeholder="'.$placeholder.'"' : ''; ?>
            <?php echo isset($value) ? 'value="'.$value.'"' : ''; ?>
            <?php echo $required_attr; ?>
            class="vf-input w-full <?php echo $has_icon ? 'pl-10' : 'px-3.5'; ?> py-2 <?php echo isset($input_class) ? $input_class : ''; ?>"
            <?php echo isset($attributes) ? $attributes : ''; ?>
        >
    </div>
</div>
