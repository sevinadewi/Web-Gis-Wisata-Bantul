<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Login and Registration Form in HTML & CSS | CodingLab </title>
    <link rel="stylesheet" href="css/login.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  <div class="container">
    <?php if (isset($_GET['status'])): ?>
        <div style="text-align:center; padding: 10px;">
            <?php if ($_GET['status'] == 'success'): ?>
            <p style="color: green;">Registrasi berhasil! Silakan login.</p>
            <?php elseif ($_GET['status'] == 'exists'): ?>
            <p style="color: orange;">Email sudah terdaftar. Gunakan email lain.</p>
            <?php else: ?>
            <p style="color: red;">Registrasi gagal! Coba lagi nanti.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>




    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="img/mangunan2.jpg" alt="">
        <div class="text">
          <span class="text-1">Cari Destinasi Wisatamu<br> di Wilayah Bantul</span>
          <span class="text-2">Let's get connected</span>
        </div>
      </div>
      <div class="back">
        <!--<img class="backImg" src="images/backImg.jpg" alt="">-->
        <div class="text">
          <span class="text-1">Complete miles of journey <br> with one step</span>
          <span class="text-2">Let's get started</span>
        </div>
      </div>
    </div>
    <div class="forms">
        <div class="form-content">
          <div class="login-form">
            <div class="title">Login</div>
                <form action="auth/login.php" method="POST">
                    <div class="input-boxes">
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input type="text" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="text"><a href="#">Forgot password?</a></div>
                    <div class="button input-box">
                        <input type="submit" value="Sumbit">
                    </div>
                    <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div>
                    </div>
                </form>
            </div>
        <div class="signup-form">
          <div class="title">Signup</div>
            <form action="auth/register.php" method="POST">
                <div class="input-boxes">
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="Enter your name" required>
                </div>
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="button input-box">
                    <input type="submit" value="Sumbit">
                </div>
                <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                </div>
            </form>
    </div>
    </div>
    </div>
  </div>
</body>
</html>