<?php
/**
 * OFSYS Education - Professional Accounting & Financial Analytics Program
 * Course content follows the supplied program brochure.
 */
$slug = 'diploma-in-global-accounting-with-sap';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Accounting & Financial Analytics',
    'tagline_highlight' => 'Accounting & Financial Analytics',

    'intro' => 'The program is designed to impart practical skills in accounting and finance using Tally Prime, Advanced Excel, SAP S/4HANA FICO, ERP and cloud accounting tools, global taxation, internship experience and real-world projects.',

    'about' => 'The course provides practical knowledge in accounting, financial analytics and modern technologies of accounting. The use of ERP systems introduces learners to accounting practices oriented toward accounting software, taxation and the workplace.',

    'tools' => [],

    'curriculum_two_columns' => true,

    'curriculum' => [
        'Fundamentals of Accounting',
        'Advance Level Tally Prime with Internship',
        'Advanced Excel for Accounting',
        'SAP S/4HANA FICO',
        'ERP & Cloud Accounting Tools',
        'Global Taxation (GST & GCC VAT)',
        'Project'
    ],

    'careers' => [
        'Accountant',
        'Accounts Executive',
        'Finance Executive',
        'Tally Accountant',
        'Accounts Assistant',
        'SAP FICO-related Roles'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Accounting & Financial Analytics',
        'text'  => 'Develop practical accounting, financial and technology skills for today’s professional workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
