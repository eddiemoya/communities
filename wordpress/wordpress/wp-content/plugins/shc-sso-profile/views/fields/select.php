<select<?php array_walk($attributes, function($value, $name) {
    echo ' ' . $name . '="' . htmlspecialchars( (string) $value, ENT_QUOTES) . '"';
}); ?>>
<?php foreach ($options as $val => $label): ?>
<option value="<?php echo $val; ?>"<?php echo ($value == $val) ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
<?php endforeach; ?>
