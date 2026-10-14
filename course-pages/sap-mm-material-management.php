<?php
/**
 * OFSYS Education - SAP MM - Material Management
 * Course content follows the supplied program document.
 */
$slug = 'sap-mm-material-management';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in SAP Material Management',
    'tagline_highlight' => 'SAP Material Management',

    'intro' => 'This course is designed to help learners understand SAP S/4HANA MM and to gain hands-on knowledge of material management, purchasing, inventory, vendor management, procurement processes, material valuation and invoice verification.',

    'about' => 'SAP MM – Material Management course gives participants practical knowledge of SAP S/4HANA MM and its core material management processes. Learners understand procurement, inventory management, master data, material valuation and related business processes.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'Introduction to ERP & SAP S/4HANA MM',
        'Organizational Structure in MM',
        'Material Master Data',
        'Vendor Master & Purchasing Master Data',
        'Purchasing Process',
        'Inventory Management',
        'Special Procurement & Special Stocks',
        'Material Valuation & Account Determination',
        'Invoice Verification'
    ],

    'careers' => [
        'SAP MM Consultant',
        'SAP MM Associate',
        'Procurement Executive',
        'Purchase Executive',
        'Inventory Executive',
        'Materials Management Executive'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in SAP Material Management',
        'text'  => 'Develop practical knowledge of SAP S/4HANA MM and prepare for opportunities in procurement, purchasing, inventory, and material management.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
