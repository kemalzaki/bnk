<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';

check_login();

$conn = get_db_connection();
$pusat_rehab = get_all_pusat_rehabilitasi($conn);
$pasien_rehab = get_all_pasien_rehabilitasi($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Rehabilitasi</h2>
    <div>
        <a href="create_pusat.php" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Tambah Pusat Rehab</a>
        <a href="register_pasien.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Pendaftaran Pasien</a>
    </div>
</div>

<?php display_flash_message(); ?>

<ul class="nav nav-tabs mb-4" id="rehabTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pasien-tab" data-bs-toggle="tab" data-bs-target="#pasien" type="button" role="tab" aria-controls="pasien" aria-selected="true">Data Pasien Rehabilitasi</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pusat-tab" data-bs-toggle="tab" data-bs-target="#pusat" type="button" role="tab" aria-controls="pusat" aria-selected="false">Data Pusat Rehabilitasi</button>
  </li>
</ul>

<div class="tab-content" id="rehabTabContent">
  
  <!-- Tab Pasien -->
  <div class="tab-pane fade show active" id="pasien" role="tabpanel" aria-labelledby="pasien-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Pasien</th>
                            <th>Pusat Rehab</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($pasien_rehab) > 0):
                            while ($row = mysqli_fetch_assoc($pasien_rehab)): 
                                $badge_class = 'bg-secondary';
                                if ($row['status_rehabilitasi'] == 'aktif') $badge_class = 'bg-primary';
                                else if ($row['status_rehabilitasi'] == 'selesai') $badge_class = 'bg-success';
                                else if ($row['status_rehabilitasi'] == 'drop-out') $badge_class = 'bg-danger';
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong> <br><small class="text-muted"><?php echo htmlspecialchars($row['nik']); ?></small></td>
                            <td><?php echo htmlspecialchars($row['nama_pusat']); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['status_rehabilitasi']); ?></span></td>
                            <td><?php echo format_date($row['created_at']); ?></td>
                            <td class="text-center">
                                <a href="update_status.php?id=<?php echo $row['id_rehabilitasi']; ?>" class="btn btn-sm btn-info text-white" title="Update Status"><i class="fas fa-edit"></i> Update</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pasien rehabilitasi.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

  <!-- Tab Pusat Rehab -->
  <div class="tab-pane fade" id="pusat" role="tabpanel" aria-labelledby="pusat-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Pusat Rehabilitasi</th>
                            <th>Alamat</th>
                            <th>Kapasitas Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($pusat_rehab) > 0):
                            while ($row = mysqli_fetch_assoc($pusat_rehab)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_pusat']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td><?php echo $row['kapasitas']; ?> Orang</td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pusat rehabilitasi.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

</div>

<?php
mysqli_close($conn);
include APP_PATH . '/templates/footer.php';
?>
