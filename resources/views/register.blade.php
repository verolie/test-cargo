<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Akun</title>
</head>
<body>

<div class="container" style="margin-top: 50px">
    <div class="row">
        <div class="col-md-5 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <label>REGISTER</label>
                    <hr>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" placeholder="Masukkan Nama Lengkap">
                    </div>

                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Masukkan Alamat Email">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                    </div>

                    <button class="btn btn-register btn-block btn-success bg-dark">Register</button>


                </div>
            </div>

            <div class="text-center" style="margin-top: 15px">
                Sudah punya akun? <a href="/login">Silahkan Login</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function () {
    $(".btn-register").click(function () {
        var nama_lengkap = $("#nama_lengkap").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var token = $("meta[name='csrf-token']").attr("content");

        if (nama_lengkap.length === "") {
            Swal.fire({ icon: "warning", title: "Oops...", text: "Nama Lengkap Wajib Diisi!" });
            return;
        } 
        if (email.length === "") {
            Swal.fire({ icon: "warning", title: "Oops...", text: "Alamat Email Wajib Diisi!" });
            return;
        } 
        if (password.length < 6) {
            Swal.fire({ icon: "warning", title: "Oops...", text: "Password minimal 6 karakter!" });
            return;
        }

        $.ajax({
            url: "/api/check-email?email=" + email,
            type: "GET",
            success: function (response) {
                if (response.email_exists) {
                    Swal.fire({ icon: "error", title: "Oops...", text: "Email sudah terdaftar!" });
                } else {
                    registerUser(nama_lengkap, email, password, token);
                }
            },
            error: function (xhr) {
                Swal.fire({ icon: "error", title: "Oops...", text: "Terjadi kesalahan saat cek email!" });
            },
        });
    });
    function registerUser(nama_lengkap, email, password, token) {
        $.ajax({
            url: "/api/register",
            type: "POST",
            data: {
                name: nama_lengkap,
                email: email,
                password: password,
                password_confirmation: password,
                roleId: 2,
                _token: token,
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Registrasi Berhasil!",
                    text: "Silakan login",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = "/login";
                });
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON.message || "Terjadi kesalahan!";
                Swal.fire({ icon: "error", title: "Oops...", text: errorMessage });
            },
        });
    }
});

</script>

</body>
</html>