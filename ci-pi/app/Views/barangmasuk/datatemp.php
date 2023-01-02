<table class="table table-sm table-bordered table-hover">
    <thead>
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Kode Barang</th>
            <th style="text-align: center;">Nama Barang</th>
            <th style="text-align: center;">Harga Jual</th>
            <th style="text-align: center;">Harga Beli</th>
            <th style="text-align: center;">Jumlah</th>
            <th style="text-align: center;">Sub Total</th>
            <th style="text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datatemp->getResultArray() as $row) :
        ?>
        <tr>
            <td style="text-align: center;"><?= $nomor++; ?></td>
            <td style="text-align: center;"><?= $row['brgkode']; ?></td>
            <td style="text-align: center;"><?= $row['brgnama']; ?></td>
            <td style="text-align: center;">
            <?= number_format($row['dethargajual'], 0, ",", ".") ?>
            </td>
            <td style="text-align: center;">
            <?= number_format($row['dethargamasuk'], 0, ",", ".") ?>
            </td>
            <td style="text-align: center;">
            <?= number_format($row['detjumlah'], 0, ",", ".") ?>
            </td>
            <td style="text-align: center;">
            <?= number_format($row['detsubtotal'], 0, ",", ".") ?>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $row['iddetail'] ?>')">
                <i class="fa fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function hapusItem(id){
    Swal.fire({
        title: 'Hapus Item',
        text: "Yakin Ingin Menghapus Item Ini?",
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
                url: "/barangmasuk/hapus",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (response) {
                    if(response.sukses){
                        dataTemp();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.sukses,                        
                        });
                    }
                },
                error : function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    })
}
</script>