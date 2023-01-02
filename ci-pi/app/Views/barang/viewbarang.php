<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Barang
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-primary" onclick="location.href=('/barang/tambah')">
    <i class="fa fa-plus-circle"></i> Tambah Barang
</button>

<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>
<?= session()->getFlashdata('error') ?>
<?= session()->getFlashdata('sukses') ?>
<?= form_open('barang/index') ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari Data Berdasarkan Kode, Nama & Kategori" name="cari" autofocus value="<?= $cari ?>">
  <div class="input-group-append">
    <button class="btn btn-outline-primary" type="submit" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
  </div>
</div>
<?= form_close(); ?>
<?= "<span class=\"badge badge-primary\">Total Data : $totaldata</span>" ?>
<br>

<table class="table table-sm table-bordered table-hover" style="width:100%;">&nbsp;
    <thread>
        <tr>
            <th style="width: 5%; text-align: center;">No</th>
            <th style="text-align: center;">Kode Barang</th>
            <th style="text-align: center;">Nama Barang</th>
            <th style="text-align: center;">Kategori</th>
            <th style="text-align: center;">Satuan</th>
            <th style="text-align: center;">Harga</th>
            <th style="text-align: center;">Stok</th>
            <th style="width: 15%; text-align: center;">Aksi</th>
        </tr>
    </thread>

    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
        ?>

            <tr>
                <td style="text-align: center;"><?= $nomor++; ?></td>
                <td style="text-align: center;"><?= $row['brgkode']; ?></td>
                <td style="text-align: center;"><?= $row['brgnama']; ?></td>
                <td style="text-align: center;"><?= $row['katnama']; ?></td>
                <td style="text-align: center;"><?= $row['satnama']; ?></td>
                <td style="text-align: center;">
                <?= number_format($row['brgharga'], 0, ",", ".") ?>
                </td>
                <td style="text-align: center;"><?= number_format($row['brgstok'], 0); ?></td>
                <td style="text-align: center;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="edit('<?= $row['brgkode'] ?>')">
                            <i class="fa fa-edit"></i>
                        </button>&nbsp;

                    <form method="POST" action="/barang/hapus/<?= $row['brgkode'] ?>" style="display:inline;" onsubmit="return hapus();">
                        <input type="hidden" value="DELETE" name="_methode">

                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                            <i class="fa fa-trash-alt"></i>
                        </button>&nbsp;
                    </form>

                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>
<div class="float-left mt-4">
    <?= $pager->links('barang','paging')?>
</div>
<script>
    function edit(kode){
        window.location.href=('/barang/edit/' + kode);
    }
    
    function hapus(kode){
    pesan = confirm('Yakin Data Barang Ingin Dihapus?');
    if(pesan){
        return true;
    }else{
        return false;
    }
}
</script>

<?= $this->endSection('isi') ?>