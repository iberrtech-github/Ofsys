<?php
/**
 * OFSYS Education - Python Full Stack Development
 * Course content follows the supplied program document.
 */
$slug = 'python-full-stack-development';

$coursePage = [
    'hero_minimal' => true,

    'tagline' => 'Build Practical Skills in Full Stack Development',
    'tagline_highlight' => 'Full Stack Development',

    'intro' => 'In this course you will learn full stack development with hands-on experience in Python, SQL, HTML, CSS, Bootstrap, JavaScript, jQuery, AJAX, Django, Django REST Framework, React and full stack integration.',

    'about' => 'The Python Full Stack Development course offers hands-on knowledge of frontend and backend development. Learners will get hands-on experience with Python programming, databases, web technology, Django, API development, React and full stack project development.',

    'tools' => [],

    'curriculum_three_columns' => true,

    'curriculum' => [
        'Core Programming Foundations',
        'Python Basics',
        'SQL Basics',
        'Frontend Foundations (HTML, CSS, Bootstrap)',
        'JavaScript',
        'jQuery & AJAX',
        'Django Basics',
        'Django CRUD',
        'Building APIs - Django REST Framework',
        'Frontend Integration',
        'React Basics',
        'Full-Stack Integration',
        'Projects'
    ],

    'careers' => [
        'Python Developer',
        'Full Stack Developer',
        'Junior Python Developer',
        'Web Developer',
        'Backend Developer',
        'Junior Full Stack Developer'
    ],

    'audience' => [
        ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
        ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
        ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
        ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
    ],

    'closing' => [
        'title' => 'Build Your Career in Full Stack Development',
        'text'  => 'Develop practical development skills and prepare for opportunities in modern web development.'
    ]
];

include __DIR__ . '/../includes/course-layout.php';
