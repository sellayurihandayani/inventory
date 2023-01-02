<div class="modal fade" id="modalitem" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Detail Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Kode Barang</th>
                    <th style="text-align: center;">Nama Barang</th>
                    <th style="text-align: center;">Harga Masuk</th>
                    <th style="text-align: center;">Harga Jual</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                foreach ($tampildatadetail->getResultArray() as $row) :
                ?>
                <tr>
                    <td style="text-align: center;"><?= $nomor++; ?></td>
                    <td style="text-align: center;"><?= $row['detbrgkode']; ?></td>
                    <td style="text-align: center;"><?= $row['brgnama']; ?></td>
                    <td style="text-align: center;">
                    <?= number_format($row['dethargamasuk'], 0, ",", ".") ?>
                    </td>
                    <td style="text-align: center;">
                    <?= number_format($row['dethargajual'], 0, ",", ".") ?>
                    </td>
                    <td style="text-align: center;">
                    <?= number_format($row['detjumlah'], 0, ",", ".") ?>
                    </td>
                    <td style="text-align: center;">
                    <?= number_format($row['detsubtotal'], 0, ",", ".") ?>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>