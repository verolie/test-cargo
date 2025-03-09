<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Akun</title>
</head>
<body>

<div class="container" style="margin-top: 50px">
    <div class="row">
        <div class="col-md-5 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <label>LOGIN</label>
                    <hr>

                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Masukkan Alamat Email">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                    </div>

                    <button class="btn btn-login btn-block btn-success bg-dark">Login</button>

                </div>
            </div>

            <div class="text-center" style="margin-top: 15px">
                Belum punya akun? <a href="/register">Silahkan Register</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        $(".btn-login").click(function() {
            var email = $("#email").val();
            var password = $("#password").val();
            var token = $("meta[name='csrf-token']").attr("content");

            if(email.length == "") {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Alamat Email Wajib Diisi!' });
                return;
            } else if(password.length == "") {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Password Wajib Diisi!' });
                return;
            }

            // Kirim data ke backend
            $.ajax({
                url: "/api/login",
                type: "POST",
                data: { email: email, password: password, _token: token },
                success: function(response) {
                    if (response.data.access_token) {
                        document.cookie = `access_token=${response.data.access_token}; path=/; max-age=86400; Secure`;

                        localStorage.setItem("user", JSON.stringify(response.data.user));

                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil!',
                            text: 'Anda akan dialihkan...',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "/";
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: xhr.responseJSON.message || 'Terjadi kesalahan!' });
                }
            });

        });
    });
</script>

</body>
</html>