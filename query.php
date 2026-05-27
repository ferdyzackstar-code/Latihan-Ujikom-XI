<?php
class Query
{
    private $conn;

    // Menangkap koneksi database dari luar
    public function __construct($koneksi)
    {
        $this->conn = $koneksi;
    }

    // ==================== LOGIKA DATA USER ====================

    // Tampil data + fitur pencarian user
    public function readUser($keyword = '')
    {
        if (!empty($keyword)) {
            $key = "%$keyword%";
            $stmt = $this->conn->prepare('SELECT * FROM tbl_user WHERE nama_user LIKE ? OR username LIKE ?');
            $stmt->bind_param('ss', $key, $key);
        } else {
            // Diubah ke prepare demi konsistensi keamanan
            $stmt = $this->conn->prepare('SELECT * FROM tbl_user ORDER BY id_user DESC');
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    // Ambil data 1 user berdasarkan ID
    public function getIdUser($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM tbl_user WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    // Tambah data user baru (Password hashing dipindah ke file proses agar class tetap clean, tapi di sini tetap aman)
    public function createUser($nama, $username, $password)
    {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare('INSERT INTO tbl_user (nama_user, username, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nama, $username, $pass_hash);
        return $stmt->execute();
    }

    // Update data user
    public function updateUser($id, $nama, $username, $password)
    {
        if (!empty($password)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare('UPDATE tbl_user SET nama_user=?, username=?, password=? WHERE id_user=?');
            $stmt->bind_param('sssi', $nama, $username, $pass_hash, $id);
        } else {
            $stmt = $this->conn->prepare('UPDATE tbl_user SET nama_user=?, username=? WHERE id_user=?');
            $stmt->bind_param('ssi', $nama, $username, $id);
        }
        return $stmt->execute();
    }

    // Hapus data user
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare('DELETE FROM tbl_user WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ==================== LOGIKA DATA BUKU ====================

    // Tampil data + fitur pencarian buku
    public function readBuku($keyword = '')
    {
        if (!empty($keyword)) {
            $key = "%$keyword%";
            $stmt = $this->conn->prepare('SELECT * FROM tbl_buku WHERE judul_buku LIKE ? OR pengarang_buku LIKE ?');
            $stmt->bind_param('ss', $key, $key);
        } else {
            $stmt = $this->conn->prepare('SELECT * FROM tbl_buku ORDER BY id_buku DESC');
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    // Ambil data 1 buku berdasarkan ID
    public function getIdBuku($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM tbl_buku WHERE id_buku = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    // Tambah data buku beserta upload gambarnya
    // NOTE: Validasi file gambar (mime, size) sebaiknya dilakukan di proses_tambah.php sebelum fungsi ini dipanggil
    public function createBuku($judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp)
    {
        $stmt = $this->conn->prepare('INSERT INTO tbl_buku (judul_buku, pengarang_buku, penerbit_buku, tahun, gambar) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar);
        $hasil = $stmt->execute();

        if ($hasil) {
            move_uploaded_file($img_tmp, '../gambar/' . $nama_baru_gambar);
        }
        return $hasil;
    }

    // Update data buku + Otomatis Hapus Gambar Lama jika diganti
    public function updateBuku($id, $judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp)
    {
        if (!empty($nama_baru_gambar)) {
            // 1. Ambil nama gambar lama dari database dulu sebelum di-update
            $bukuLama = $this->getIdBuku($id);
            $gambarLama = $bukuLama->gambar;

            $stmt = $this->conn->prepare('UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, tahun=?, gambar=? WHERE id_buku=?');
            $stmt->bind_param('sssssi', $judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $id);
            $hasil = $stmt->execute();

            if ($hasil) {
                // 2. Pindahkan gambar baru
                move_uploaded_file($img_tmp, '../gambar/' . $nama_baru_gambar);

                // 3. Hapus gambar lama dari server (jika filenya ada)
                if (!empty($gambarLama) && file_exists('../gambar/' . $gambarLama)) {
                    unlink('../gambar/' . $gambarLama);
                }
            }
            return $hasil;
        } else {
            $stmt = $this->conn->prepare('UPDATE tbl_buku SET judul_buku=?, pengarang_buku=?, penerbit_buku=?, tahun=? WHERE id_buku=?');
            $stmt->bind_param('ssssi', $judul, $pengarang, $penerbit, $tahun, $id);
            return $stmt->execute();
        }
    }

    // Hapus data buku + Otomatis Hapus File Gambar dari Server
    public function deleteBuku($id)
    {
        // 1. Ambil data gambar yang mau dihapus
        $buku = $this->getIdBuku($id);
        if ($buku) {
            $gambar = $buku->gambar;

            // 2. Jalankan query delete
            $stmt = $this->conn->prepare('DELETE FROM tbl_buku WHERE id_buku = ?');
            $stmt->bind_param('i', $id);
            $hasil = $stmt->execute();

            // 3. Jika query berhasil, hapus file gambarnya dari folder
            if ($hasil && !empty($gambar) && file_exists('../gambar/' . $gambar)) {
                unlink('../gambar/' . $gambar);
            }
            return $hasil;
        }
        return false;
    }
}
?>
