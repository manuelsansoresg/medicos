<?php

if (!function_exists('format_price')) {
    function format_price($price)
    {
        if (!$price || !is_numeric($price)) {
            return 0;
        }

        return number_format($price, 2, '.', ',');
    }
}