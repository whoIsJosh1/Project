<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Café Menu | Coffee & Pastries</title>
<style>
  body {
    margin: 0;
    font-family: "Poppins", Arial, sans-serif;
    background-color: #fffaf5;
    overflow-x: hidden;
  }

  /* ====== Buttons ====== */
  .pos-btn, .logout-btn {
    position: fixed;
    top: 20px;
    background-color: #ff914d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    z-index: 1000;
  }
  .pos-btn { right: 130px; }
  .logout-btn { right: 20px; background-color: #ff4d4d; }
  .pos-btn:hover, .logout-btn:hover {
    transform: scale(1.05);
    filter: brightness(0.9);
  }

  /* ====== Hero Section ====== */
  .hero {
    position: relative;
    width: 100%;
    height: 350px;
    background: url('cafe-banner.jpg') center/cover no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .hero-overlay {
    background: rgba(0, 0, 0, 0.5);
    position: absolute;
    inset: 0;
  }

  .hero-content {
    position: relative;
    color: white;
    text-align: center;
    z-index: 2;
  }

  .hero-content h1 {
    font-size: 48px;
    letter-spacing: 2px;
    margin-bottom: 10px;
  }

  .hero-content p {
    font-size: 18px;
    color: #f8e8d0;
  }

  /* ====== Section Titles ====== */
  h2 {
    color: #ff914d;
    font-size: 32px;
    margin-top: 60px;
    margin-bottom: 20px;
    text-align: center;
    text-transform: uppercase;
  }

  /* ====== Carousel ====== */
  .carousel {
    position: relative;
    width: 90%;
    max-width: 1000px;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    background: white;
    margin: 0 auto 60px auto;
  }

  .carousel-track {
    display: flex;
    transition: transform 0.6s ease;
  }

  .carousel-item {
    min-width: 100%;
    box-sizing: border-box;
    text-align: center;
  }

  .carousel-item img {
    width: 100%;
    height: 400px;
    object-fit: cover;
  }

  .carousel-info {
    padding: 15px;
  }

  .item-title {
    font-size: 22px;
    font-weight: bold;
    color: #ff914d;
    margin-bottom: 8px;
  }

  .item-desc {
    color: #333;
    font-size: 16px;
  }

  .carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,145,77,0.8);
    border: none;
    color: white;
    font-size: 24px;
    padding: 10px 16px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    transition: 0.3s;
  }
  .carousel-btn:hover { background: #ff7c2e; }
  .prev { left: 15px; }
  .next { right: 15px; }

  /* ====== About Section ====== */
  .about {
    width: 100%;
    background: linear-gradient(135deg, #fff3e0, #ffe0cc);
    padding: 60px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 40px;
  }

  .about img {
    width: 350px;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  }

  .about-text {
    max-width: 500px;
  }

  .about-text h3 {
    color: #ff914d;
    font-size: 28px;
    margin-bottom: 15px;
  }

  .about-text p {
    font-size: 17px;
    color: #333;
    line-height: 1.6;
  }

  /* ====== Quote Divider ====== */
  .quote {
    font-style: italic;
    text-align: center;
    color: #555;
    margin: 60px auto;
    font-size: 20px;
    max-width: 700px;
  }

  /* ====== Footer ====== */
  footer {
    background-color: #ff914d;
    color: white;
    text-align: center;
    padding: 20px 10px;
    font-size: 16px;
    letter-spacing: 1px;
  }

  @media (max-width: 768px) {
    .hero-content h1 { font-size: 34px; }
    .carousel-item img { height: 280px; }
    .about { flex-direction: column; }
  }
</style>
</head>
<body>

<!-- Buttons -->
<button class="pos-btn" onclick="window.location.href='pastryPage.html'">Go to POS</button>
<button class="logout-btn" onclick="logout()">Logout</button>

<!-- Hero Banner -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>Welcome to 16 Ounces Café ☕</h1>
    <p>Where every sip and bite feels like home</p>
  </div>
</section>

<!-- Coffee Carousel -->
<h2>Our Signature Coffees</h2>
<div class="carousel" id="coffeeCarousel">
  <button class="carousel-btn prev" onclick="moveSlide('coffeeCarousel', -1)">❮</button>
  <div class="carousel-track">
    <div class="carousel-item"><img src="matcha latte.jpg"><div class="carousel-info"><div class="item-title">Espresso</div><div class="item-desc">Rich and strong espresso, perfect to start your day.</div></div></div>
    <div class="carousel-item"><img src="Salted Caramel Latte.jpg"><div class="carousel-info"><div class="item-title">Cappuccino</div><div class="item-desc">Creamy cappuccino with a perfect foam layer.</div></div></div>
    <div class="carousel-item"><img src="Spanish Latte.jpg"><div class="carousel-info"><div class="item-title">Latte</div><div class="item-desc">Smooth and mild latte with rich aroma.</div></div></div>
    <div class="carousel-item"><img src="Caramel Macchiato.jpg"><div class="carousel-info"><div class="item-title">Mocha</div><div class="item-desc">Chocolate-infused coffee, sweet and comforting.</div></div></div>
    <div class="carousel-item"><img src="Americano.jpg"><div class="carousel-info"><div class="item-title">Americano</div><div class="item-desc">Classic americano with a smooth taste.</div></div></div>
  </div>
  <button class="carousel-btn next" onclick="moveSlide('coffeeCarousel', 1)">❯</button>
</div>

<!-- About Section -->
<section class="about">
  <img src="interior.jpg" alt="Cafe Interior">
  <div class="about-text">
    <h3>About 16 Ounces</h3>
    <p>At our Café, we serve handcrafted beverages and freshly baked pastries using only the finest ingredients. Whether you're craving a strong espresso or a buttery croissant, our café provides the perfect atmosphere to unwind, study, or catch up with friends.</p>
  </div>
</section>

<!-- Pastry Carousel -->
<h2>Best-Selling Pastries</h2>
<div class="carousel" id="pastryCarousel">
  <button class="carousel-btn prev" onclick="moveSlide('pastryCarousel', -1)">❮</button>
  <div class="carousel-track">
    <div class="carousel-item"><img src="redvelvet.jpg"><div class="carousel-info"><div class="item-title">Croissant</div><div class="item-desc">Flaky buttery croissants, perfect for breakfast or snack.</div></div></div>
    <div class="carousel-item"><img src="Chocolate Chip Cookies.jpg"><div class="carousel-info"><div class="item-title">Muffin</div><div class="item-desc">Soft and moist muffins in a variety of flavors.</div></div></div>
    <div class="carousel-item"><img src="red chip cookies.jpg"><div class="carousel-info"><div class="item-title">Cake</div><div class="item-desc">Delicious cakes baked fresh daily, perfect with coffee.</div></div></div>
    <div class="carousel-item"><img src="cheesecake.jpg"><div class="carousel-info"><div class="item-title">Danish</div><div class="item-desc">Sweet danish pastries, ideal for breakfast or dessert.</div></div></div>
    <div class="carousel-item"><img src="croissant.jpg"><div class="carousel-info"><div class="item-title">Brownie</div><div class="item-desc">Rich and fudgy chocolate brownies, perfect with coffee.</div></div></div>
  </div>
  <button class="carousel-btn next" onclick="moveSlide('pastryCarousel', 1)">❯</button>
</div>

<!-- Quote Divider -->
<p class="quote">“Life happens, coffee helps.”</p>

<!-- Footer -->
<footer>
  © 2025 16 Ounces Café | Designed group ☕ No Name
</footer>

<script>
let slides = { coffeeCarousel: 0, pastryCarousel: 0 };

function moveSlide(id, direction) {
  const carousel = document.getElementById(id);
  const track = carousel.querySelector('.carousel-track');
  const items = carousel.querySelectorAll('.carousel-item');
  slides[id] = (slides[id] + direction + items.length) % items.length;
  track.style.transform = `translateX(-${slides[id] * 100}%)`;
}

setInterval(() => moveSlide('coffeeCarousel', 1), 5000);
setInterval(() => moveSlide('pastryCarousel', 1), 5000);

function logout() {
  fetch("logout.php", { method: "POST" })
    .then(() => {
      window.location.replace("index.php");
    });
}
</script>
</body>
</html>
