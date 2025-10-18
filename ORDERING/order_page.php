<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>16 Ounces Café</title>
<style>
  body {
    background-color: #ff914d;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-wrap: wrap;
    padding: 150px 20px 20px;
    box-sizing: border-box;
    font-family: sans-serif;
  }

  .logo {
    display: flex;
    align-items: center;
    gap: 25px;
    background-color: black;
    border-radius: 8px;
    padding: 120px;
    flex: 1 1 350px;
    max-width: 550px;
    justify-content: center;
  }
  .cup {
    width: 50px;
    height: 100px;
    border: 5px solid white;
    border-radius: 10px;
    position: relative;
    flex-shrink: 0;
  }
  .cup::before {
    content: "";
    position: absolute;
    top: -12px;
    left: -15px;
    width: 70px;
    height: 18px;
    border: 5px solid white;
    border-radius: 4px;
  }
  .cup::after {
    content: "";
    position: absolute;
    bottom: -12px;
    left: 5px;
    width: 30px;
    height: 12px;
    border: 5px solid white;
    border-radius: 4px;
  }
  .brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: Impact;
  }
  .brand .name {
    background: white;
    color: black;
    padding: 8px 20px;
    font-weight: bold;
    font-size: 24px;
    letter-spacing: 3px;
  }
  .brand .tagline {
    margin-top: 12px;
    font-size: 16px;
    letter-spacing: 4px;
    color: white;
  }

  .text_order {
    background: #ff914d;
    color: white;
    padding: 20px;
    border-radius: 8px;
    font-size: 22px;
    text-align: center;
    flex: 1 1 350px;
    max-width: 450px;
  }
  .btn-container {
    margin-top: 40px;
    padding-top: 30px;
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  .btn {
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .btn-signin { background-color: white; color: #ff914d; }
  .btn-signup { background-color: black; color: white; }
  .btn-menu { background-color: #fff; color: #ff914d; }
  .btn:hover { transform: scale(1.05); opacity: 0.9; }

  .modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
  }
  .modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    width: 300px;
    text-align: center;
  }
  .modal-content h2 { margin-bottom: 20px; color: #5a3605; }
  .modal-content input {
    width: 90%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  .modal-content button {
    width: 95%;
    padding: 10px;
    background: #8b5e2e;
    border: none;
    color: #fff;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
  }
  .modal-content button:hover { background: #6b451d; }
  .close {
    cursor: pointer;
    float: right;
    font-size: 20px;
    font-weight: bold;
    color: #333;
  }
</style>
</head>
<body>

<!-- Logo -->
<div class="logo">
  <div class="cup"></div>
  <div class="brand">
    <div class="name">16 OUNCES CAFÉ</div>
    <div class="tagline">COFFEE AND PASTRY</div>
  </div>
</div>

<!-- Order section -->
<div class="text_order">
  <h1>ORDER HERE</h1>
  <div class="btn-container">
    <?php if(!isset($_SESSION['user_id'])): ?>
      <button class="btn btn-signin" onclick="openModal('login')">Sign In</button>
      <button class="btn btn-signup" onclick="openModal('signup')">Sign Up</button>
    <?php else: ?>
      <button class="btn btn-menu" onclick="menuP age()">Menu</button>
    <?php endif; ?>
  </div>
</div>

<!-- LOGIN -->
<div class="modal" id="loginModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('login')">&times;</span>
    <h2>Login</h2>
    <form id="loginForm" onsubmit="return false;">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Sign In</button>
    </form>
  </div>
</div>

<!-- SIGNUP -->
<div class="modal" id="signupModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('signup')">&times;</span>
    <h2>Sign Up</h2>
    <form id="signupForm" onsubmit="return false;">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="text" name="address" placeholder="Address" required><br>
      <input type="tel" name="con_number" placeholder="Contact Number" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <button type="submit">Sign Up</button>
    </form>
  </div>
</div>

<script>
function openModal(type){ document.getElementById(type+'Modal').style.display='flex'; }
function closeModal(type){ document.getElementById(type+'Modal').style.display='none'; }
function menuPage(){ 
  window.location.href = "menu_page.php"; }
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword, sendEmailVerification, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyBtbkIBIMDP7qTOxJdsQewvz3IvHLpkepA",
  authDomain: "onlineordering-5bdde.firebaseapp.com",
  projectId: "onlineordering-5bdde",
  storageBucket: "onlineordering-5bdde.firebasestorage.app",
  messagingSenderId: "507250409621",
  appId: "1:507250409621:web:ac173d9eb858a79e899b9c",
  measurementId: "G-Y6LXMNJ2QX"
};
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

// SIGNUP
const signupForm = document.querySelector("#signupForm");
signupForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = signupForm.querySelector('input[name="email"]').value;
  const password = signupForm.querySelector('input[name="password"]').value;
  const username = signupForm.querySelector('input[name="username"]').value;
  const address = signupForm.querySelector('input[name="address"]').value;
  const contact = signupForm.querySelector('input[name="con_number"]').value;

  try {
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    await sendEmailVerification(userCredential.user);
    localStorage.setItem("pendingUser", JSON.stringify({ username, address, contact, email }));
    alert("✅ Verification email sent! Please verify before login.");
    closeModal('signup');
  } catch (error) {
    alert("❌ " + error.message);
  }
});

// LOGIN
const loginForm = document.querySelector("#loginForm");
loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = loginForm.querySelector('input[name="email"]').value;
  const password = loginForm.querySelector('input[name="password"]').value;

  try {
    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    const user = userCredential.user;

    if (user.emailVerified) {
      const pendingData = localStorage.getItem("pendingUser");

      if (pendingData) {
        const userData = JSON.parse(pendingData);
        await fetch("save_users.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(userData)
        });
        localStorage.removeItem("pendingUser");
      }

      await fetch("login_session.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email })
      });

      closeModal('login');
      window.top.location.href = "menu_page.php";
    } else {
      alert("⚠️ Please verify your email first.");
    }
  } catch (error) {
    alert("❌ " + error.message);
  }
});
</script>
</body>
</html>
