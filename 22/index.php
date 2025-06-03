<?php
require_once 'config.php';

// Check if language is set in URL, default to English
$lang = isset($_GET['lang']) && $_GET['lang'] === 'ku' ? 'ku' : 'en';

$pdo = getConnection();
$about = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
$contact = $pdo->query("SELECT * FROM contact_info LIMIT 1")->fetch();
$projects = $pdo->query("SELECT * FROM projects ORDER BY category, display_order LIMIT 6")->fetchAll();
$achievements = $pdo->query("SELECT * FROM achievements ORDER BY display_order LIMIT 4")->fetchAll();
$experience = $pdo->query("SELECT * FROM experience ORDER BY display_order, year DESC")->fetchAll();
$certificates = $pdo->query("SELECT * FROM certificates ORDER BY display_order LIMIT 6")->fetchAll();
$reports = $pdo->query("SELECT * FROM reports ORDER BY display_order LIMIT 4")->fetchAll();
$cv_resumes = $pdo->query("SELECT * FROM cv_resumes WHERE is_active = 1 ORDER BY display_order LIMIT 1")->fetch();
$projects_completed = $pdo->query("SELECT * FROM projects WHERE category = 'completed' ORDER BY display_order")->fetchAll();
$projects_ongoing = $pdo->query("SELECT * FROM projects WHERE category = 'ongoing' ORDER BY display_order")->fetchAll();
$projects_concept = $pdo->query("SELECT * FROM projects WHERE category = 'concept' ORDER BY display_order")->fetchAll();
// Helper function to get localized text
function getLocalizedText($data, $field, $lang, $default = '') {
    $key = $field . '_' . $lang;
    return isset($data[$key]) && !empty($data[$key]) ? $data[$key] : 
           (isset($data[$field]) ? $data[$field] : $default);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>   
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Header Styles */
    .header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    .nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 5%;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }

    .logo {
        font-size: 1.8rem;
        font-weight: bold;
        color: #2c3e50;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        padding: 5px;
        z-index: 1001;
    }

    .mobile-menu-toggle span {
        width: 25px;
        height: 3px;
        background: #333;
        margin: 3px 0;
        transition: 0.3s;
        border-radius: 2px;
    }

    .mobile-menu-toggle.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .mobile-menu-toggle.active span:nth-child(2) {
        opacity: 0;
    }

    .mobile-menu-toggle.active span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }

    .nav-links {
        display: flex;
        list-style: none;
        gap: 2rem;
        align-items: center;
    }

    .nav-links a {
        text-decoration: none;
        color: #555;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-links a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
    }

    .nav-links a:hover::after {
        width: 100%;
    }

    .nav-links a:hover {
        color: #667eea;
    }

    .lang-switcher {
        display: flex;
        gap: 1rem;
    }

    .lang-switcher a {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-decoration: none;
        color: #666;
        background: #f8f9fa;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .lang-switcher a.active,
    .lang-switcher a:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    /* Hero Section */
    .hero {
        padding: 120px 5% 80px;
        text-align: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero div {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Stats Section */
    .stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        min-width: 120px;
    }

    .stat-number {
        display: block;
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Section Styles */
    .section {
        padding: 80px 5%;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        color: #2c3e50;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    /* Card Styles */
    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Grid Layout */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    /* Profile Image Enhanced Styles */
.profile-image {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-image img {
    width: 280px;                    /* گەورەتر کراوە لە 200px */
    height: 280px;                   /* گەورەتر کراوە لە 200px */
    border-radius: 25px;             /* چوارگۆشەی خوار کراوە لە جیاتی بازنەیی */
    object-fit: cover;
    border: 4px solid #667eea;       /* سنوورێکی جوان */
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3),    /* سێبەری سەرەکی */
                0 5px 15px rgba(0, 0, 0, 0.1);           /* سێبەری زیادە */
    transition: all 0.3s ease;       /* ئەنیمەیشن بۆ هۆڤەر */
    position: relative;
}

/* هۆڤەر ئیفێکت بۆ وێنەکە */
.profile-image img:hover {
    transform: translateY(-8px);     /* بەرزبوونەوە کاتی هۆڤەر */
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4),
                0 10px 20px rgba(0, 0, 0, 0.15);
    border-color: #764ba2;           /* گۆڕینی ڕەنگی سنوور */
}

/* گرادیێنت بەکگراوند بۆ وێنەکە */
.profile-image::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 30px;
    z-index: -1;
    opacity: 0.1;
}

/* Mobile Responsive Design - گەورەتر کراوە بۆ مۆبایل */
@media (max-width: 768px) {
    .profile-image img {
        width: 220px;                /* گەورەتر کراوە لە 150px */
        height: 220px;               /* گەورەتر کراوە لە 150px */
        border-radius: 20px;
        border: 3px solid #667eea;
    }
    
    .profile-image::before {
        width: 240px;
        height: 240px;
        border-radius: 25px;
    }
}

@media (max-width: 480px) {
    .profile-image img {
        width: 200px;                /* گەورەتر کراوە لە 120px */
        height: 200px;               /* گەورەتر کراوە لە 120px */
        border-radius: 18px;
        border: 3px solid #667eea;
    }
    
    .profile-image::before {
        width: 220px;
        height: 220px;
        border-radius: 23px;
    }
}

/* دەکۆری زیادە بۆ جوانکردنی وێنەکە */
.profile-image {
    position: relative;
    display: inline-block;
}

/* ئیفێکتی درەوشاوە دەوروبەری وێنەکە */
.profile-image::after {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
    border-radius: 35px;
    z-index: -2;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-image:hover::after {
    opacity: 0.3;
    animation: rotate 3s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* باشترکردنی بەشی About Me */
.card {
    background: rgba(255, 255, 255, 0.95);    /* کەمێک شەفافتر */
    border-radius: 25px;                      /* گەورەتر کراوە */
    padding: 2.5rem;                          /* پەدینگی زیادە */
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1),
                0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.4s ease;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.card:hover {
    transform: translateY(-10px);             /* بەرزبوونەوەی زیادە */
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15),
                0 10px 25px rgba(0, 0, 0, 0.1);
}

    /* Skills Tags */
    .skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .skill-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Project Cards */
    .project-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 1rem;
    }

    .project-meta h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }

    /* Certificate Cards */
    .certificate-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 1rem;
    }

    .certificate-info h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    /* Experience & Achievement Cards */
    .experience-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
        padding-left: 1.5rem;
    }

    .experience-card h3 {
        color: #2d3748;
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
    }

    .experience-card h4 {
        color: #667eea;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .experience-card .year {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .experience-card .description {
        color: #4a5568;
        line-height: 1.6;
    }

    .experience-card i {
        margin-right: 0.5rem;
        color: #667eea;
    }

    @media (max-width: 768px) {
        .experience-card {
            border-left: 3px solid #667eea;
            padding-left: 1rem;
        }
        
        .experience-card h3 {
            font-size: 1.2rem;
        }
        
        .experience-card h4 {
            font-size: 1rem;
        }
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        padding: 0.8rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0.5rem;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg,rgb(186, 181, 187) 0%,rgb(34, 24, 25) 100%);
        box-shadow: 0 5px 15px rgba(102, 96, 97, 0.3);
    }

    .btn-secondary:hover {
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
    }

    /* Contact Section */
    .contact {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .contact .section-title {
        color: white;
    }

    .contact .section-title::after {
        background: white;
    }

    .contact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .contact-item {
        text-align: center;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .contact-item h3 {
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .contact-item a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.3s ease;
    }

    .contact-item a:hover {
        opacity: 0.8;
    }

    /* CV Section */
    .cv-section {
        background: #f8f9fa;
    }

    /* Mobile Responsive Design */
    @media (max-width: 768px) {
        .mobile-menu-toggle {
            display: flex;
        }

        .nav {
            padding: 1rem 3%;
        }

        .logo {
            font-size: 1.5rem;
        }

        .nav-links {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            z-index: 999;
        }

        .nav-links.active {
            display: flex;
        }

        .nav-links a {
            font-size: 1.5rem;
            color: #333;
            padding: 1rem;
        }

        .lang-switcher {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }

        /* Hero Mobile */
        .hero {
            padding: 100px 3% 60px;
        }

        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }

        /* Button Mobile */
        .btn {
            display: block;
            width: 90%;
            max-width: 280px;
            margin: 0.5rem auto;
            text-align: center;
            padding: 1rem;
        }

        /* Grid Mobile */
        .grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        /* Section Mobile */
        .section {
            padding: 50px 3%;
        }

        .section-title {
            font-size: 1.8rem;
        }

        /* Profile */
        .profile-image img {
            width: 150px;
            height: 150px;
        }

        /* Skills */
        .skills {
            justify-content: center;
            gap: 0.3rem;
        }

        .skill-tag {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
        }

        /* Project and Certificate Images */
        .project-card img,
        .certificate-card img {
            height: 200px;
            border-radius: 10px;
        }

        /* Contact */
        .contact-info {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .contact-item {
            padding: 1.5rem;
            border-radius: 10px;
        }

        .contact-item h3 {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .nav {
            padding: 0.8rem 2%;
        }

        .logo {
            font-size: 1.3rem;
        }

        .hero {
            padding: 90px 2% 50px;
        }

        .hero h1 {
            font-size: 1.7rem;
        }

        .section {
            padding: 40px 2%;
        }

        .section-title {
            font-size: 1.6rem;
        }

        .card {
            padding: 1.2rem;
        }

        .profile-image img {
            width: 120px;
            height: 120px;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
    .project-tabs {
    display: flex;
    justify-content: center;
    margin-bottom: 3rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.tab-button {
    padding: 1rem 2rem;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid #667eea;
    color: #667eea;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 1rem;
    min-width: 150px;
    text-align: center;
}

.tab-button.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.tab-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.project-category {
    display: none;
    animation: fadeInUp 0.6s ease-out;
}

.project-category.active {
    display: block;
}

.category-title {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 1.8rem;
    color: #2c3e50;
    font-weight: 600;
}

.empty-category {
    text-align: center;
    padding: 3rem;
    color: #666;
    font-style: italic;
}

/* Mobile responsive for tabs */
@media (max-width: 768px) {
    .project-tabs {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .tab-button {
        width: 90%;
        max-width: 280px;
        padding: 0.8rem 1.5rem;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .tab-button {
        font-size: 0.9rem;
        padding: 0.7rem 1.2rem;
    }
}
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <div class="logo">Portfolio</div>
            
            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#experience">Experience</a></li> <!-- Add this line -->
                <li><a href="#contact">Contact</a></li>
            </ul>
            
            <div class="lang-switcher">
                <a href="?lang=en" class="active">EN</a>
                <a href="?lang=ku">کوردی</a>
            </div>
        </nav>
    </header>


    <section id="home" class="hero">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars(getLocalizedText($about, 'name', $lang, 'Your Name')); ?></h1>
            <p><?php echo htmlspecialchars(getLocalizedText($about, 'title', $lang, 'Programmer and AI Expert')); ?></p>
            <div>
                <a href="#projects" class="btn"><?php echo $lang === 'ku' ? 'کارەکانم ببینە' : 'View My Work'; ?></a>
                <?php if($cv_resumes): ?>
                    <a href="<?php echo htmlspecialchars($cv_resumes['file_path']); ?>" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-download"></i> <?php echo $lang === 'ku' ? 'بینینی CV' : 'View  CV'; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
    </section>

    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'دەربارەی من' : 'About Me'; ?></h2>
            <div class="card">
                <?php if (!empty($about['profile_image'])): ?>
                    <div class="profile-image">
                        <img src="uploads/profile/<?php echo htmlspecialchars($about['profile_image']); ?>" 
                             alt="Profile">
                    </div>
                <?php endif; ?>
                <p><?php echo htmlspecialchars(getLocalizedText($about, 'description', $lang)); ?></p>
                <?php 
                $university = getLocalizedText($about, 'university', $lang);
                if ($university): ?>
                    <p><strong><?php echo $lang === 'ku' ? 'زانکۆ:' : 'University:'; ?></strong> <?php echo htmlspecialchars($university); ?></p>
                <?php endif; ?>
                <?php 
                $skills = getLocalizedText($about, 'skills', $lang);
                if ($skills): ?>
                    <div class="skills">
                        <?php foreach (explode(',', $skills) as $skill): ?>
                            <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<section id="projects" class="section">
    <div class="container">
        <h2 class="section-title"><?php echo $lang === 'ku' ? 'پرۆژەکانم' : 'My Projects'; ?></h2>
        
        <!-- Project Category Tabs -->
        <div class="project-tabs">
            <div class="tab-button active" onclick="showCategory('completed')">
                <?php echo $lang === 'ku' ? 'پڕۆژە تەواوبووەکان' : 'Completed Projects'; ?>
            </div>
            <div class="tab-button" onclick="showCategory('ongoing')">
                <?php echo $lang === 'ku' ? 'پڕۆژە بەردەوامەکان' : 'Ongoing Projects'; ?>
            </div>
            <div class="tab-button" onclick="showCategory('concept')">
                <?php echo $lang === 'ku' ? 'پڕۆژە بیرۆکەکان' : 'Concept Projects'; ?>
            </div>
        </div>

        <!-- Completed Projects -->
        <div id="completed-projects" class="project-category active">
            <?php if (!empty($projects_completed)): ?>
                <div class="grid">
                    <?php foreach ($projects_completed as $project): ?>
                        <div class="card project-card">
                            <?php if ($project['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?>">
                            <?php endif; ?>
                            <div class="project-meta">
                                <h3><?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?></h3>
                                <p><?php echo htmlspecialchars(getLocalizedText($project, 'description', $lang)); ?></p>
                                <?php 
                                $technologies = getLocalizedText($project, 'technologies', $lang);
                                if ($technologies): ?>
                                    <div class="skills">
                                        <?php foreach (explode(',', $technologies) as $tech): ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="margin-top:1rem;">
                                    <?php if ($project['demo_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['demo_url']); ?>" class="btn" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> <?php echo $lang === 'ku' ? 'نموونە' : 'Demo'; ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($project['github_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_url']); ?>" class="btn" target="_blank">
                                            <i class="fab fa-github"></i> GitHub
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-category">
                    <p><?php echo $lang === 'ku' ? 'هێشتا هیچ پڕۆژەیەکی تەواوبوو نەماوە' : 'No completed projects yet'; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ongoing Projects -->
        <div id="ongoing-projects" class="project-category">
            <?php if (!empty($projects_ongoing)): ?>
                <div class="grid">
                    <?php foreach ($projects_ongoing as $project): ?>
                        <div class="card project-card">
                            <?php if ($project['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?>">
                            <?php endif; ?>
                            <div class="project-meta">
                                <h3><?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?></h3>
                                <p><?php echo htmlspecialchars(getLocalizedText($project, 'description', $lang)); ?></p>
                                <?php 
                                $technologies = getLocalizedText($project, 'technologies', $lang);
                                if ($technologies): ?>
                                    <div class="skills">
                                        <?php foreach (explode(',', $technologies) as $tech): ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="margin-top:1rem;">
                                    <?php if ($project['demo_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['demo_url']); ?>" class="btn" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> <?php echo $lang === 'ku' ? 'نموونە' : 'Demo'; ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($project['github_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_url']); ?>" class="btn" target="_blank">
                                            <i class="fab fa-github"></i> GitHub
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-category">
                    <p><?php echo $lang === 'ku' ? 'هێشتا هیچ پڕۆژەیەکی بەردەوام نەماوە' : 'No ongoing projects yet'; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Concept Projects -->
        <div id="concept-projects" class="project-category">
            <?php if (!empty($projects_concept)): ?>
                <div class="grid">
                    <?php foreach ($projects_concept as $project): ?>
                        <div class="card project-card">
                            <?php if ($project['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" alt="<?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?>">
                            <?php endif; ?>
                            <div class="project-meta">
                                <h3><?php echo htmlspecialchars(getLocalizedText($project, 'title', $lang)); ?></h3>
                                <p><?php echo htmlspecialchars(getLocalizedText($project, 'description', $lang)); ?></p>
                                <?php 
                                $technologies = getLocalizedText($project, 'technologies', $lang);
                                if ($technologies): ?>
                                    <div class="skills">
                                        <?php foreach (explode(',', $technologies) as $tech): ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="margin-top:1rem;">
                                    <?php if ($project['demo_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['demo_url']); ?>" class="btn" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> <?php echo $lang === 'ku' ? 'نموونە' : 'Demo'; ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($project['github_url']): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_url']); ?>" class="btn" target="_blank">
                                            <i class="fab fa-github"></i> GitHub
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-category">
                    <p><?php echo $lang === 'ku' ? 'هێشتا هیچ پڕۆژەیەکی بیرۆکە نەماوە' : 'No concept projects yet'; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

    <?php if ($experience): ?>
    <section id="experience" class="section">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'ئەزموون' : 'Experience'; ?></h2>
            <div class="grid">
                <?php foreach ($experience as $exp): ?>
                    <div class="card experience-card">
                        <h3><?php echo htmlspecialchars(getLocalizedText($exp, 'position', $lang)); ?></h3>
                        <h4>
                            <i class="fas fa-building"></i> 
                            <?php echo htmlspecialchars(getLocalizedText($exp, 'company', $lang)); ?>
                        </h4>
                        <?php if ($exp['year']): ?>
                            <p class="year">
                                <i class="fas fa-calendar"></i> 
                                <?php echo htmlspecialchars($exp['year']); ?>
                            </p>
                        <?php endif; ?>
                        <?php 
                        $description = getLocalizedText($exp, 'description', $lang);
                        if ($description): ?>
                            <p class="description"><?php echo htmlspecialchars($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($achievements): ?>
    <section id="achievements" class="section">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'دەستکەوتەکان' : 'Achievements'; ?></h2>
            <div class="grid">
                <?php foreach ($achievements as $achievement): ?>
                    <div class="card achievement-card">
                        <h3><i class="fas fa-trophy"></i> <?php echo htmlspecialchars(getLocalizedText($achievement, 'title', $lang)); ?></h3>
                        <?php if ($achievement['year']): ?>
                            <p><strong><?php echo htmlspecialchars($achievement['year']); ?></strong></p>
                        <?php endif; ?>
                        <?php 
                        $description = getLocalizedText($achievement, 'description', $lang);
                        if ($description): ?>
                            <p><?php echo htmlspecialchars($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($reports): ?>
    <section id="reports" class="section">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'ڕاپۆرتەکان' : 'Reports'; ?></h2>
            <div class="grid">
                <?php foreach ($reports as $report): ?>
                    <div class="card report-card">
                        <h3><i class="fas fa-file-alt"></i> <?php echo htmlspecialchars(getLocalizedText($report, 'title', $lang)); ?></h3>
                        <?php 
                        $description = getLocalizedText($report, 'description', $lang);
                        if ($description): ?>
                            <p><?php echo htmlspecialchars($description); ?></p>
                        <?php endif; ?>
                        <?php if ($report['file_url']): ?>
                            <div style="margin-top:1rem;">
                                <a href="<?php echo htmlspecialchars($report['file_url']); ?>" class="btn" target="_blank">
                                    <i class="fas fa-download"></i> <?php echo $lang === 'ku' ? 'داونلۆد' : 'Download'; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if($cv_resumes): ?>
    <section class="section cv-section">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'CV ' : 'CV & Resume'; ?></h2>
            <div class="card" style="text-align:center;">
                <h3><?php echo htmlspecialchars(getLocalizedText($cv_resumes, 'title', $lang)); ?></h3>
                <p><?php echo $lang === 'ku' ? 'سەیرکردنی' : 'View CV'; ?></p>
                <div style="margin-top:2rem;">
                    <a href="<?php echo htmlspecialchars($cv_resumes['file_path']); ?>" class="btn btn-secondary cv-download" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                        <?php echo $lang === 'ku' ? 'بینینی CV' : 'View CV'; ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section id="contact" class="section contact">
        <div class="container">
            <h2 class="section-title"><?php echo $lang === 'ku' ? 'پەیوەندی' : 'Get In Touch'; ?></h2>
            <div class="contact-info">
                <?php if ($contact['email']): ?>
                    <div class="contact-item">
                        <h3><i class="fas fa-envelope"></i> <?php echo $lang === 'ku' ? 'ئیمەیڵ' : 'Email'; ?></h3>
                        <p><a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a></p>
                    </div>
                <?php endif; ?>
                <?php if ($contact['phone']): ?>
                    <div class="contact-item">
                        <h3><i class="fas fa-phone"></i> <?php echo $lang === 'ku' ? 'تەلەفۆن' : 'Phone'; ?></h3>
                        <p><a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>"><?php echo htmlspecialchars($contact['phone']); ?></a></p>
                    </div>
                <?php endif; ?>
                <?php if ($contact['linkedin']): ?>
                    <div class="contact-item">
                        <h3><i class="fab fa-linkedin"></i> LinkedIn</h3>
                        <p><a href="<?php echo htmlspecialchars($contact['linkedin']); ?>" target="_blank"><?php echo $lang === 'ku' ? 'پەیوەندی لەگەڵ من' : 'Connect with me'; ?></a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

   
 <script>
        // Mobile Menu Functions
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            navLinks.classList.toggle('active');
            menuToggle.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (navLinks.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function closeMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            navLinks.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close menu when clicking on menu links
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    setTimeout(() => {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                }
            });
        });
    </script>
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards
        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Stats counter animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start);
                }
            }, 16);
        }

        // Animate stats when they come into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const numbers = entry.target.querySelectorAll('.stat-number');
                    numbers.forEach(num => {
                        const target = parseInt(num.textContent);
                        num.textContent = '0';
                        animateCounter(num, target);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
    <script>
        // Add this JavaScript to your existing script section

// Mobile Menu Functions
function toggleMobileMenu() {
    const navLinks = document.getElementById('navLinks');
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    
    navLinks.classList.toggle('active');
    menuToggle.classList.toggle('active');
}

function closeMobileMenu() {
    const navLinks = document.getElementById('navLinks');
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    
    navLinks.classList.remove('active');
    menuToggle.classList.remove('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const nav = document.querySelector('.nav');
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.getElementById('navLinks');
    
    if (!nav.contains(event.target) && navLinks.classList.contains('active')) {
        closeMobileMenu();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        const navLinks = document.getElementById('navLinks');
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        
        navLinks.classList.remove('active');
        menuToggle.classList.remove('active');
    }
});

// Enhanced smooth scrolling with mobile menu close
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            // Close mobile menu first
            closeMobileMenu();
            
            // Then scroll
            setTimeout(() => {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }
    });
});
    </script>

<script>
function editAchievement(id) {
    // Hide add form
    document.querySelector('.add-form').style.display = 'none';
    
    // Show edit form
    const editForm = document.getElementById('editAchievementForm');
    editForm.style.display = 'block';
    
    // Fetch achievement data
    fetch(`get_item.php?table=achievements&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_achievement_id').value = data.id;
            document.getElementById('edit_achievement_title_en').value = data.title_en;
            document.getElementById('edit_achievement_title_ku').value = data.title_ku;
            document.getElementById('edit_achievement_description_en').value = data.description_en;
            document.getElementById('edit_achievement_description_ku').value = data.description_ku;
            document.getElementById('edit_achievement_year').value = data.year;
            document.getElementById('edit_achievement_display_order').value = data.display_order;
            
            if (data.image_url) {
                document.getElementById('current_achievement_image').innerHTML = 
                    `<img src="${data.image_url}" alt="Current image" style="max-width: 200px;">`;
            }
        });
}
</script>
<script>
function showCategory(category) {
    // Hide all categories
    const categories = document.querySelectorAll('.project-category');
    categories.forEach(cat => {
        cat.classList.remove('active');
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab-button');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected category
    const selectedCategory = document.getElementById(category + '-projects');
    if (selectedCategory) {
        selectedCategory.classList.add('active');
    }
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}
</script>
</body>
</html>