<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sysarch</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    scroll-behavior: smooth;
    background: #f2f2f2;
  }

  nav {
    background: white;
    padding: 17px 30px;
    position: sticky;
    top: 0;
    display: flex;
    font-size: small;
    justify-content: flex-end;
    gap: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    z-index: 1000;
  }
  nav a {
    color: #333;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: bold;
    letter-spacing: 1px;
    transition: all 0.4s ease;
  }
  nav a:hover {
    background: #ff914d;
    color: white;
    transform: scale(1.05);
  }
  nav a.active {
    background: #ff914d;
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    transform: scale(1.1);
  }

  /* Toast notification */
  #toast {
    position: fixed;
    top: 70px;
    right: 20px;
    background: #4caf50;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: none;
    z-index: 2000;
  }

  /* Sections */
  .order_sec, .loc_sec, .menu_sec {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    scroll-margin-top: 70px;
    overflow: hidden;
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease;
  }
  .order_sec, .loc_sec { height: 100vh; }
  .menu_sec { height: 230vh; }
  section.show { opacity: 1; transform: translateY(0); }
  section iframe { width: 100%; height: 100%; border: none; }

  /* Footer */
  footer {
    background: white;
    padding: 15px 40px;
    font-size: small;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }
  footer .about_us a { text-decoration: none; color: #333; font-weight: bold; }
  footer .contact-footer { display: flex; gap: 15px; }
  footer .contact-footer a {
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #ff914d;
    color: white;
  }
</style>
</head>
<body>

<!-- Navigation -->
<nav>
  <a href="#home" class="active">Home</a>
  <a href="#menu">Products</a>
  <a href="#location">Location</a>

  <!-- Logout Button -->
  <?php if(isset($_SESSION['user_id'])): ?>
    <a href="?logout=1" style="background:#ff4d4d; color:white;">Logout</a>
  <?php endif; ?>
</nav>

<!-- Toast Notification -->
<div id="toast"></div>

<!-- Sections -->
<section id="home" class="order_sec">
  <iframe src="order_page.php"></iframe>
</section>

<section id="menu" class="menu_sec">
  <iframe src="best_sellers.html"></iframe>
</section>

<section id="location" class="loc_sec">
  <iframe src="location_page.html"></iframe>
</section>

<!-- Footer -->
<footer>
  <div class="about_us"><a href="about_page.html">About Us</a></div>
  <div class="contact-footer">
    <a href="#"><i class="fab fa-instagram"></i></a>
    <a href="#"><i class="fab fa-facebook"></i></a>
    <a href="#"><i class="fas fa-envelope"></i></a>
  </div>
</footer>

<script>
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll("nav a");

window.addEventListener("scroll", () => {
  let current = "";
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 80;
    const sectionHeight = section.clientHeight;
    if(scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
      current = section.getAttribute("id");
      section.classList.add("show");
    }
  });
  navLinks.forEach(link => {
    link.classList.remove("active");
    if(link.getAttribute("href") === "#" + current) {
      link.classList.add("active");
    }
  });
});

document.querySelector("#home").classList.add("show");

// Show toast
function showToast(message) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}

// If logged in, show welcome toast
<?php if(isset($_SESSION['user_id'])): ?>
showToast("✅ Welcome back, <?php echo $_SESSION['username']; ?>!");
<?php endif; ?>
</script>

</body>
</html>