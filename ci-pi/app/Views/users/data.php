<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Management User
<?= $this->endSection('judul') ?>

<?= $this->section('subjudul') ?>
<button type="button" class="btn btn-primary btntambah">
    <i class="fa fa-plus-circle"></i> Tambah User Baru
</button>

<?= $this->endSection('subjudul') ?>

<?= $this->section('isi') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- DataTables  & Plugins -->
<script src="<?= base_url() ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<table style="width: 100%;" id="datauser" class="table table-bordered table-hover dataTable dtr-inline collapsed">
    <thead>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">ID User</th>
            <th style="text-align: center;">Nama User</th>
            <th style="text-align: center;">Level</th>
            <th style="text-align: center;">Status</th>
            <th style="text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<script>
    $(document).ready(function () {
        $('.btntambah').click(function (e) { 
            e.preventDefault();
            $.ajax({
                url: "/users/formtambah",
                success: function (response) {
                    $('.viewmodal').html(response).show();
                    $('#modaltambah').on('shown.bs.modal', function (event) {
                        $('#iduser').focus();
                    })
                    $('#modaltambah').modal('show');
                }
            });
        });
        dataUser = $('#datauser').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/users/listData',
            order: [],
            columns: [{
                data: 'nomor',
                width: 10
            },
            {
                data: 'userid'
            },
            {
                data: 'usernama'
            },
            {
                data: 'levelnama'
            },
            {
                data: 'status',
                orderable: false,
                width: 30
            },
            {
                data: 'aksi',
                orderable: false,
                width: 30
            },
            ]
        });
    });

    function view(userid){
        $.ajax({
            type: "post",
            url: "/users/formedit",
            data: {
                userid : userid
            },
            success: function (response) {
                $('.viewmodal').html(response).show();
                $('#modaledit').on('shown.bs.modal', function (event) {
                    $('#namalengkap').focus();
                })
                $('#modaledit').modal('show');
            }
        });
    }
</script>
<?= $this->endSection('isi') ?>