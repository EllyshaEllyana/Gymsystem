    </main>
    <style>
        .footer {
            background-color: #111827;
            color: #cbd5e1;
            padding: 60px 0 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: space-between;
        }
        .footer-col {
            flex: 1;
            min-width: 220px;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .footer-logo i {
            color: #3b82f6;
        }
        .footer-col p {
            line-height: 1.7;
            font-size: 0.95rem;
            margin: 0;
        }
        .footer-col h4 {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 18px 0;
            position: relative;
        }
        .footer-col h4::after {
            content: '';
            display: block;
            width: 38px;
            height: 3px;
            background-color: #3b82f6;
            margin-top: 10px;
        }
        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-col ul li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }
        .footer-col ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .footer-col ul li a:hover {
            color: #ffffff;
            transform: translateX(3px);
        }
        .footer-col ul li i {
            color: #3b82f6;
            width: 16px;
            text-align: center;
        }
        .social-links {
            display: flex;
            gap: 12px;
            margin-top: 5px;
        }
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #1f2937;
            color: #ffffff;
            border-radius: 50%;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .social-links a:hover {
            background-color: #3b82f6;
            transform: translateY(-2px);
        }
        .footer-bottom {
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid #1f2937;
            text-align: center;
            font-size: 0.9rem;
            color: #94a3b8;
        }
        .footer-bottom p {
            margin: 0;
        }
        @media (max-width: 860px) {
            .footer-grid {
                flex-direction: column;
            }
            .footer-col {
                min-width: 100%;
            }
        }
    </style>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fas fa-dumbbell"></i>
                        <span>FitZone</span>
                    </div>
                    <p>Your premier fitness destination. Building stronger bodies and healthier lives since 2020.</p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="homepage.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Fitness Street, KL</li>
                        <li><i class="fas fa-phone"></i> +60 12-345 6789</li>
                        <li><i class="fas fa-envelope"></i> info@fitzone.com</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> FitZone Gym. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                navToggle.classList.toggle('active');
            });
        }
    </script>
</body>
</html>
