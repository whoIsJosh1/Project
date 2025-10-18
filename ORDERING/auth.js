// 📦 Firebase imports
import { 
  initializeApp 
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { 
  getAuth, 
  createUserWithEmailAndPassword, 
  sendEmailVerification, 
  signInWithEmailAndPassword 
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

// 🔹 Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyBtbkIBIMDP7qTOxJdsQewvz3IvHLpkepA",
  authDomain: "onlineordering-5bdde.firebaseapp.com",
  projectId: "onlineordering-5bdde",
  storageBucket: "onlineordering-5bdde.firebasestorage.app",
  messagingSenderId: "507250409621",
  appId: "1:507250409621:web:ac173d9eb858a79e899b9c",
  measurementId: "G-Y6LXMNJ2QX"
};

// 🔹 Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

$_SESSION['customer_name'] = $row['name']; // or whatever your user name column is

// ✅ SIGN UP FUNCTION (create account + send verification)
const signupForm = document.querySelector("#signupForm");
if (signupForm) {
  signupForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = signupForm.querySelector('input[name="email"]').value;
    const password = signupForm.querySelector('input[name="password"]').value;
    const username = signupForm.querySelector('input[name="username"]').value;
    const address = signupForm.querySelector('input[name="address"]').value;
    const contact = signupForm.querySelector('input[name="con_number"]').value;

    try {
      // 1️⃣ Create Firebase user
      const userCredential = await createUserWithEmailAndPassword(auth, email, password);

      // 2️⃣ Send verification email
      await sendEmailVerification(userCredential.user);

      // 3️⃣ Temporarily store user info
      localStorage.setItem("pendingUser", JSON.stringify({
        username, address, contact, email
      }));

      alert("✅ Verification email sent! Please verify your email before logging in.");
      closeModal('signup');
    } catch (error) {
      alert("❌ " + error.message);
    }
  });
}

// ✅ LOGIN FUNCTION (check verified + save to DB)
const loginForm = document.querySelector("#loginForm");
if (loginForm) {
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

          // 🔹 Save verified user to database
          const response = await fetch("save_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData)
          });
          const result = await response.json();
          console.log("Server response:", result);
          localStorage.removeItem("pendingUser");
        }

        closeModal('login');
        window.location.replace("pastryPage.html");
      } else {
        alert("⚠️ Please verify your email first.");
      }
    } catch (error) {
      alert("❌ " + error.message);
    }
  });
}
