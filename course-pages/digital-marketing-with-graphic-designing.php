<?php
/**
 * OFSYS Education - Digital Marketing with Graphic Designing
 * Course content follows the supplied program document.
 */
$slug = 'digital-marketing-with-graphic-designing';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Digital Marketing & Graphic Designing',
    'tagline_highlight' => 'Digital Marketing & Graphic Designing',

    'intro' => 'Students will learn skills in digital marketing and graphic designing such as SEO, social media marketing, performance marketing, content marketing, e-commerce and basic design tools.',

    'about' => 'The Digital Marketing with Graphic Designing course will provide practical knowledge of digital marketing strategies and graphic designing. Learners get hands-on experience in website basics, SEO, social media, online advertising, content creation, e-commerce and graphic designing.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'Introduction to Marketing',
        'Foundation of Digital Marketing',
        'Content Marketing',
        'Canva',
        'Introduction to Website',
        'SEO',
        'Social Media Marketing',
        'Performance Marketing (Meta Ads & Google Ads)',
        'Email Marketing',
        'E-Commerce',
        'Graphic Designing (Photoshop, Illustrator)'
    ],

    'careers' => [
        'Digital Marketing Executive',
        'SEO Executive',
        'Social Media Executive',
        'Performance Marketing Executive',
        'Content Marketing Executive',
        'Graphic Designer'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Digital Marketing',
        'text'  => 'Develop practical digital marketing and graphic designing skills and prepare for opportunities in today’s digital workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
