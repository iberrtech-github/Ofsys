<?php
/**
 * OFSYS Education - Centralized Data Store & Configuration
 * Provides structured data for Courses, Branches, Testimonials, Placements, FAQs, etc.
 */

define('SITE_NAME', 'OFSYS Education');
define('SITE_TAGLINE', 'Career-Focused Education for a Future-Ready Career');
define('PHONE_PRIMARY', '+91 99463 30438');
define('PHONE_DISPLAY', '+91 99463 30438');
define('PHONE_CLEAN', '919946330438');
define('HF_PHONE', '+919946330438'); // Header & footer contact number
define('HF_PHONE_DISPLAY', '+91 99463 30438');
define('EMAIL_PRIMARY', 'info@ofsysedu.com');
define('EMAIL_ADMISSION', 'admissions@ofsysedu.com');
define('WHATSAPP_LINK', 'https://wa.me/919946330438?text=Hello%20OFSYS%20Education,%20I%20would%20like%20to%20know%20more%20about%20your%20courses');

if (!defined('BASE_PATH')) {
    // Work the site root out from this file's own location, so pages living in a
    // sub folder (e.g. /course-pages/*.php) still build correct asset URLs.
    $basePath = null;
    $docRoot  = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $siteRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

    if ($docRoot !== '' && strpos($siteRoot, $docRoot) === 0) {
        $basePath = rtrim(substr($siteRoot, strlen($docRoot)), '/');
    }

    if ($basePath === null) {
        // Fallback: guess from the folder of the running script.
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/index.php';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    }

    if ($basePath === '.' || $basePath === '/') {
        $basePath = '';
    }
    define('BASE_PATH', $basePath);
}

// Placements & Gallery are managed from the admin panel, not from this file.
require_once __DIR__ . '/db.php';

// 8 Core Career-Oriented Courses (Directly mapped from Client Syllabus)
$courses = [
    'office-administration' => [
        'id' => 1,
        'slug' => 'office-administration',
        'title' => 'Office Administration',
        'short_title' => 'Office Administration',
        'category' => 'Management & Admin',
        'badge' => 'High Demand',
        'is_ai' => false,
        'duration' => '3 - 6 Months',
        'mode' => 'Offline & Hybrid Batches',
        'icon' => 'fa-solid fa-briefcase',
        'color' => '#E11D2A',
        'summary' => 'Build high-demand office and administrative skills for modern corporate workspaces, integrating AI tools and executive management.',
        'description' => 'This course will help you build the skills you need for today’s offices and administration jobs. You will learn about office management, communication, HR, documentation, MS office, time management, sales techniques, spoken English, personality development and AI enabled work environments.',
        'tools' => ['MS Office 365', 'Advanced Excel', 'Google Workspace', 'AI Productivity Tools', 'HR MIS Softwares', 'Tally Prime Basics'],
        'curriculum' => [
            'Module 1: Modern Office Management & Executive Etiquette',
            'Module 2: Business Communication & Spoken English Proficiency',
            'Module 3: Advanced MS Office (Word, Excel, PowerPoint, Outlook)',
            'Module 4: HR Coordination, Documentation & Filing Systems',
            'Module 5: Sales Techniques, Client Handling & Front Office Management',
            'Module 6: AI-Enabled Office Productivity & Digital Workspace Automation'
        ],
        'careers' => ['Office Administrator', 'Administrative Assistant', 'Office Executive', 'Office Coordinator', 'HR Assistant', 'Back Office Executive']
    ],

    'diploma-in-global-accounting-with-sap' => [
        'id' => 2,
        'slug' => 'diploma-in-global-accounting-with-sap',
        'title' => 'Professional Accounting & Financial Analytics Program',
        'short_title' => 'Professional Accounting & Financial Analytics Program',
        'category' => 'Finance & Accounting',
        'badge' => 'Industry Certified',
        'is_ai' => false,
        'duration' => '6 Months',
        'mode' => 'Intensive Practical Training',
        'icon' => 'fa-solid fa-calculator',
        'color' => '#D97706',
        'summary' => 'Comprehensive accounting foundation covering Tally Prime, SAP S/4HANA FICO, GST, GCC VAT, and real-time internship exposure.',
        'description' => 'Gain a solid basis for accounting using Tally Prime, Advanced Excel, SAP S/4HANA FICO, ERP, cloud accounting, GST, GCC VAT, Internship experience and real-world projects.',
        'tools' => ['SAP S/4HANA FICO', 'Tally Prime', 'Advanced Excel', 'QuickBooks', 'Zoho Books', 'GST & VAT Portals'],
        'curriculum' => [
            'Module 1: Financial Accounting Principles & Double Entry Mastery',
            'Module 2: Tally Prime with Indian GST & GCC VAT Regulations',
            'Module 3: Advanced Excel for Financial Analysis & Reporting',
            'Module 4: SAP S/4HANA FICO (General Ledger, AP, AR, Asset Accounting)',
            'Module 5: Cloud Accounting Platforms (Zoho & QuickBooks)',
            'Module 6: Live Corporate Project & Practical Internship Experience'
        ],
        'careers' => ['SAP FICO Associate', 'Senior Accountant', 'GCC VAT Consultant', 'Financial Analyst', 'Tax Executive']
    ],

    'sap-mm-material-management' => [
        'id' => 3,
        'slug' => 'sap-mm-material-management',
        'title' => 'SAP MM – Material Management',
        'short_title' => 'SAP MM (Material Mgmt)',
        'category' => 'Enterprise ERP',
        'badge' => 'Consultant Track',
        'is_ai' => false,
        'duration' => '4 - 6 Months',
        'mode' => 'Hands-on ERP Server Access',
        'icon' => 'fa-solid fa-boxes-stacked',
        'color' => '#E11D2A',
        'summary' => 'Master SAP S/4HANA MM, procurement lifecycle, inventory management, vendor master, and real-time enterprise supply chains.',
        'description' => 'Learn SAP S/4HANA MM, organizational structure, material master, vendor master, purchasing, inventory management, special procurement, material valuation, and invoice verification.',
        'tools' => ['SAP S/4HANA MM', 'SAP GUI', 'Material Master DB', 'Procurement Cockpit', 'Inventory Valuation Models'],
        'curriculum' => [
            'Module 1: SAP S/4HANA Enterprise & Organizational Structure',
            'Module 2: Master Data Management (Material Master & Business Partner/Vendor Master)',
            'Module 3: Procurement Cycle (PR, RFQ, Purchase Order, Contracts & Outline Agreements)',
            'Module 4: Inventory Management & Goods Movement (MIGO, Movement Types)',
            'Module 5: Logistics Invoice Verification (MIRO) & Material Valuation',
            'Module 6: Special Procurement (Subcontracting, Consignment) & Integration with FI/SD'
        ],
        'careers' => ['SAP MM End-User', 'SAP MM Associate Consultant', 'Procurement Specialist', 'Inventory Manager', 'Supply Chain Analyst']
    ],

    'data-analytics-with-gen-ai' => [
        'id' => 4,
        'slug' => 'data-analytics-with-gen-ai',
        'title' => 'Data Analytics with Agentic AI',
        'short_title' => 'Data Analytics with Agentic AI',
        'category' => 'AI & Data Intelligence',
        'badge' => 'AI Powered',
        'is_ai' => true,
        'duration' => '6 Months',
        'mode' => 'Hands-on Projects & AI Labs',
        'icon' => 'fa-solid fa-chart-line',
        'color' => '#2563EB',
        'summary' => 'Drive strategic decisions through Python, SQL, Tableau, Power BI, Statistics, and next-gen Generative AI data workflows.',
        'description' => 'Data Analytics using Python, Excel, SQL, Tableau, Power BI, Pandas Statistics & Machine Learning Basics with Hands-on Projects.',
        'tools' => ['Python (Pandas, NumPy)', 'PostgreSQL / SQL', 'Tableau Desktop', 'Microsoft Power BI', 'ChatGPT for Code & Analysis', 'Jupyter Lab'],
        'curriculum' => [
            'Module 1: Advanced Excel & Business Analytics Foundations',
            'Module 2: Relational Databases & Complex SQL Querying',
            'Module 3: Python for Data Analysis (Pandas, NumPy, Seaborn & Matplotlib)',
            'Module 4: Interactive Business Intelligence Dashboards with Power BI & Tableau',
            'Module 5: Statistical Analysis & Machine Learning Basics for Business Insights',
            'Module 6: Generative AI for Automated Reporting, Data Synthesis & Prompt Engineering'
        ],
        'careers' => ['Data Analyst', 'Business Intelligence Analyst', 'Data Visualization Specialist', 'Market Research Analyst', 'AI Analytics Consultant']
    ],

    'data-science-with-gen-ai' => [
        'id' => 5,
        'slug' => 'data-science-with-gen-ai',
        'title' => 'Data Science with Gen AI',
        'short_title' => 'Data Science with Gen AI',
        'category' => 'AI & Data Intelligence',
        'badge' => 'Flagship AI Course',
        'is_ai' => true,
        'duration' => '6 Months',
        'mode' => 'Advanced AI Lab & Capstones',
        'icon' => 'fa-solid fa-brain',
        'color' => '#8B5CF6',
        'summary' => 'Deep dive into Machine Learning, Deep Learning, NLP, R programming, LLMs, Prompt Engineering, and Generative AI applications.',
        'description' => 'Learn Python, SQL, statistics, exploratory data analysis, machine learning, NLP, deep learning, Power BI, Tableau, Generative AI, prompt engineering, and R programming.',
        'tools' => ['Python', 'R Language', 'TensorFlow / PyTorch', 'Scikit-Learn', 'OpenAI APIs / LangChain', 'Power BI & Tableau'],
        'curriculum' => [
            'Module 1: Advanced Python & R Programming for Data Science',
            'Module 2: Mathematical Foundations, Probability & Exploratory Data Analysis (EDA)',
            'Module 3: Supervised & Unsupervised Machine Learning Algorithms',
            'Module 4: Deep Learning Foundations & Natural Language Processing (NLP)',
            'Module 5: Generative AI, Large Language Models (LLMs) & Prompt Engineering',
            'Module 6: Model Deployment with Streamlit, Cloud APIs & Capstone Projects'
        ],
        'careers' => ['Data Scientist', 'Machine Learning Engineer', 'AI Specialist', 'Predictive Modeler', 'NLP Associate']
    ],

    'python-full-stack-development' => [
        'id' => 6,
        'slug' => 'python-full-stack-development',
        'title' => 'Python Full Stack Development',
        'short_title' => 'Python Full Stack Dev',
        'category' => 'Software & Engineering',
        'badge' => '100% Placement Track',
        'is_ai' => false,
        'duration' => '6 Months',
        'mode' => 'Live Software Development',
        'icon' => 'fa-solid fa-code',
        'color' => '#059669',
        'summary' => 'Master Python programming, Django, Django REST Framework, modern React frontend, SQL databases, and real cloud deployment.',
        'description' => 'Learn the basics of programming, Python, SQL, HTML, CSS, Bootstrap, JavaScript, JQuery, AJAX, Django, Django REST Framework, React, full-stack integration and hands-on development projects.',
        'tools' => ['Python 3', 'Django & DRF', 'React.js', 'PostgreSQL', 'HTML5 / CSS3 / Tailwind', 'Git & GitHub', 'Postman & Docker'],
        'curriculum' => [
            'Module 1: Web Fundamentals (HTML5, Modern CSS, Responsive Design, Bootstrap)',
            'Module 2: JavaScript ES6+, DOM Manipulation, AJAX & Asynchronous Programming',
            'Module 3: Python Programming Core & Object-Oriented Programming (OOP)',
            'Module 4: Backend Web Development with Django & Django REST Framework',
            'Module 5: Modern Frontend Development with React.js & State Management',
            'Module 6: Full Stack Integration, Authentication, Database Optimization & Cloud Deployment'
        ],
        'careers' => ['Full Stack Python Developer', 'Backend Developer', 'Django Engineer', 'Frontend React Developer', 'Web Application Engineer']
    ],

    'digital-marketing-with-graphic-designing' => [
        'id' => 7,
        'slug' => 'digital-marketing-with-graphic-designing',
        'title' => 'Digital Marketing with Graphic Designing',
        'short_title' => 'Digital Marketing & Design',
        'category' => 'Creative & Digital Media',
        'badge' => 'High ROI Career',
        'is_ai' => true,
        'duration' => '4 - 6 Months',
        'mode' => 'Live Campaigns & Creative Studio',
        'icon' => 'fa-solid fa-bullhorn',
        'color' => '#EA580C',
        'summary' => 'Blend creative graphic design in Photoshop, Illustrator & Canva with high-converting SEO, Meta Ads, Google Ads, and AI marketing tools.',
        'description' => 'Learn Digital Marketing, Content Marketing, Website Basics, SEO, Social Media Marketing, Meta Ads, Google Ads, Email Marketing, E-commerce, Canva, Photoshop and Illustrator.',
        'tools' => ['Adobe Photoshop', 'Adobe Illustrator', 'Canva Pro', 'Google Ads', 'Meta Ads Manager', 'SEMrush', 'GA4 & Search Console'],
        'curriculum' => [
            'Module 1: Graphic Design Fundamentals with Adobe Photoshop & Illustrator',
            'Module 2: High-Speed Creative Content Creation with Canva & AI Design Tools',
            'Module 3: Website Architecture, CMS Basics & Search Engine Optimization (SEO)',
            'Module 4: Social Media Marketing & Paid Ads Mastery (Meta, Instagram, LinkedIn)',
            'Module 5: Google Ads (Search, Display, Video/YouTube) & Conversion Tracking',
            'Module 6: Email Marketing, E-Commerce Strategies & Influencer Campaign Execution'
        ],
        'careers' => ['Digital Marketing Specialist', 'Graphic Designer', 'Performance Marketer', 'Social Media Manager', 'SEO Strategist']
    ],

    'diploma-in-business-administration-with-hr-management' => [
        'id' => 8,
        'slug' => 'diploma-in-business-administration-with-hr-management',
        'title' => 'Diploma in Business Administration with HR Management',
        'short_title' => 'Business Admin with HR',
        'category' => 'Management & Admin',
        'badge' => 'Leadership Program',
        'is_ai' => false,
        'duration' => '6 Months',
        'mode' => 'Case Studies & Corporate Labs',
        'icon' => 'fa-solid fa-users-gear',
        'color' => '#7C3AED',
        'summary' => 'Comprehensive business leadership program covering HR management, corporate operations, hospital administration, and marketing.',
        'description' => 'Build knowledge across HR management, digital marketing, operations management, hospital administration, office management, sales, and marketing.',
        'tools' => ['HRMS Portals', 'CRM Software', 'Payroll Systems', 'Operations Workflow Tools', 'Hospital Admin Softwares', 'MS Office Suite'],
        'curriculum' => [
            'Module 1: Principles of Modern Business Administration & Leadership',
            'Module 2: Strategic Human Resource Management, Talent Acquisition & Payroll',
            'Module 3: Operations Management, Supply Chain & Workflow Optimization',
            'Module 4: Healthcare & Hospital Administration Systems',
            'Module 5: Sales Dynamics, CRM Tools & Corporate Marketing Fundamentals',
            'Module 6: Corporate Governance, Legal Compliances & Business Case Studies'
        ],
        'careers' => ['HR Executive / Specialist', 'Business Operations Executive', 'Hospital Administrator', 'Corporate Relationship Manager', 'Branch Admin Manager']
    ]
];

// Course Cover Images (used on cards, banners & mega menus)
$course_images = [
    'office-administration' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=900&auto=format&fit=crop&q=72&fm=webp',
    'diploma-in-global-accounting-with-sap' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=900&auto=format&fit=crop&q=72&fm=webp',
    'sap-mm-material-management' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=900&auto=format&fit=crop&q=72&fm=webp',
    'data-analytics-with-gen-ai' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&auto=format&fit=crop&q=72&fm=webp',
    'data-science-with-gen-ai' => BASE_PATH . '/assets/images/data%20scince.jpg',
    'python-full-stack-development' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=900&auto=format&fit=crop&q=72&fm=webp',
    'digital-marketing-with-graphic-designing' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=900&auto=format&fit=crop&q=72&fm=webp',
    'diploma-in-business-administration-with-hr-management' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=900&auto=format&fit=crop&q=72&fm=webp'
];
foreach ($courses as $cKey => $cVal) {
    $courses[$cKey]['image'] = $course_images[$cKey];
}

// Homepage Hero Banner Slides
$hero_slides = [
    [
        'tag' => 'OFSYS Education',
        'headline' => 'Build Skills.<br>Shape Your <em>Future.</em>',
        'desc' => 'Practical, job-oriented training tailored to today’s career opportunities. Learn from experienced trainers, work on modern tools and get prepared for the workplace.',
        'cta' => 'Explore Courses',
        'link' => '/courses',
        'image' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=1600&auto=format&fit=crop&q=72&fm=webp'
    ],
    [
        'tag' => 'SAP • Gen AI • Full Stack',
        'headline' => 'Learn on the Tools<br>Employers <em>Use.</em>',
        'desc' => 'Hands-on training on SAP S/4HANA, Power BI, Python, Django, React, Photoshop and Gen AI — with live projects that build a real portfolio.',
        'cta' => 'View Programs',
        'link' => '/courses',
        'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1600&auto=format&fit=crop&q=72&fm=webp'
    ],
    [
        'tag' => '100% Placement Support',
        'headline' => 'From Classroom<br>to <em>Career.</em>',
        'desc' => 'Resume building, mock interviews, corporate orientation and direct employer connects across India and the GCC — we stay with you until you are hired.',
        'cta' => 'See Placements',
        'link' => '/placements',
        'image' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1600&auto=format&fit=crop&q=72&fm=webp'
    ]
];

// Learning Journey Steps (Homepage stacking cards)
$journey_steps = [
    [
        'title' => 'Choose Your Career Path',
        'desc' => 'Talk to our career counsellors, understand your strengths and pick the program that matches your interests — SAP, Data, AI, Development, Marketing or Administration.',
        'points' => ['Free career counselling', 'Weekday & weekend batches', 'Flat 20% early bird scholarship'],
        'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=900&auto=format&fit=crop&q=72&fm=webp'
    ],
    [
        'title' => 'Learn by Doing, Every Day',
        'desc' => 'Train in our labs with experienced trainers on real industry software. Every module ends with practical assignments and live projects, not just theory.',
        'points' => ['Industry-standard tools', 'Real-world projects', 'Personal mentoring'],
        'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=900&auto=format&fit=crop&q=72&fm=webp'
    ],
    [
        'title' => 'Get Certified & Get Hired',
        'desc' => 'Earn a recognized certification, polish your resume, crack mock interviews and step into your first role with our dedicated placement support.',
        'points' => ['Recognized certification', 'Mock HR & technical rounds', 'Direct employer connects'],
        'image' => 'https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?w=900&auto=format&fit=crop&q=72&fm=webp'
    ]
];

// Inner Page Banner Images
$page_banners = [
    'about' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1600&auto=format&fit=crop&q=72&fm=webp',
    'courses' => BASE_PATH . '/assets/images/courses%20img.jpg',
    'gallery' => 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=1600&auto=format&fit=crop&q=72&fm=webp',
    'placements' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1600&auto=format&fit=crop&q=72&fm=webp',
    'blog' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=1600&auto=format&fit=crop&q=72&fm=webp',
    'contact' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1600&auto=format&fit=crop&q=72&fm=webp'
];

// Branches Network (from ofsysedu.com/contact)
$branches = [
    'manjeri' => [
        'name' => 'OFSYS - Manjeri',
        'badge' => 'Corporate Office',
        'address' => ['Kadeeja Tower, Pandikkad Road,', 'Near New Bus Stand,', 'Kuthukal, Manjeri,', 'Kerala 676121'],
        'map_query' => 'Kadeeja Tower Pandikkad Road Kuthukal Manjeri Kerala 676121',
        'map_url' => 'https://www.google.com/maps/place/Ofsys+Manjeri/@11.1211428,76.1230342,17z/data=!3m1!4b1!4m6!3m5!1s0x3ba6370cc8232e4d:0x733f8790b417769f!8m2!3d11.1211428!4d76.1230342!16s%2Fg%2F11z68gt_rj',
        'map_embed' => '11.1211428,76.1230342'
    ],
    'nilambur' => [
        'name' => 'OFSYS - Nilambur',
        'badge' => '',
        'address' => ['VAS Building, 30/139,', 'Mini Bypass Nilambur Rd,', 'Near New Bus Stand,', 'Nilambur, Kerala 679329'],
        'map_query' => 'VAS Building Mini Bypass Nilambur Road Nilambur Kerala 679329',
        'map_url' => 'https://www.google.com/maps?vet=10CAAQoqAOahcKEwiYgZvY4qmXAxUAAAAAHQAAAAAQDg..i&rlz=1C1GCEA_enIN1189IN1189&sca_esv=f15b6346d67b4a35&udm&fvr=1&pvq=Cg0vZy8xMW5iNW5uejg2IhUKD29mc3lzIGVkdWNhdGlvbhACGAM&lqi=Cg9vZnN5cyBlZHVjYXRpb25IhvKp2qi0gIAIWhcQABABGAAiD29mc3lzIGVkdWNhdGlvbpIBF2VkdWNhdGlvbmFsX2luc3RpdHV0aW9u&cs=0&um=1&ie=UTF-8&fb=1&gl=in&sa=X&ftid=0x3ba63b7f6becfe17:0x1e9c6220645e51c6'
    ],
    'perinthalmanna' => [
        'name' => 'OFSYS - Perinthalmanna',
        'badge' => '',
        'address' => [ 'VL Arcade,', 'Bypass Road,', 'Near Gulf Own Mobiles,','Padippura', 'Perinthalmanna, Keralam 679322'],
        'map_query' => 'VL Arcade Bypass Road Perinthalmanna Kerala 679322',
        'map_url' => 'https://www.google.com/maps?vet=10CAAQoqAOahcKEwiou57-uLaXAxUAAAAAHQAAAAAQDg..i&rlz=1C1GCEA_enIN1189IN1189&sca_esv=95a3924c91beb42b&udm&fvr=1&pvq=Cg0vZy8xMXh0eDAxY2prIhUKD29mc3lzIGVkdWNhdGlvbhACGAOJAcD3IzDK6QN2&lqi=Cg9vZnN5cyBlZHVjYXRpb25IkdyCwOe8gIAIWhcQABABGAAiD29mc3lzIGVkdWNhdGlvbpIBF2VkdWNhdGlvbmFsX2luc3RpdHV0aW9u&cs=0&um=1&ie=UTF-8&fb=1&gl=in&sa=X&ftid=0x3ba7cddc6960b0b9:0x7603e9ca3023f7c0'
    ],
    'kochi' => [
        'name' => 'OFSYS - Kochi',
        'badge' => '',
        'address' => ['Door No. 48/148A5,', 'Manorama Memorial Edackoyvelil Building,', 'Aisha Road, Anjumury,', 'Thammanam, Vyttila,', 'Kochi, Keralam 682019'],
        'map_query' => 'Ofsys Kochi Edackoyvelil Building Aisha Road Thammanam Vyttila Kochi 682019',
        'map_url' => 'https://www.google.com/maps/place/Ofsys+Kochi/@9.9808274,76.3130974,17z/data=!3m1!4b1!4m6!3m5!1s0x3b080d99d6c5bb1d:0x77896d49c2e33b2d!8m2!3d9.9808221!4d76.3156723!16s%2Fg%2F11zc_r6kr_'
    ]
];

// Affiliations & Certifications
$affiliations = [
    ['name' => 'Govt Recognized Skill Partner', 'image' => 'assets/images/affili-1.jpg'],
    ['name' => 'Vocational Education Council', 'image' => 'assets/images/affili-2.jpg'],
    ['name' => 'National Skills Board Alignment', 'image' => 'assets/images/affili-3.png'],
    ['name' => 'International Accreditation Council', 'image' => 'assets/images/affili-4.jpg']
];

$certifications = [
    ['name' => 'SAP S/4HANA Ecosystem', 'image' => 'assets/images/cert-sap.jpeg'],
    ['name' => 'IBM SkillsBuild Partner', 'image' => 'assets/images/cert-ibm.jpeg'],
    ['name' => 'Meta Certified Digital Marketing', 'image' => 'assets/images/cert-meta.jpeg'],
    ['name' => 'Microsoft Authorized Educator', 'image' => 'assets/images/cert-micr.jpeg'],
    ['name' => 'Tally Prime Authorized Academy', 'image' => 'assets/images/cert-tally.png'],
    ['name' => 'N-Ergy Skill Ecosystem', 'image' => 'assets/images/cert-nergy.png'],
    ['name' => 'BFSI Sector Skill Council', 'image' => 'assets/images/cert-bfsi.png'],
    ['name' => 'CTDS Skill Certification', 'image' => 'assets/images/cert-ctds.jpeg'],
    ['name' => 'GADS Professional Standard', 'image' => 'assets/images/cert-gads.jpeg']
];

// Achievements / Metrics
$metrics = [
    ['number' => '4+',  'label' => 'Years – Active Training', 'short' => 'Years Active'],
    ['number' => '1K+', 'label' => 'Graduates',               'short' => 'Graduates'],
    ['number' => '20+', 'label' => 'Expert Trainers',         'short' => 'Expert Trainers']
];

// FAQs (From Client PDF)
$faqs = [
    [
        'q' => 'What courses does OFSYS Education offer?',
        'a' => 'OFSYS offers professional courses in Office Administration, Professional Accounting & Financial Analytics Program, SAP MM, Data Analytics, Data Science, Python Full Stack Development, Digital Marketing with Graphic Designing, and Business Administration with HR Management.'
    ],
    [
        'q' => 'Is OFSYS Education a job-oriented training institute?',
        'a' => 'Yes. OFSYS focuses on practical and job-oriented education with industry-focused training, hands-on learning, projects, career support, and interview preparation.'
    ],
    [
        'q' => 'What is the duration of OFSYS courses?',
        'a' => 'OFSYS highlights structured career-focused programs, including 6-month programs. The exact duration may vary by course.'
    ],
    [
        'q' => 'Who can join OFSYS courses?',
        'a' => 'Current eligibility information states that learners who have passed 12th standard in any stream and have basic English skills can apply, subject to the individual course requirements.'
    ],
    [
        'q' => 'Does OFSYS provide career support?',
        'a' => 'Yes. OFSYS highlights resume preparation, mock interviews, interview management, corporate orientation, and career-readiness support.'
    ]
];

// Why Choose Us Pillars (From Client PDF)
$why_choose = [
    ['title' => 'Industry-Driven Training', 'desc' => 'Our courses are designed to teach practical knowledge and skills that are relevant to the needs of today’s workplace.', 'img' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-chart-pie'],
    ['title' => 'Professional Trainers', 'desc' => 'Train with professionals with professional knowledge and experience in the field.', 'img' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-chalkboard-user'],
    ['title' => 'Hands-on Learning', 'desc' => 'Latest Software, Tools, Assignments & Real World Projects.', 'img' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-laptop-code'],
    ['title' => 'Career Support', 'desc' => 'Prepare for the workplace with resume preparation, interview training, mock interviews and corporate orientation.', 'img' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-handshake-angle'],
    ['title' => 'Flexible Batches', 'desc' => 'Select weekday or weekend batches that fit your learning around your other commitments.', 'img' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-clock-rotate-left'],
    ['title' => 'Personal Attention', 'desc' => 'Smaller classes create an environment that provides the opportunity for interaction, guidance and focused learning.', 'img' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=500&auto=format&fit=crop&q=72&fm=webp', 'icon' => 'fa-solid fa-user-check']
];

// What You Get (From Client PDF)
$what_you_get = [
    ['title' => '6-Month Career-Focused Programs', 'desc' => 'Take a structured learning path to build skills in your chosen field.', 'icon' => 'fa-solid fa-calendar-check'],
    ['title' => 'Practical Experience', 'desc' => 'Gain practical experience with the latest tools and technologies used in the industry.', 'icon' => 'fa-solid fa-sliders'],
    ['title' => 'Course Certification', 'desc' => 'Upon successful completion of your program you will be awarded course certification to enhance your professional profile.', 'icon' => 'fa-solid fa-award'],
    ['title' => 'Practical Assignments & Projects', 'desc' => 'Gain hands-on experience with real-world projects to apply your learning.', 'icon' => 'fa-solid fa-folder-open'],
    ['title' => 'Employability Skills', 'desc' => 'Develop communication, confidence, professional etiquette, leadership and readiness for the workforce.', 'icon' => 'fa-solid fa-comments'],
    ['title' => 'Career Readiness', 'desc' => 'Support with resume writing, interview preparation and corporate on-boarding.', 'icon' => 'fa-solid fa-plane-departure']
];

// Career Support steps (Homepage)
$career_support = [
    ['title' => 'Resume Writing', 'desc' => 'Properly display your education, skills, projects and experience.', 'icon' => 'fa-solid fa-file-lines'],
    ['title' => 'Interview Skills', 'desc' => 'Gain confidence and professional communication in the interview.', 'icon' => 'fa-solid fa-comments'],
    ['title' => 'Workplace Orientations', 'desc' => 'Learn about your duties at the workplace and in professional settings.', 'icon' => 'fa-solid fa-building'],
    ['title' => 'Hands-On Projects', 'desc' => 'Work on real life projects and put what you learn to use.', 'icon' => 'fa-solid fa-diagram-project']
];

// Blog Posts
$blogs = [
    [
        'slug' => 'why-ai-is-transforming-business-and-education-2026',
        'title' => 'Why Gen AI is Transforming Career Opportunities in 2026',
        'category' => 'AI & Technology',
        'date' => 'October 02, 2026',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=72&fm=webp',
        'excerpt' => 'From automating routine analysis to augmenting creative decision-making, Generative AI skills are now mandatory across all job sectors.',
        'content' => '<p>The rapid rise of Generative AI has redefined what it means to be job-ready in 2026. Whether in Data Science, Digital Marketing, or Office Administration, professionals who know how to wield AI tools operate 5x faster than those who do not.</p><p>At OFSYS Education, we have embedded Gen AI concepts right into the core syllabus of our data, programming, and administration programs, giving our students an unfair advantage in job interviews.</p>'
    ]
];
