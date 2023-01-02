<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Cetak Laporan
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>
Silahkan Pilih Laporan Yang Ingin Dicetak
<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>

<div class="row">
    <div class="col-lg-6">
        <button type="button" style="padding-top: 20px; padding-bottom: 20px;" class="btn btn-block btn-lg btn-primary" onclick="window.location=('/laporan/cetak-barang-masuk')">
            <i class="fa fa-file"></i> Laporan Barang Masuk
        </button>&nbsp;
    </div>
    <div class="col-lg-6">
        <button type="button" style="padding-top: 20px; padding-bottom: 20px;" class="btn btn-block btn-lg btn-primary" onclick="window.location=('/laporan/cetak-barang-keluar')">
            <i class="fa fa-file"></i> Laporan Barang Keluar
        </button>&nbsp;
    </div>
</div>

<?= $this->endSection('isi') ?>