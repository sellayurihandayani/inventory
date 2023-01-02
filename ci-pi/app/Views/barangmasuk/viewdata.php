<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Data Transaksi Barang Masuk
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-primary" onclick="location.href=('/barangmasuk/index')">
    <i class="fa fa-plus-circle"></i> Tambah Barang Masuk
</button>

<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>
<?= form_open('barangmasuk/data') ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari Berdasarkan Nomor Faktur" name="cari" value="<?= $cari ?>" autofocus="true">
  <div class="input-group-append">
    <button class="btn btn-outline-primary" type="submit" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
  </div>
</div>
<?= form_close(); ?>
<?= "<span class=\"badge badge-primary\">Total Data : $totaldata</span>" ?>
<br>

<table class="table table-sm table-bordered table-hover">&nbsp;
    <thead>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Faktur</th>
            <th style="text-align: center;">Tanggal</th>
            <th style="text-align: center;">Jumlah Item</th>
            <th style="text-align: center;">Total Harga</th>
            <th style="text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 10);
        foreach($tampildata as $row):
        ?>
            <tr>
                <td style="text-align: center;"><?= $nomor++; ?></td>
                <td style="text-align: center;"><?= $row['faktur']; ?></td>
                <td style="text-align: center;"><?= date('d-m-Y', strtotime($row['tglfaktur'])); ?></td>
                <td align="center">
                    <?php
                    $db = \Config\Database::connect();

                    $jumlahItem = $db->table('detail_barangmasuk')->where('detfaktur', $row['faktur'])->countAllResults();
                    ?>

                    <span style="cursor:pointer; font-weight: bold; color:cadetblue;" onclick="detailItem('<?= $row['faktur'] ?>')"><?= $jumlahItem; ?></span>
                </td>
                <td style="text-align: center;">
                    <?= number_format($row['totalharga'],0,",",".") ?>
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-primary" title="Edit Transaksi" onclick="edit('<?= sha1($row['faktur']) ?>')">
                        <i class="fa fa-edit"></i>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-danger" title="Hapus Transaksi" onclick="hapusTransaksi('<?= $row['faktur'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<div class="float-left mt-4">
    <?= $pager->links('barangmasuk','paging')?>
</div>
<script>
    function hapusTransaksi(faktur){
        Swal.fire({
            title: 'Hapus Transaksi',
            text: "Yakin Ingin Menghapus Transaksi Ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                type: "post",
                url: "/barangmasuk/hapusTransaksi",
                data: {
                    faktur: faktur
                },
                dataType: "json",
                success: function (response) {
                    if(response.sukses){
                        Swal.fire({
                            icon : "success",
                            title : "Berhasil",
                            text : response.sukses
                        }).then((result) => {
                            window.location.reload();
                        })
                    }
                },
                error : function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + '\n' + thrownError);
                }
                });
            }
        })
    }

    function edit(faktur){
        window.location.href=('/barangmasuk/edit/') + faktur;
    }

    function detailItem(faktur){
        $.ajax({
            type: "post",
            url: "/barangmasuk/detailItem",
            data: {
                faktur : faktur
            },
            dataType: "json",
            success: function (response) {
                if(response.data){
                    $('.viewmodal').html(response.data).show();
                    $('#modalitem').modal('show');
                }
                
            },
            error : function(xhr,ajaxOptions,thrownError){
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }
</script>
<?= $this->endSection('isi') ?>