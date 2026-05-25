<?php
class Query {
    private $conn;

    // Menangkap koneksi database dari luar
    public function __construct($koneksi) {
        $this->conn = $koneksi;
    }

    // ==================== LOGIKA DATA USER ====================
    
    // Tampil data + fitur pencarian user
    public function readUser($keyword = "") {
        if (!empty($keyword)) {
            $key = "%$keyword%";
            $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE nama_user LIKE ? OR username LIKE ?");
            $stmt->bind_param("ss", $key, $key);
            $stmt->execute();
            return $stmt->get_result();
        }
        return $this->conn->query("SELECT * FROM tbl_user ORDER BY id_user DESC");
    }

    // Ambil data 1 user berdasarkan ID (untuk mode Edit)
    public function getIdUser($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    // Tambah data user baru lewat dashboard
    public function createUser($nama, $username, $password) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tbl_user (nama_user, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $username, $pass_hash);
        return $stmt->execute();
    }

    // Update data user (jika password kosong, pakai password lama)
    public function updateUser($id, $nama, $username, $password) {
        if (!empty($password)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE tbl_user SET nama_user=?, username=?, password=? WHERE id_user=?");
            $stmt->bind_param("sssi", $nama, $username, $pass_hash, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE tbl_user SET nama_user=?, username=? WHERE id_user=?");
            $stmt->bind_param("ssi", $nama, $username, $id);
        }
        return $stmt->execute();
    }

    // Hapus data user
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM tbl_user WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ==================== LOGIKA DATA BUKU ====================
    
    // Tampil data + fitur pencarian buku
    public function readBuku($keyword = "") {
        if (!empty($keyword)) {
            $key = "%$keyword%";
            $stmt = $this->conn->prepare("SELECT * FROM tbl_buku WHERE judul_buku LIKE ? OR pengarang_buku LIKE ?");
            $stmt->bind_param("ss", $key, $key);
            $stmt->execute();
            return $stmt->get_result();
        }
        return $this->conn->query("SELECT * FROM tbl_buku ORDER BY id_buku DESC");
    }

    // Ambil data 1 buku berdasarkan ID (untuk mode Edit)
    public function getIdBuku($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_buku WHERE id_buku = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    // Tambah data buku beserta upload gambarnya
    public function createBuku($judul, $pengarang, $penerbit, $tahun, $img_name, $img_tmp) {
        $ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $nama_baru = uniqid() . "." . $ext; // Nama gambar acak agar tidak bentrok
        
        $stmt = $this->conn->prepare("INSERT INTO tbl_buku (judul_buku, pengarang_buku, penerbit_buku, year, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $judul, $pengarang, $penerbit, $tahun, $nama_baru);
        $hasil = $stmt->execute();
        
        if ($hasil) {
            move_uploaded_file($img_tmp, "../gambar/" . $nama_baru);
        }
        return $hasil;
    }

    // Update data buku (fleksibel jika gambar ganti atau tidak)
    public function updateBuku($id, $judul, $pengarang, $penerbit, $tahun, $img_name, $img_tmp) {
        if (!empty($img_name)) {
            $ext = pathinfo($img_name, PATHINFO_EXTENSION);
            $nama_baru = uniqid() . "." . $ext;

            $stmt = $this->conn->prepare("UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, year=?, gambar=? WHERE id_buku=?");
            $stmt->bind_param("ssmisi", $judul, $pengarang, $penerbit, $tahun, $nama_baru, $id);
            $hasil = $stmt->execute();
            
            if ($hasil) {
                move_uploaded_file($img_tmp, "../gambar/" . $nama_baru);
            }
            return $hasil;
        } else {
            $stmt = $this->conn->prepare("UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, year=? WHERE id_buku=?");
            $stmt->bind_param("sssii", $judul, $pengarang, $penerbit, $tahun, $id);
            return $stmt->execute();
        }
    }

    // Hapus data buku
    public function deleteBuku($id) {
        $stmt = $this->conn->prepare("DELETE FROM tbl_buku WHERE id_buku = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>  