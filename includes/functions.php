<?php
/**
 * Praktikum 4: Implementasi Aplikasi Web Dinamis Menggunakan PHP
 * File: includes/functions.php
 * Kumpulan fungsi modular untuk pengelolaan data mahasiswa di server.
 */

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Inisialisasi data mahasiswa pada session jika belum ada
 */
function init_mahasiswa_session() {
    
}

/**
 * Sanitasi string input untuk keamanan XSS
 */
function sanitize($data) {
   
}

/**
 * Mengambil semua data mahasiswa dengan opsi filter pencarian
 */
function get_all_mahasiswa($keyword = '') {
    init_mahasiswa_session();
    $data = $_SESSION['daftar_mahasiswa'];

    if (empty($keyword)) {
        return $data;
    }

    $keyword = strtolower(trim($keyword));
    $filtered = [];

    foreach ($data as $mhs) {
        if (
            str_contains(strtolower($mhs['npm']), $keyword) || 
            str_contains(strtolower($mhs['nama']), $keyword) ||
            str_contains(strtolower($mhs['program_studi']), $keyword)
        ) {
            $filtered[] = $mhs;
        }
    }

    return $filtered;
}

/**
 * Menghitung total data mahasiswa yang tersimpan
 */
function count_total_mahasiswa() {
    
}

/**
 * Menambahkan data mahasiswa baru ke dalam session dengan validasi server-side
 */
function tambah_mahasiswa($npm, $nama, $program_studi, $angkatan, $email) {
    init_mahasiswa_session();

    // Sanitasi data
    // Percobaan 4-3 Langkah 3
    

    // Validasi Server-Side
    if (empty($npm) || empty($nama) || empty($program_studi) || empty($angkatan) || empty($email)) {
        return [
            'status' => false,
            'message' => 'Semua kolom formulir wajib diisi!'
        ];
    }

    if (!preg_match('/^[0-9]{8,12}$/', $npm)) {
        return [
            'status' => false,
            'message' => 'npm harus berupa angka 8 hingga 12 digit!'
        ];
    }

    // Cek duplikasi npm
    foreach ($_SESSION['daftar_mahasiswa'] as $mhs) {
        if ($mhs['npm'] === $npm) {
            return [
                'status' => false,
                'message' => 'npm sudah terdaftar! Harap gunakan npm lain.'
            ];
        }
    }

    if (strlen($nama) < 3) {
        return [
            'status' => false,
            'message' => 'Nama lengkap minimal 3 karakter!'
        ];
    }

    if ($angkatan < 2018 || $angkatan > 2026) {
        return [
            'status' => false,
            'message' => 'Tahun angkatan harus berada antara 2018 hingga 2026!'
        ];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'status' => false,
            'message' => 'Format alamat email tidak valid!'
        ];
    }

    // Tambahkan data baru ke array session
    $_SESSION['daftar_mahasiswa'][] = [
        'npm' => $npm,
        'nama' => $nama,
        'program_studi' => $program_studi,
        'angkatan' => $angkatan,
        'email' => $email
    ];

    return [
        'status' => true,
        'message' => "Data mahasiswa {$nama} (npm: {$npm}) berhasil ditambahkan!"
    ];
}

/**
 * Menghapus data mahasiswa dari session berdasarkan npm
 */
function hapus_mahasiswa($npm) {
    init_mahasiswa_session();
    $npm = sanitize($npm);
    $found = false;
    $namaDihapus = '';

    foreach ($_SESSION['daftar_mahasiswa'] as $index => $mhs) {
        if ($mhs['npm'] === $npm) {
            $namaDihapus = $mhs['nama'];
            unset($_SESSION['daftar_mahasiswa'][$index]);
            // Re-index array
            $_SESSION['daftar_mahasiswa'] = array_values($_SESSION['daftar_mahasiswa']);
            $found = true;
            break;
        }
    }

    if ($found) {
        return [
            'status' => true,
            'message' => "Data mahasiswa {$namaDihapus} (npm: {$npm}) berhasil dihapus."
        ];
    }

    return [
        'status' => false,
        'message' => "Data mahasiswa dengan npm {$npm} tidak ditemukan."
    ];
}

/**
 * Set flash message untuk feedback notifikasi pengguna
 */


/**
 * Ambil dan hapus flash message
 */


