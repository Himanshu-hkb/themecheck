<?php

/* Prevent any PHP inside the file from being executed if the file is accessed directly outside a WordPress context */
defined('ABSPATH') || die("you do not have access to this page!");

echo '<div class="pt-50 pt-30-sm" id="page-header"><div class="jkd-c ta-c jc-c d-col ai-c">';

echo '<h1>';
jkdtheme::the_title();
echo '</h1>';

/* if is archive (tags/cats) - output the term description string */
if (!empty(term_description())) echo '<div class="term-desc  ta-c mt-10">' . term_description() . '</div>';

/* breadcrumbs render */
get_template_part('template-parts/breadcrumbs');

echo '</div></div>';