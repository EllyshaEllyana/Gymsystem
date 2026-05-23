<?php
require_once 'auth.php';
require_once 'connectdb.php';
$pageTitle = 'About';
require_once 'header.php';

$packageResult = $conn->query("SELECT package_name, duration, price FROM membership_packages ORDER BY package_id");
$packages = $packageResult ? $packageResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<link rel="stylesheet" href="about.css?">
<section class="about-section fade-in">
    <div class="about-banner">
        <img src="picture/WhatsApp Image 2026-05-22 at 9.27.41 PM.jpeg" alt="Gym Banner" style="width:100%;max-height:360px;object-fit:cover;border-radius:8px;">
    </div>
    <div style="margin-top:1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;">
        <img src="picture/WhatsApp Image 2026-05-22 at 9.27.42 PM.jpeg" alt="Equipment" style="width:100%;height:120px;object-fit:cover;border-radius:6px;">
        <img src="picture/WhatsApp Image 2026-05-22 at 9.27.43 PM.jpeg" alt="Trainers" style="width:100%;height:120px;object-fit:cover;border-radius:6px;">
        <img src="picture/WhatsApp Image 2026-05-22 at 9.27.44 PM.jpeg" alt="Gym Floor" style="width:100%;height:120px;object-fit:cover;border-radius:6px;">
        <img src="picture/WhatsApp Image 2026-05-22 at 9.27.45 PM.jpeg" alt="Members Working Out" style="width:100%;height:120px;object-fit:cover;border-radius:6px;">
    </div>
    <h1>About FitZone Gym</h1>
    <p>Welcome to <strong>FitZone Gym</strong> — your premier fitness destination in Kuala Lumpur. Since our founding in 2020, we have been committed to helping our members achieve their fitness goals through world-class facilities, expert guidance, and an inclusive community.</p>
    <p>Our gym features the latest cardiovascular and strength training equipment, spacious workout areas, and dedicated zones for stretching and functional training. Whether you're a beginner or a seasoned athlete, FitZone has everything you need.</p>
    <p>Our team of certified trainers is passionate about fitness and dedicated to creating personalized workout plans that fit your lifestyle. We believe that everyone deserves access to quality fitness resources, which is why we offer flexible and affordable membership packages.</p>
    <h2 style="margin-top: 2rem; margin-bottom: 1rem;">Our Mission</h2>
    <p>To empower individuals to lead healthier, more active lives by providing a welcoming and well-equipped fitness environment.</p>
    <h2 style="margin-top: 2rem; margin-bottom: 1rem;">Our Values</h2>
    <div class="features-grid" style="margin-top: 1.5rem;">
        <div class="feature-card">
            <div class="icon"><i class="fas fa-handshake"></i></div>
            <h3>Integrity</h3>
            <p>We are transparent and honest in everything we do.</p>
        </div>
        <div class="feature-card">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h3>Excellence</h3>
            <p>We strive for the highest standards in fitness services.</p>
        </div>
        <div class="feature-card">
            <div class="icon"><i class="fas fa-heart"></i></div>
            <h3>Community</h3>
            <p>We foster a supportive and inclusive environment for all.</p>
        </div>
    </div>

    <section class="packages-section" style="margin-top: 2rem; background: rgba(255,255,255,0.05); padding: 24px; border-radius: 16px;">
        <h2 style="margin-bottom: 1rem;">Our Membership Packages</h2>
        <p style="margin-bottom: 1rem;">Choose a plan that fits your fitness goals. Each package includes access to trainers, classes, and our full gym facilities.</p>
        <ul style="list-style: disc inside; padding-left: 0; margin: 0;">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <li style="margin-bottom: 0.75rem; font-size: 1rem; line-height: 1.6;">
                        <strong><?php echo htmlspecialchars($pkg['package_name']); ?></strong> &mdash; <?php echo htmlspecialchars($pkg['duration']); ?> month<?php echo $pkg['duration'] > 1 ? 's' : ''; ?> at <strong>RM <?php echo number_format($pkg['price'], 2); ?></strong>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="margin-bottom: 0.75rem;">Our membership packages are currently being updated. Please check back soon.</li>
            <?php endif; ?>
        </ul>
    </section>
</section>

<?php require_once 'footer.php'; ?>
