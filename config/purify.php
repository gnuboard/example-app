<?php
return [
    'settings' => [
        'allowed_html' => [
            'div', 'span', 'p', 'br', 'strong', 'em', 'u', 'strike',
            'ul', 'ol', 'li',
            'h1', 'h2', 'h3', 'h4', 'h5',
            'table', 'thead', 'tbody', 'tr', 'td', 'th',
            'a' => [
                'href' => true,
                'title' => true,
                'target' => true,
            ],
            'img' => [
                'src' => true,
                'alt' => true,
                'width' => true,
                'height' => true,
            ],
        ],
        'remove_contents' => true,
        'remove_unknown_tags' => true,
        'allow_safe_protocols' => true,
    ],
]; 