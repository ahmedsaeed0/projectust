<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Login Page</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap Stylesheet -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Heebo', sans-serif;
    }

    .login-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .login-image img {
      max-width: 100%;
      height: auto;
    }

    h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #343a40;
    }

    .form-control {
      border-radius: 5px;
      height: 45px;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 1rem;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .social-login a {
      font-size: 1.2rem;
      margin: 0 10px;
      color: #6c757d;
      text-decoration: none;
      transition: color 0.2s;
    }

    .social-login a:hover {
      color: #007bff;
    }
  </style>
</head>

<body>
  <section class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="row login-container align-items-center">
            <!-- Image Section -->
            <div class="col-md-6 login-image">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="537.64" height="563.26" viewBox="0 0 537.64 563.26"><path id="uuid-fb6bba6d-324d-4625-a98c-3d990729dcd8-114-164-127-108" d="m294.36,308.7c1.69,8.48,7.72,13.98,13.47,12.28,5.75-1.7,9.04-9.96,7.35-18.44-.63-3.4-2.11-6.52-4.32-9.07l-7.63-35.8-17.84,5.88,9.42,34.67c-1.01,3.51-1.16,7.11-.43,10.48,0,0,0,0,0,0Z" fill="#f8a8ab"/><rect x="254.14" y="514.38" width="20.94" height="29.71" transform="translate(529.23 1058.47) rotate(-180)" fill="#f8a8ab"/><path d="m272.77,561.11c-3.58.32-21.5,1.74-22.4-2.37-.82-3.77.39-7.71.56-8.25,1.72-17.14,2.36-17.33,2.75-17.44.61-.18,2.39.67,5.28,2.53l.18.12.04.21c.05.27,1.33,6.56,7.4,5.59,4.16-.66,5.51-1.58,5.94-2.03-.35-.16-.79-.44-1.1-.92-.45-.7-.53-1.6-.23-2.68.78-2.85,3.12-7.06,3.22-7.23l.27-.48,23.8,16.06,14.7,4.2c1.11.32,2,1.11,2.45,2.17h0c.62,1.48.24,3.2-.96,4.28-2.67,2.4-7.97,6.51-13.54,7.02-1.48.14-3.44.19-5.64.19-9.19,0-22.61-.95-22.71-.97Z" fill="#2f2e43"/><rect x="196.13" y="514.38" width="20.94" height="29.71" transform="translate(413.21 1058.47) rotate(-180)" fill="#f8a8ab"/><path d="m214.76,561.11c-3.58.32-21.5,1.74-22.4-2.37-.82-3.77.39-7.71.56-8.25,1.72-17.14,2.36-17.33,2.75-17.44.61-.18,2.39.67,5.28,2.53l.18.12.04.21c.05.27,1.33,6.56,7.4,5.59,4.16-.66,5.51-1.58,5.94-2.03-.35-.16-.79-.44-1.1-.92-.45-.7-.53-1.6-.23-2.68.78-2.85,3.12-7.06,3.22-7.23l.27-.48,23.8,16.06,14.7,4.2c1.11.32,2,1.11,2.45,2.17h0c.62,1.48.24,3.2-.96,4.28-2.67,2.4-7.97,6.51-13.54,7.02-1.48.14-3.44.19-5.64.19-9.19,0-22.61-.95-22.71-.97Z" fill="#2f2e43"/><polygon points="213.11 100.28 245.58 110.95 245.58 64.21 216.12 64.21 213.11 100.28" fill="#f8a8ab"/><circle cx="241.56" cy="44.8" r="32.35" fill="#f8a8ab"/><path d="m233.32,47.33l4.46,5.41,8.07-14.12s10.3.53,10.3-7.11c0-7.64,9.45-7.86,9.45-7.86,0,0,13.37-23.35-14.33-17.2,0,0-19.21-13.16-28.77-1.91,0,0-29.3,14.75-20.91,40.44l13.93,26.48,3.16-5.99s-1.91-25.16,14.65-18.15Z" fill="#2f2e43"/><path d="m0,562.07c0,.66.53,1.19,1.19,1.19h535.26c.66,0,1.19-.53,1.19-1.19s-.53-1.19-1.19-1.19H1.19c-.66,0-1.19.53-1.19,1.19Z" fill="#484565"/><path d="m328.8,349.01l-61.13-19.65c-7.54-2.42-15.64,1.63-18.21,9.13l-27.62,80.54c-2.82,8.22,2.16,17.06,10.65,18.92l70.26,15.37c7.72,1.69,15.38-3.1,17.24-10.78l18.49-76.26c1.79-7.4-2.43-14.94-9.68-17.27Z" fill="#e2e3e4"/><path d="m322.6,366l-3.94-.7c2.52-14.32,6-52.5-8.05-57.18-6.81-2.27-13.67,1.7-20.38,11.81-5.27,7.95-8.35,16.75-8.38,16.84l-3.78-1.31c.54-1.55,13.39-37.94,33.8-31.14,20.17,6.72,11.12,59.43,10.72,61.67Z" fill="#2f2e43"/><polygon points="276.25 254.19 166.98 254.19 193.72 529.88 224.72 527.88 226.25 329.34 245.76 416.47 251.47 526.38 279.72 527.88 283 402.85 276.25 254.19" fill="#2f2e43"/><polygon points="211.34 83.19 248.34 92.19 279.97 228.88 276.25 254.19 183.6 269.87 166.98 254.19 211.34 83.19" fill="#6c63ff"/><polygon points="211.28 76.35 198.63 90.54 172.97 96.7 129.22 291.22 213.52 305.5 237 107.39 211.28 76.35" fill="#2f2e43"/><polygon points="248.32 83.44 241.72 106.96 272.27 317.88 288.03 317.88 280.76 111.48 259.36 94.48 248.32 83.44" fill="#2f2e43"/><path d="m268.07,108.87l12.69,2.61s5.56,2.04,7.58,17.84c2.01,15.8,2.37,68.86,2.37,68.86l21.58,85.6-24.51,8.08-11.54-46.06-8.18-136.94Z" fill="#2f2e43"/><polygon points="246.9 97.89 240.76 109.15 253.04 241.23 238.71 254.54 225.4 242.26 235.13 109.66 220.95 97.89 225.91 94.3 231.03 100.44 241.27 98.4 245.36 92.25 246.9 97.89" fill="#2f2e43"/><path id="uuid-4155b336-10d9-4b14-826d-07551e167be9-115-165-128-109" d="m186.59,275.51c5.15,6.95,12.95,9.34,17.42,5.35,4.47-3.99,3.92-12.86-1.23-19.82-2.02-2.81-4.69-4.99-7.79-6.35l-22.2-29.11-13.62,12.94,23.33,27.32c.59,3.61,1.99,6.92,4.09,9.66,0,0,0,0,0,0Z" fill="#f8a8ab"/><path d="m183.11,99.89l-10.14-3.19s-13.74,3.41-18.29,20.57l-22.21,83.6,37.67,66.12,20.13-24.44-18.96-60.7,11.81-81.96Z" fill="#2f2e43"/></svg>            </div>
            <!-- Form Section -->
            <div class="col-md-6">
              <h1 class="text-center mb-4">Log In</h1>
              <form action="../fun/login.php" method="POST">
                <!-- Account Type Selection -->
                <div class="mb-3">
                  <label for="accountType" class="form-label">Account Type</label>
                  <select id="accountType" name="accountType" class="form-select" required>
                    <option value="" disabled selected>Select Account Type</option>
                    <option value="company">Company</option>
                    <option value="student">Student</option>
                  </select>
                </div>
                <!-- Username -->
                <div class="mb-3">
                  <label class="form-label" for="UserName">Username</label>
                  <input class="form-control" type="text" id="UserName" name="UserName" placeholder="Enter your username" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                  <label class="form-label" for="pass">Password</label>
                  <input class="form-control" type="password" id="pass" name="pass" placeholder="Enter your password" required>
                </div>
                <!-- Remember Me -->
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <!-- Submit Button -->
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Log In</button>
                </div>
              </form>
              <!-- Links -->
              <div class="text-center mt-3">
                <a href="register.php" class="text-decoration-none">Create an account</a>
              </div>
              <!-- <div class="text-center mt-4 social-login">
                <p>Or login with</p>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-google"></i></a>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>