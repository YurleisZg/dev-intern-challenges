<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <title>PetCare - Pet Services</title>
</head>
<body>
    <header>
    <nav>
        <ul>
            <?php ?>
            <li><a href="#home">Home</a></li>
            <li><a href="#services">Services</a></li>
        </ul>

        <div class="logo">PetCare</div>

        <ul>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="login.html" class="login-btn">Log In</a></li>
        </ul>
    </nav>
</header>


    <main>
        <section class="hero" id="home">
            <h1>We take care of your pets<br>with <span>love and dedication</span></h1>
            <p>Professional grooming, care, and wellness services for dogs and cats.</p>
        </section>

        <section id="services">
            <h2>Services</h2>
            <p>The best care for your furry friends:</p>
            <div class="services-grid">
                <div class="card">
                    <h3>✂️ Haircut</h3>
                    <p>Safe, clean styles adapted to each pet.</p>
                </div>
                <div class="card">
                    <h3>🧼 Bath & Cleaning</h3>
                    <p>Specialized baths, deep cleaning, and safe drying.</p>
                </div>
                <div class="card">
                    <h3>❤️ Spa & Care</h3>
                    <p>Relaxing treatments to improve wellness and health.</p>
                </div>
                <div class="card">
                    <h3>🛍️ Product Sales</h3>
                    <p>Food, toys, and hygiene products.</p>
                </div>
            </div>
        </section>

        <section id="about">
            <h2>About Us</h2>
            <p>We are a team dedicated to the professional care and well-being of pets.</p>
            <aside>
                We also care for nervous pets or those with special needs.
            </aside>
        </section>

        <section id="contact">
            <h2>Contact Us</h2>
            <form>
                <label>Name</label>
                <input type="text" placeholder="Your name" required>

                <label>Email</label>
                <input type="email" placeholder="youremail@domain.com" required>

                <label>Message</label>
                <textarea rows="4" placeholder="Write your message..." required></textarea>

                <button type="submit">Send</button>
            </form>
        </section>
    </main>

    <footer>
        © 2025 PetCare - All rights reserved
    </footer>
</body>
</html>
