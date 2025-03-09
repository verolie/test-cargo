<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <h2 class="text-center">Daftar Data Shipment</h2>
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
                    <th>Nama Pengirim</th>
                    <th>Alamat Pengirim</th>
                    <th>Nomor Telpon Pengirim</th>
                    <th>Nama Penerima</th>
                    <th>Alamat Penerima</th>
                    <th>Nomor Telpon Penerima</th>
                    <th>Deskripsi Barang</th>
                    <th>Berat Barang</th>
                    <th>Harga Barang</th>
                    <th>Id Tracking Barang</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
    <!-- Total Harga -->
    <div class="text-right mt-3">
        <h5>Total Data: <span id="total-data">0</span></h5>
    </div>
</div>

<!-- Modal Tracking Shipment -->
<div class="modal fade" id="trackingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tracking Shipment</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="shipmentTitle"></h6>
                <ul class="list-group" id="trackingDetails">
                    <!-- Tracking data akan diisi oleh JavaScript -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="addDataModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pengiriman</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDataForm">
                    <div class="form-group">
                        <label>Nama Pengirim</label>
                        <input type="text" class="form-control" id="namaPengirim" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Pengirim</label>
                        <input type="text" class="form-control" id="alamatPengirim" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telpon Pengirim</label>
                        <input type="text" class="form-control" id="nomorTelponPengirim" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" class="form-control" id="namaPenerima" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Penerima</label>
                        <input type="text" class="form-control" id="alamatPenerima" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telpon Penerima</label>
                        <input type="text" class="form-control" id="nomorTelponPenerima" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Barang</label>
                        <input type="text" class="form-control" id="descBarang" required>
                    </div>
                    <div class="form-group">
                        <label>Berat Barang (gram)</label>
                        <input type="number" class="form-control" id="beratBarang" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" class="form-control" id="hargaBarang" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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

    async function fetchData() {
        const token = getCookie("access_token");
        try {
            const response = await fetch("http://127.0.0.1:8000/api/order-shipment", {
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
                renderTable(data.data.data);
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
                    <td>${item.namaPengirim}</td>
                    <td>${item.alamatPengirim}</td>
                    <td>${item.nomorTelponPengirim}</td>
                    <td>${item.namaPenerima}</td>
                    <td>${item.alamatPenerima}</td>
                    <td>${item.nomorTelponPenerima}</td>
                    <td>${item.descBarang}</td>
                    <td>${item.beratBarang} gr</td>
                    <td>Rp ${item.hargaBarang}</td>
                    <td>${item.idTrackingBitship}</td>
                    <td>${item.created_by || 'Admin'}</td>
                    <td>${new Date(item.created_at).toLocaleString()}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="seeDetail('${item.idTrackingBitship}')">
                            <i class="fas fa-eye"></i>
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
    

    async function seeDetail(idTrackingBitship) {
    const apiUrl = `https://api.biteship.com/v1/trackings/${idTrackingBitship}`;
    
    try {
        const response = await fetch(apiUrl, {
            method: "GET",
            headers: {
                "authorization": "biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoidGVzdC1zaGlwIiwidXNlcklkIjoiNjdjZDBlYmZkMzhlODkwMDEyMWQ2ZmIzIiwiaWF0IjoxNzQxNDkyODYzfQ.tE1wAiJglo0NE2K3ubtAcaqE7A1VrsL4YkRHv7dksqY",
                "Content-Type": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log("Tracking Data:", data);

        document.getElementById("shipmentTitle").textContent = `Tracking Info - ${idTrackingBitship}`;
        const trackingList = document.getElementById("trackingDetails");
        trackingList.innerHTML = "";

        if (data.history && data.history.length > 0) {
            data.history.forEach(entry => {
                trackingList.innerHTML += `
                    <li class="list-group-item">
                        <strong>${new Date(entry.updated_at).toLocaleString()}</strong><br>
                        <span>Status: <b>${entry.status}</b></span><br>
                        <small>${entry.note}</small>
                    </li>
                `;
            });
        } else {
            trackingList.innerHTML = '<li class="list-group-item text-muted">No tracking history available</li>';
        }

        $("#trackingModal").modal("show");
    } catch (error) {
        console.error("Error fetching tracking data:", error);
        alert("Gagal mengambil data tracking!");
    }
    }

    document.getElementById("addDataForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        const token = getCookie("access_token");
        const payload = {
            namaPengirim: document.getElementById("namaPengirim").value,
            alamatPengirim: document.getElementById("alamatPengirim").value,
            nomorTelponPengirim: document.getElementById("nomorTelponPengirim").value,
            namaPenerima: document.getElementById("namaPenerima").value,
            alamatPenerima: document.getElementById("alamatPenerima").value,
            nomorTelponPenerima: document.getElementById("nomorTelponPenerima").value,
            descBarang: document.getElementById("descBarang").value,
            beratBarang: parseInt(document.getElementById("beratBarang").value),
            hargaBarang: parseInt(document.getElementById("hargaBarang").value),
            createdBy: "admin123"
        };

        try {
            const response = await fetch("http://127.0.0.1:8000/api/order-shipment", {
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
        logout(); // Jika user tidak ditemukan, langsung logout
    }

    document.getElementById("logoutBtn").addEventListener("click", function (e) {
        e.preventDefault();
        logout();
    });

        fetchData();
    });
</script>

</body>
</html>
