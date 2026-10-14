<?php
/**
 * OFSYS Education - Office Administration
 * Page content lives here; the shared design comes from includes/course-layout.php
 */
$slug = 'office-administration';

$coursePage = [
    // Banner shows only the course title; tagline + intro move into the body.
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills for a Professional Office Career',
    'tagline_highlight' => 'Professional Office Career',

    'intro' => 'The Office Administration course is designed to prepare students with the abilities and knowledge necessary to succeed in a career in today’s office and administrative environment. Office management and communication, HR and documentation, MS Office, workplace and AI enabled work environments program.',

    'about' => 'This course helps learners build essential administrative and workplace skills for today’s professional environment. Along with office management and documentation, learners develop communication, time management, HR, sales, interview and personality development skills.',

    'learn_groups' => [
        [
            'title' => 'Office Administration & Management',
            'icon'  => 'fa-solid fa-briefcase',
            'items' => [
                'Introduction to Office Administration',
                'Office Management',
                'Record Keeping & Filing',
                'Time Management & Organisation Behaviour'
            ]
        ],
        [
            'title' => 'Communication & Professional Skills',
            'icon'  => 'fa-solid fa-comments',
            'items' => [
                'Communication Skills',
                'Spoken English Training',
                'Personality Development',
                'Goal Setting',
                'Interview Training'
            ]
        ],
        [
            'title' => 'HR & Business Skills',
            'icon'  => 'fa-solid fa-users-gear',
            'items' => [
                'Human Resource & Personnel Management',
                'Advanced Sales Strategies',
                'Project & Seminars'
            ]
        ],
        [
            'title' => 'Digital & Workplace Skills',
            'icon'  => 'fa-solid fa-laptop-code',
            'items' => [
                'Microsoft Word',
                'Microsoft Excel',
                'Microsoft PowerPoint / Presentation',
                'AI-Integrated Working Environment'
            ]
        ]
    ],

    // No Tools & Platforms strip on this page
    'tools' => [],

    'careers' => [
        'Office Administrator',
        'Administrative Assistant',
        'Office Executive',
        'Office Coordinator',
        'HR Assistant',
        'Back Office Executive'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Skills for a Professional Office Career',
        'text'  => 'Develop practical office, administrative and professional skills to prepare for today’s workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
