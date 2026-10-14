<?php
/**
 * OFSYS Education - Diploma in Business Administration with HR Management
 * Course content follows the supplied program document.
 */
$slug = 'diploma-in-business-administration-with-hr-management';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Business Administration & HR',
    'tagline_highlight' => 'Business Administration & HR',

    'intro' => 'This course is designed to develop learners’ practical knowledge of HR management, digital marketing, operations, hospital administration, office management, sales and marketing.',

    'about' => 'The Diploma in Business Administration with HR Management provides students with an understanding of the key areas of business and administration. The course covers HR, operations, marketing, office management, hospital administration and sales to develop practical workplace skills for learners.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'HR Management',
        'Digital Marketing',
        'Operations Management',
        'Hospital Administration',
        'Office Management',
        'Sales and Marketing'
    ],

    'careers' => [
        'HR Executive',
        'HR Assistant',
        'Office Administrator',
        'Operations Executive',
        'Business Administration Executive',
        'Sales & Marketing Executive'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Business Administration & HR',
        'text'  => 'Develop practical business, administration and HR skills and prepare for opportunities in today’s professional workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
