<?php
/**
 * OFSYS Education - Data Science with Gen AI
 * Course content follows the supplied program document.
 */
$slug = 'data-science-with-gen-ai';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Data Science & Generative AI',
    'tagline_highlight' => 'Data Science & Generative AI',

    'intro' => 'In this course, learners will acquire practical data science skills in Excel, SQL, Python, statistics, machine learning, NLP, deep learning, Power BI, Tableau, Generative AI, prompt engineering and R programming.',

    'about' => 'The Data Science with GenAI course offers hands-on knowledge in data analysis, visualization, machine learning, deep learning and Generative AI. Learners get to work on data and apply data science concepts using industry-relevant tools and technology.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'Excel for Data Analytics',
        'SQL for Data Analysis',
        'Python for Data Analytics',
        'Python EDA & Data Visualization',
        'Statistics for Data Science',
        'Machine Learning with Python',
        'Natural Language Processing (NLP)',
        'Deep Learning',
        'Data Visualization with Power BI',
        'Tableau – Data Visualization',
        'Generative AI & Prompt Engineering',
        'R Programming'
    ],

    'careers' => [
        'Data Scientist',
        'Junior Data Scientist',
        'Data Analyst',
        'Machine Learning Associate',
        'Data Science Executive',
        'Business Data Analyst'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Data Science',
        'text'  => 'Develop practical data science and Generative AI skills and prepare for opportunities in today’s data-driven workplace.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
