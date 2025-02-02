<?php 
    session_start(); 
    include_once "header.php"; ?>
<body>
    <!-- Header Start-->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo"><img src="assets/img/logo/logo.png" alt="Ollyo Logo" class="img-fluid"/></a>
            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="#header">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#events">Events</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            <!-- Button Toggle Based on Login Status -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn btn-dark panel-btn">User Panel</button>
            <?php else: ?>
                <button class="btn btn-dark login-btn">User Panel</button>
            <?php endif; ?>
        </div>
    </header>
    <!-- Header End-->
    <?php require 'modals/modal-login-auth.php'; ?>
    <!-- Hero Section Start -->
    <section id="hero" class="d-flex align-items-center justify-content-center">
        <div class="container" >
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <h1>Powerful Digital Solutions With Ollyo<span>.</span></h1>
                    <h2>We are a team of talanted software engineers</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->
    <!-- Main Section Start -->
    <main id="main">
        <!-- About Section Start -->
        <section id="about" class="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 order-1 order-lg-2">
                        <img src="assets/img/about.jpg" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
                        <h3>Our vision is to empower every business with technology, unlocking their fullest potential seamlessly and efficiently.</h3>
                        <p class="font-italic">
                        We dream, design, develop, and dare to challenge the status quo and make a difference. We strive to develop a rich culture by expanding our horizons and bringing you ideas outside of the box.
                        </p>
                        <ul>
                            <li><i class="ri-check-double-line"></i> Committing to the highest standards in our products and processes, with a relentless pursuit of innovation and improvement.</li>
                            <li><i class="ri-check-double-line"></i> Cultivating a culture of empathy, respect, and proactive support, ensuring we meet and anticipate the needs of our customers and colleagues.</li>
                            <li><i class="ri-check-double-line"></i> Encouraging continuous personal and professional development while upholding a strong sense of responsibility for our actions and outcomes.</li>
                        </ul>
                        <p>
                        Building a vibrant community that values open communication, teamwork, and shared joy in our achievements and pursuits.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- About Section End -->
        <!-- Service Section Start -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title">
                    <h2>Services</h2>
                    <p>Check our Services</p>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-stretch">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bxl-dribbble"></i></div>
                            <h4><a href="">JoomShaper</a></h4>
                            <p>Easily build websites, landing pages, pop-ups, alert bars, and beyond.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-stretch">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-tachometer"></i></div>
                            <h4><a href="https://www.themeum.com/">Themeum</a></h4>
                            <p>Empowering WordPress: LMS, No-Code Builder, Crowdfunding & More.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-stretch">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-world"></i></div>
                            <h4><a href="https://droip.com/">Droip</a></h4>
                            <p>Droip is a WordPress drag & drop website builder that makes your website designing and building a lot easier than ever.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-stretch">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-slideshow"></i></div>
                            <h4><a href="https://icofont.com/">IcoFont</a></h4>
                            <p>2400+ free, meticulously crafted icons. You will be provided with a zip file. Extracting the zip file, you'll find a demo.html file, icofont.css file, icofont.min.css file and a fonts directory.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services Section -->
         <!-- Include Modal Pages -->
        <?php require 'modals/modal-event-details.php'; ?>
        <?php require 'modals/modal-add-attendee.php'; ?>
        <!-- Event Section Start -->
        <section id="events" class="event py-5 bg-light">
            <div class="container">
                <div class="section-title">
                    <h2>Events</h2>
                    <p>Check our Events</p>
                </div>
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Upcoming Events</h2>
                    <p class="text-muted">Explore the latest events and conferences</p>
                </div>
                <div class="row">
                    <?php require 'core/fetch-events.php'; ?>
                </div>
            </div>
        </section>
        <!-- Event Section End -->
        <!-- Team Section Start -->
        <section id="team" class="team">
            <div class="container">
                <div class="section-title">
                    <h2>Team</h2>
                    <p>Check our Team</p>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-1.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Kawshar Ahmed</h4>
                                <span>Founder & CEO</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-2.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Jakir Hossen</h4>
                                <span>Senior Vice President (SVP) of Engineering</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-3.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Zareen Tasnim</h4>
                                <span>Head of Content</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-4.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Sohan Zakaria</h4>
                                <span>VP of Product</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-5.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Showkot Shawon</h4>
                                <span>VP of Design</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <div class="member-img">
                                <img src="assets/img/team/team-6.jpg" class="img-fluid" alt="">
                                <div class="social">
                                    <a href=""><i class="icofont-twitter"></i></a>
                                    <a href=""><i class="icofont-facebook"></i></a>
                                    <a href=""><i class="icofont-instagram"></i></a>
                                    <a href=""><i class="icofont-linkedin"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Paul Frankowski</h4>
                                <span>Global Product Specialist</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Team Section End -->
        <!-- Contact Section Start -->
        <section id="contact" class="contact">
            <div class="container" >
                <div class="section-title">
                    <h2>Contact</h2>
                    <p>Contact Us</p>
                </div>
                <div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.70272914228!2d90.47364867602465!3d23.82922678574421!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c94b4492908d%3A0xda9fb76cf55c6c18!2z4KaT4Kay4KeN4Kay4KeH4KaT!5e0!3m2!1sbn!2sbd!4v1737996604607!5m2!1sbn!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="row mt-5">
                    <div class="col-lg-4">
                        <div class="info">
                            <div class="address">
                                <i class="icofont-google-map"></i>
                                <h4>Location:</h4>
                                <p>1 Quantum Drive, Dhaka 1229</p>
                            </div>
                            <div class="email">
                                <i class="icofont-envelope"></i>
                                <h4>Email:</h4>
                                <p>hello@ollyo.com</p>
                            </div>
                            <div class="phone">
                                <i class="icofont-phone"></i>
                                <h4>Call:</h4>
                                <p>+880 17 4293 3775</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mt-5 mt-lg-0">
                        <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                                    <div class="validate"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                                    <div class="validate"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                                <div class="validate"></div>
                            </div>
                            <div class="mb-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Sectino End -->
    </main>
    <!-- Main Section End -->
    <!-- Footer Start -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-info">
                        <h3>Ollyo<span>.</span></h3>
                        <p>
                            1 Quantum Drive,<br>
                            Dhaka 1229<br><br>
                            <strong>Phone:</strong> +880 17 4293 3775<br>
                            <strong>Email:</strong> hello@ollyo.com<br>
                        </p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                            <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Our Services</h4>
                        <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-newsletter">
                        <h4>Our Newsletter</h4>
                        <p>You can subscribe to our Newsletter to stay updated.</p>
                        <form action="" method="post">
                        <input type="email" name="email"><input type="submit" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                &copy; Copyright <?= date("Y"); ?><strong><span> Shofeul Bashar Ashik</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed and developed by <b>Ashik</b>
            </div>
        </div>
    </footer>
    <!-- Footer End -->
    <?php include_once "footer.php"; ?>
</body>
</html>