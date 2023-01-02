<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Form Tambah Barang
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-warning" onclick="location.href=('/barang/index')">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>
<?= form_open_multipart('barang/simpandata') ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>
<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Kode Barang</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="kodebarang" name="kodebarang" autofocus>
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Nama Barang</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="namabarang" name="namabarang">
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Pilih Kategori</label>
    <div class="col-sm-4">
        <select name="kategori" id="kategori" class="form-control">
            <option selected value="">Silahkan Pilih Kategori</option>
            <?php foreach($datakategori as $kat): ?>
                <option value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Pilih Satuan</label>
    <div class="col-sm-4">
        <select name="satuan" id="satuan" class="form-control">
            <option selected value="">Silahkan Pilih Satuan</option>
            <?php foreach($datasatuan as $sat): ?>
                <option value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Harga Barang</label>
    <div class="col-sm-4">
      <input type="number" class="form-control" id="harga" name="harga">
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Stok Barang</label>
    <div class="col-sm-4">
      <input type="number" class="form-control" id="stok" name="stok">
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label">Upload Gambar</label>
    <div class="col-sm-4">
      <input type="file" id="gambar" name="gambar">
    </div>
</div>

<div class="form-group row">
    <label for="" class="col-sm-3 col-form-label"></label>
    <div class="col-sm-4">
      <input type="submit" value="Simpan" class="btn btn-success">
    </div>
</div>
<?= form_close(); ?>

<?= $this->endSection('isi') ?>