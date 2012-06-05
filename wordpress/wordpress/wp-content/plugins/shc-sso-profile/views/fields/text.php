<input<?php array_walk($attributes, function($value, $name) {
    echo ' ' . $name . '="' . htmlspecialchars( (string) $value, ENT_QUOTES) . '"';
}); ?> type="text">
