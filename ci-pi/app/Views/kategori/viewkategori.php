<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Kategori
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Kategori', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('kategori/formtambah') . "')"
]) ?>

<?= $this->endSection('subjudul') ?>


<?= $this->section('isi') ?>

<?= session()->getFlashdata('sukses'); ?>
<?= form_open('kategori/index') ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari Data Kategori" aria-label="Recipient's username" 
  aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>" autofocus>
  <div class="input-group-append">
    <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
  </div>
</div>
<?= form_close(); ?>

<table class="table table-sm table-bordered table-hover"  style="width:100%;">
    <thread>
        <tr>
            <th style="text-align: center;" style="width: 5%;">No</th>
            <th style="text-align: center;">Nama Kategori</th>
            <th style="text-align: center;" style="width: 15%;">Aksi</th>
        </tr>
    </thread>

    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
        ?>

            <tr>
                <td style="text-align: center;"><?= $nomor++; ?></td>
                <td style="text-align: center;"><?= $row['katnama']; ?></td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-primary" title="Edit Data" onclick="edit('<?= $row['katid'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;

                    <form method="POST" action="/kategori/hapus/<?= $row['katid'] ?>" style="display:inline;" onsubmit="hapus();">
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
    <?= $pager->links('kategori', 'paging'); ?>
</div>

<script>
    function edit(id) {
        window.location = ('/kategori/formedit/' + id);
    }

    function hapus(id){
        pesan = confirm('Yakin Data Kategori Dihapus?');

        if (pesan){
            window.location = ('/kategori/hapus/' + id);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi') ?>