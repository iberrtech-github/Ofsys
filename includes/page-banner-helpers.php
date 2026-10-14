<?php
/**
 * Helpers for the poster page banner (includes/page-banner.php).
 */

if (!function_exists('pbx_stagger')) {
    /**
     * Stacks a plain headline as "first word" on line one and the rest
     * stepped in underneath — the layout every banner uses:
     *
     *     About
     *            OFSYS Education
     *
     * Pass pre-built HTML in $banner['title'] to lay a headline out by hand.
     */
    function pbx_stagger(string $text): string
    {
        $text = trim($text);
        $pos  = strpos($text, ' ');

        if ($pos === false) {
            return htmlspecialchars($text);
        }

        return htmlspecialchars(substr($text, 0, $pos))
             . '<span class="pbx-ind">' . htmlspecialchars(substr($text, $pos + 1)) . '</span>';
    }
}
