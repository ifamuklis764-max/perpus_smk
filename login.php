<?php
session_start();
require_once 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['admin_login'] = true;
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - perpus_api</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            /* Gradasi Latar Belakang Biru Muda Soft Pastel */
            background: linear-gradient(135deg, #cbe5ff 0%, #eef6ff 50%, #d8ebff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi Awan/Bulatan Soft */
        .shape-top {
            position: absolute;
            top: -100px;
            left: -80px;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            filter: blur(30px);
        }

        .shape-bottom {
            position: absolute;
            bottom: -100px;
            right: -80px;
            width: 400px;
            height: 400px;
            background: rgba(186, 224, 255, 0.5);
            border-radius: 50%;
            filter: blur(40px);
        }

        /* Card Container Glassmorphism Soft */
        .login-card {
            position: relative;
            z-index: 2;
            width: 820px;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: flex;
            overflow: hidden;
        }

        /* Panel Kiri (Biru Muda Pastel) */
        .card-left {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .card-left::before {
            content: '';
            position: absolute;
            bottom: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(5px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .card-left h2 {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.25;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .card-left p {
            font-size: 13.5px;
            color: #e0f2fe;
            line-height: 1.5;
        }

        .footer-tag {
            font-size: 11px;
            letter-spacing: 1px;
            color: #bfdbfe;
            font-weight: 600;
        }

        /* Panel Kanan (Form Input) */
        .card-right {
            flex: 1.1;
            padding: 45px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .card-right h3 {
            font-size: 22px;
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card-right .subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 25px;
        }

        .error-msg {
            background-color: #fef2f2;
            color: #ef4444;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            border: 1px solid #fee2e2;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 12.5px;
            color: #475569;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 14px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 10px;
            font-size: 13.5px;
            color: #1e293b;
            outline: none;
            transition: all 0.25s ease;
        }

        .input-wrapper input:focus {
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .btn-submit {
            width: 100%;
            padding: 12.5px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13.5px;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
            transition: all 0.25s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            .card-left, .card-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Background Decoration -->
    <div class="shape-top"></div>
    <div class="shape-bottom"></div>

    <div class="login-card">
        <!-- Panel Kiri (Biru Soft/Pastel) -->
        <div class="card-left">
            <div>
                <div class="brand-icon">
                    <i class="fa-solid fa-book"></i>
                </div>
                <h2>Selamat<br>datang<br>kembali.</h2>
                <p>Kelola koleksi dan aktivitas perpustakaan dengan lebih mudah.</p>
            </div>
            <div class="footer-tag">PERPUS API · PANEL ADMIN</div>
        </div>

        <!-- Panel Kanan (Form Input) -->
        <div class="card-right">
            <h3>Masuk ke akun</h3>
            <p class="subtitle">Gunakan akun admin untuk melanjutkan.</p>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-submit">MASUK</button>
            </form>
        </div>
    </div>

</body>
</html>