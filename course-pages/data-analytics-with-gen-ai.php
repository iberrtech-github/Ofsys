<?php
/**
 * OFSYS Education - Data Analytics with Agentic AI
 * Course content follows the supplied program document.
 */
$slug = 'data-analytics-with-gen-ai';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Data Analytics',
    'tagline_highlight' => 'Data Analytics',

    'intro' => 'This course aims to train learners in practical data analysis and visualization using Python, Excel, Tableau, Power BI, SQL and Pandas, along with the fundamentals of statistics and machine learning.',

    'about' => 'Hands-on data analysis and visualization with industry tools is the focus of the Data Analytics with Agentic AI course. Students will be introduced to data handling, exploratory analysis, statistics, basic principles of machine learning and real data projects.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'Python Programming',
        'Data Analysis & Visualization Using Excel',
        'Data Analysis & Visualization Using Tableau',
        'Data Analysis & Visualization Using Power BI',
        'Relational Database - SQL',
        'Exploratory Data Analysis with Pandas',
        'Foundational Statistics',
        'Foundational Machine Learning',
        'R Programming Basics',
        'Project'
    ],

    'careers' => [
        'Data Analyst',
        'Junior Data Analyst',
        'Business Data Analyst',
        'Reporting Analyst',
        'Data Visualization Analyst',
        'Data Analytics Executive'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Data Analytics',
        'text'  => 'Develop practical data analytics skills and prepare for opportunities in today’s data-driven workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
