<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>

  <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/">Shipment</a>
      </li>
      <li class="nav-item" id="managementRoleTab" style="display: none;">
        <a class="nav-link" href="/management-user">Management Role</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span id="usernameDisplay">Username</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="profile">Edit Profile</a>
          <a class="dropdown-item text-danger" href="#" id="logoutBtn">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<!-- Form Edit Profile -->
<div class="container mt-4">
    <h2 class="text-center">Edit Profile</h2>
    <form id="edit-profile-form">
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="" required>
        </div>

        <div class="form-group">
            <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script>
        function logout() {
        localStorage.removeItem("user"); 
      document.cookie = "access_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      window.location.href = "/login";
    }

    document.addEventListener("DOMContentLoaded", function () {
    const user = JSON.parse(localStorage.getItem("user"));

    if (user) {
        document.getElementById("usernameDisplay").textContent = user.name;

        if (user.role !== "user") {
            document.getElementById("managementRoleTab").style.display = "block";
        }
    } else {
        logout();
    }

    document.getElementById("logoutBtn").addEventListener("click", function (e) {
        e.preventDefault();
        logout();
    });
      fetchData();
    });

    $(document).ready(function() {
    const user = JSON.parse(localStorage.getItem("user")) || {};

    $("#name").val(user.name || "");
    $("#email").val(user.email || "");

    $("#edit-profile-form").submit(function(e) {
        e.preventDefault();
        
        const name = $("#name").val();
        const email = $("#email").val();
        const password = $("#password").val();
        const password_confirmation = $("#password_confirmation").val();
        function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    const token = getCookie("access_token");
        const user = JSON.parse(localStorage.getItem("user")) || {};

        if (password && password !== password_confirmation) {
            alert("Password dan Konfirmasi Password harus sama!");
            return;
        }

        const updatedUser = {
            name,
            email,
            ...(password ? { password, password_confirmation } : {})
        };

        $.ajax({
            url: "/api/user?id=" + user.id,
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify(updatedUser),
            headers: { "Authorization": `Bearer ${token}` }, 
            success: function(response) {
                alert("Profile berhasil diperbarui!");
                
                localStorage.setItem("user", JSON.stringify(response.data));

                window.location.href = "/";
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message || "Terjadi kesalahan, coba lagi.");
            }
        });
    });
});
</script>

</body>
</html>
