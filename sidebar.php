<aside class="sidebar" id="sidebar">
    <ul>
        <li><a href="dashboard_pegawai.php">Beranda</a></li>
        <li><a href="gabung_kelas.php">Gabung Kelas</a></li>
        <?php if ($_SESSION['role'] === 'pengajar'): ?>
            <li><a href="buat_kelas.php">Buat Kelas</a></li>
        <?php endif; ?>
        <li><a href="profile.php">Profil</a></li>
    </ul>
</aside>
