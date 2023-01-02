<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Manajemen Data Satuan
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Satuan', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('satuan/formtambah') . "')"
]) ?>

<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>
<?= session()->getFlashdata('sukses'); ?>
<?= form_open('satuan/index') ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari Data Satuan" aria-label="Recipient's username" 
  aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>" autofocus>
  <div class="input-group-append">
    <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
  </div>
</div>
<?= form_close(); ?>
<table class="table table-sm table-bordered table-hover" style="width:100%;">
    <thread>
        <tr>
            <th style="width: 5%; text-align: center;">No</th>
            <th style="text-align: center;">Nama Satuan</th>
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
                <td style="text-align: center;"><?= $row['satnama']; ?></td>
                <td style="text-align: center;">
                    <button type="button" class="btn btn-sm btn-primary" title="Edit Data" onclick="edit('<?= $row['satid'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;

                    <form method="POST" action="/satuan/hapus/<?= $row['satid'] ?>" style="display:inline;" onsubmit="hapus();">
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
    <?= $pager->links('satuan', 'paging'); ?>
</div>

<script>
    function edit(id) {
        window.location = ('/satuan/formedit/' + id);
    }

    function hapus(id){
        pesan = confirm('Yakin Data Satuan Dihapus?');

        if (pesan){
            window.location = ('/satuan/hapus/' + id);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi') ?>