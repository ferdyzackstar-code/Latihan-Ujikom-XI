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
            // Mencari berdasarkan nama atau username
            $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE nama_user LIKE ? OR username LIKE ?");
            $stmt->bind_param("ss", $key, $key);
            $stmt->execute();
            return $stmt->get_result();
        }
        return $this->conn->query("SELECT * FROM tbl_user ORDER BY id_user DESC");
    }

    // Ambil data 1 user berdasarkan ID (untuk memicu mode isi form Edit)
    public function getIdUser($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    // Tambah data user baru lewat dashboard admin
    public function createUser($nama, $username, $password) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO tbl_user (nama_user, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $username, $pass_hash);
        return $stmt->execute();
    }

    // Update data user (jika password kosong, gunakan password lama)
    public function updateUser($id, $nama, $username, $password) {
        if (!empty($password)) {
            // Jika admin menginput password baru di form
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE tbl_user SET nama_user=?, username=?, password=? WHERE id_user=?");
            $stmt->bind_param("sssi", $nama, $username, $pass_hash, $id);
        } else {
            // Jika admin mengosongkan kolom password (password lama tidak berubah)
            $stmt = $this->conn->prepare("UPDATE tbl_user SET nama_user=?, username=? WHERE id_user=?");
            $stmt->bind_param("ssi", $nama, $username, $id);
        }
        return $stmt->execute();
    }

    // Hapus data user dari database
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
        $nama_baru = uniqid() . "." . $ext;
        
        // SUDAH DIPERBAIKI: year -> tahun
        $stmt = $this->conn->prepare("INSERT INTO tbl_buku (judul_buku, pengarang_buku, penerbit_buku, tahun, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $pengarang, $penerbit, $tahun, $nama_baru);
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

            // SUDAH DIPERBAIKI: year -> tahun, dan bind_param "sssssi"
            $stmt = $this->conn->prepare("UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, tahun=?, gambar=? WHERE id_buku=?");
            $stmt->bind_param("sssssi", $judul, $pengarang, $penerbit, $tahun, $nama_baru, $id);
            $hasil = $stmt->execute();
            
            if ($hasil) {
                move_uploaded_file($img_tmp, "../gambar/" . $nama_baru);
            }
            return $hasil;
        } else {
            // SUDAH DIPERBAIKI: year -> tahun, dan bind_param "ssssi"
            $stmt = $this->conn->prepare("UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, tahun=? WHERE id_buku=?");
            $stmt->bind_param("ssssi", $judul, $pengarang, $penerbit, $tahun, $id);
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