<table class="table table-sm table-hover table-bordered" style="width: 100%;">
<thead>
    <tr>
        <th colspan="5"></th>
        <th colspan="2" style="text-align: right;">
            <?php 
            $totalHarga = 0;
            foreach ($tampildata->getResultArray() as $row) : 
                $totalHarga += $row['detsubtotal'];
            endforeach;
            ?>
            <h1><?= number_format($totalHarga, 0, ",", ".") ?></h1>
            <input type="hidden" id="totalharga" value="<?= $totalHarga; ?>">
        </th>
    </tr>
</thead>
<thead>
    <tr>
        <th style="text-align: center;">No</th>
        <th style="text-align: center;">Kode Barang</th>
        <th style="text-align: center;">Nama Barang</th>
        <th style="text-align: center;">Harga Jual</th>
        <th style="text-align: center;">Jumlah</th>
        <th style="text-align: center;">Sub Total</th>
        <th style="text-align: center;">Aksi</th>
    </tr>
</thead>
<tbody>
    <?php
    $nomor = 1;
    foreach ($tampildata->getResultArray() as $row) :
    ?>
    <tr>
        <td style="text-align: center;"><?= $nomor++; ?></td>
        <td style="text-align: center;"><?= $row['detbrgkode'] ?></td>
        <td style="text-align: center;"><?= $row['brgnama'] ?></td>
        <td style="text-align: center;">
        <?= number_format($row['dethargajual'], 0, ",", ".") ?>
        </td>
        <td style="text-align: center;">
        <?= number_format($row['detjumlah'], 0, ",", ".") ?>
        </td>
        <td style="text-align: center;">
        <?= number_format($row['detsubtotal'], 0, ",", ".") ?>
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $row['id'] ?>')">
                <i class="fa fa-trash-alt"></i>
            </button>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</tbody>
</table>
<script>
    function hapusItem(id){
        Swal.fire({
            title: 'Hapus Item',
            text: "Apakah yakin ingin menghapus item ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/barangkeluar/hapusItem",
                    data: {
                        id : id
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.sukses){
                            Swal.fire('berhasil', response.sukses, 'success');
                            tampilDataTemp();
                            kosong();
                        }
                    }
                });
            }
        })
    }
</script>