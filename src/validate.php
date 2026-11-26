<?php
declare(strict_types=1);


function validate_required(string $v): bool
{
    return trim($v) !== '';
}


function validate_email(string $v): bool
{
    return filter_var(trim($v), FILTER_VALIDATE_EMAIL) !== false;
}


function validate_length(string $v, int $min, int $max): bool
{
    $t = trim($v);
    $len = function_exists('mb_strlen') ? mb_strlen($t, 'UTF-8') : strlen($t);
    return $len >= $min && $len <= $max;
}
