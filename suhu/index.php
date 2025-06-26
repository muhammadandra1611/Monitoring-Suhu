<?php
//koneksi ke database user
include './assets/function.php';
$air=new klass_air;
$koneksi=$air->koneksi();

//masukan data tabel ke user
// $pass = password_hash("admin", PASSWORD_DEFAULT);
// mysqli_query($koneksi,"INSERT INTO user1(username, password, nama, alamat, tlp, level) VALUES ('admin','$pass','admin','Tembalang','085850','admin')");
// if(mysqli_affected_rows($koneksi) > 0) echo "data berhasil masuk...";
// else echo "data gagal masuk...";

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Admin Dashboard</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .card {
                border-radius: 15px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.9);
            }
            .card-header {
                background: transparent;
                border-bottom: none;
                padding-top: 30px;
            }
            .card-header h3 {
                color: #333;
                font-weight: 600;
                font-size: 24px;
            }
            .form-floating input {
                border-radius: 10px;
                border: 1px solid #ddd;
                padding: 15px;
                transition: all 0.3s ease;
            }
            .form-floating input:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 10px;
                padding: 12px 30px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            }
            .card-footer {
                background: transparent;
                border-top: none;
            }
            .small a {
                color: #667eea;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .small a:hover {
                color: #764ba2;
            }
            .form-check-input:checked {
                background-color: #667eea;
                border-color: #667eea;
            }
            #layoutAuthentication_footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            .alert {
                border-radius: 10px;
                margin-bottom: 20px;
            }
            @media (max-width: 576px) {
                .container {
                    padding: 0 15px;
                }
                .card {
                    margin: 15px;
                }
            }
        </style>
    </head>
    <body>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header text-center">
                                        <i class="fas fa-user-circle fa-3x mb-3" style="color: #667eea;"></i>
                                        <h3 class="font-weight-light">Welcome! </h3>
                                        <p class="text-muted">Please login to your account</p>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        if(isset($_POST['tombol'])) {
                                            $username = $_POST['user'];
                                            $password = $_POST['password'];

                                            $qc=mysqli_query($koneksi, "SELECT username,password FROM user1 WHERE username='$username'");
                                            $dc=mysqli_fetch_row($qc);

                                            if(!empty($dc[0])) $user_cek = $dc[0];
                                            
                                            if(!empty($user_cek)) {
                                                $pass_cek=$dc[1];
                                                if (password_verify($password, $pass_cek)) {
                                                    session_start();
                                                    $_SESSION['user']=$username;
                                                    $_SESSION['pass']=$password;
                                                    echo "<script>window.location.replace('./backend/index.php')</script>";
                                                } else {
                                                    echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                                            <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                            <i class=\"fas fa-exclamation-circle me-2\"></i>Invalid password. Please try again.
                                                        </div>";
                                                }
                                            } else {
                                                echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                                        <button type=button class=btn-close data-bs-dismiss=alert></button>
                                                        <i class=\"fas fa-exclamation-circle me-2\"></i>Username not found.
                                                    </div>";
                                            }
                                        } 
                                        ?>
                                        <form method="post" class="needs-validation">
                                            <div class="form-floating mb-4">
                                                <input class="form-control" id="inputUser" type="text" placeholder="Username" name="user" required/>
                                                <label for="inputUser"><i class="fas fa-user me-2"></i>Username</label>
                                            </div>
                                            <div class="form-floating mb-4">
                                                <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" required/>
                                                <label for="inputPassword"><i class="fas fa-lock me-2"></i>Password</label>
                                            </div>
                                            <div class="form-check mb-4">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-grid gap-2 mb-4">
                                                <input type="submit" name="tombol" value="Sign In" class="btn btn-primary btn-lg">
                                            </div>
                                            <div class="text-center">
                                                <a class="small" href="password.html"></a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"> <a href="register.html">Sign up now!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2025</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
