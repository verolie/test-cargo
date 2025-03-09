<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
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

  <!-- Tabel Data -->
  <div class="container mt-4" style="max-width: 80%;">
    <h2 class="text-center">Daftar Data User</h2>
    <div class="text-right mb-3">
      <button class="btn btn-success" data-toggle="modal" data-target="#addDataModal">
        <i class="fas fa-plus"></i> Tambah Data
      </button>
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <!-- Data akan diisi oleh JavaScript -->
        </tbody>
      </table>
    </div>
    <!-- Total Data -->
    <div class="text-right mt-3">
      <h5>Total Data: <span id="total-data">0</span></h5>
    </div>
  </div>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Data User</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addDataForm">
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" id="nama" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" id="email" required />
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" id="password" required />
            </div>
            <div class="form-group">
              <label>Role</label>
              <select class="form-control" id="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="1">Admin</option>
                <option value="2">User</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Data -->
  <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data User</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editDataForm">
            <!-- Hidden field untuk menyimpan ID user -->
            <input type="hidden" id="editUserId" />
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" id="editNama" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" id="editEmail" required />
            </div>
            <div class="form-group">
              <label>Password (Isi jika ingin mengubah)</label>
              <input type="password" class="form-control" id="editPassword" />
            </div>
            <div class="form-group">
              <label>Password Confirmation</label>
              <input type="password" class="form-control" id="editPasswordConfirmation" />
            </div>
            <div class="form-group">
              <label>Role</label>
              <select class="form-control" id="editRole" required>
                <option value="">-- Pilih Role --</option>
                <option value="1">Admin</option>
                <option value="2">User</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script>
    function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
    }

    let usersData = [];

    async function fetchData() {
      const token = getCookie("access_token");
      try {
        const response = await fetch("http://127.0.0.1:8000/api/user", {
          method: "GET",
          headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        if (data.status === "success") {
          usersData = data.data.data;
          renderTable(usersData);
          updateTotal(data.data.total);
        } else {
          console.error("Gagal mengambil data:", data.detail);
        }
      } catch (error) {
        console.error("Error:", error);
      }
    }

    function renderTable(data) {
      const tableBody = $("#table-body");
      tableBody.empty();

      data.forEach((item, index) => {
        const row = `
          <tr>
            <td>${index + 1}</td>
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>${item.role ? item.role : "Tidak ada peran"}</td>
            <td>
              <button class="btn btn-warning btn-sm" onclick="editUser(${item.id})">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm" onclick="deleteUser(${item.id})">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        `;
        tableBody.append(row);
      });
    }

    function updateTotal(total) {
      document.getElementById("total-data").textContent = `${total}`;
    }

    document.getElementById("addDataForm").addEventListener("submit", async function (e) {
      e.preventDefault();
      const token = getCookie("access_token");
      const payload = {
        name: document.getElementById("nama").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        password_confirmation: document.getElementById("password").value,
        roleId: parseInt(document.getElementById("role").value)
      };

      try {
        const response = await fetch("http://127.0.0.1:8000/api/register", {
          method: "POST",
          headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();
        if (response.ok) {
          alert("Data berhasil ditambahkan!");
          $("#addDataModal").modal("hide");
          fetchData();
        } else {
          alert("Gagal menambahkan data: " + result.detail);
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Terjadi kesalahan, coba lagi.");
      }
    });

    // Fungsi untuk menghapus user
    async function deleteUser(id) {
      const token = getCookie("access_token");
      if (!confirm("Yakin ingin menghapus data?")) return;

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/user?id=${id}`, {
          method: "DELETE",
          headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
          }
        });

        if (response.ok) {
          alert("Data berhasil dihapus!");
          fetchData(); // Refresh tabel
        } else {
          alert("Gagal menghapus data.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Terjadi kesalahan.");
      }
    }
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

    function editUser(id) {
      const user = usersData.find(u => u.id === id);
      if (user) {
        $("#editUserId").val(user.id);
        $("#editNama").val(user.name);
        $("#editEmail").val(user.email);
        if (user.role) {
          $("#editRole").val(user.role.id);
        } else {
          $("#editRole").val("");
        }
        $("#editPassword").val('');
        $("#editPasswordConfirmation").val('');
        $("#editDataModal").modal("show");
      }
    }

    document.getElementById("editDataForm").addEventListener("submit", async function (e) {
  e.preventDefault();
  const token = getCookie("access_token");

  const userId = document.getElementById("editUserId").value;
  const oldUserData = usersData.find(user => user.id == userId);
  
  const newEmail = document.getElementById("editEmail").value;
  const payload = {
    name: document.getElementById("editNama").value,
    roleId: parseInt(document.getElementById("editRole").value)
  };

  if (newEmail !== oldUserData.email) {
    payload.email = newEmail;
  }

    const newPassword = document.getElementById("editPassword").value;
      if (newPassword.trim()) {
        payload.password = newPassword;
        payload.password_confirmation = document.getElementById("editPasswordConfirmation").value;
      }

      try {
        const response = await fetch(`http://127.0.0.1:8000/api/user?id=${userId}`, {
          method: "PUT",
          headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();
        if (response.ok) {
          alert("Data berhasil diperbarui!");
          $("#editDataModal").modal("hide");
          fetchData(); 
        } else {
          alert("Gagal memperbarui data: " + result.detail);
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Terjadi kesalahan, coba lagi.");
      }
    });

  </script>
</body>
</html>
