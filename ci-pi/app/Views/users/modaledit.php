<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">View Data User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open('users/update', ['class' => 'frmsimpan']) ?>
      <div class="modal-body">
        <div class="form-group">
            <label for="">ID User</label>
            <input type="text" name="iduser" id="iduser" class="form-control form-control-sm" autocomplete="off" value="<?= $iduser; ?>" readonly="true">
        </div>
        <div class="form-group">
            <label for="">Nama Lengkap</label>
            <input type="text" name="namalengkap" id="namalengkap" class="form-control form-control-sm" autocomplete="off" value="<?= $namalengkap; ?>">
        </div>
        <div class="form-group">
            <label for="">Level User</label>
            <select name="level" id="level" class="form-control form-control-sm">
                <?php foreach($datalevel->getResultArray() as $x): ?>
                    <?php if($level == $x['levelid']): ?>
                        <option selected value="<?= $x['levelid'] ?>"><?= $x['levelnama'] ?></option>

                        <?php else: ?>
                            <option value="<?= $x['levelid'] ?>"><?= $x['levelnama'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="">Status User</label>
            <input type="checkbox" <?= ($status=='1') ? 'checked' : ''; ?> data-toggle="toggle" data-on="Aktif" data-off="Tidak Aktif" data-onstyle="success" data-offstyle="danger" data-width="150" data-size="xs" class="chStatus">
        </div>
        <div class="form-group viewResetPassword" style="display: none;">
            <label for="">Password Baru</label>
            <br>
            <h3 class="passReset"></h3>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-warning btnreset">Reset Password</button>
        <button type="button" class="btn btn-danger btnhapus">Hapus</button>
        <button type="submit" class="btn btn-success btnsimpan">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>
<script>
    $(document).ready(function () {
        $('.btnreset').click(function (e) { 
            e.preventDefault();
            let iduser = $('#iduser').val();
            Swal.fire({
                title: 'Reset Password',
                html: `Yakin iduser <strong>${iduser}</strong> ingin di Riset Password ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/users/resetPassword",
                        data: {iduser : iduser},
                        dataType: "json",
                        success: function (response) {
                            if(response.sukses == ''){
                                $('.viewResetPassword').show();
                                $('.passReset').html(response.passwordBaru);
                            }
                        }
                    });
                }
            })

        });
        $('.btnhapus').click(function (e) { 
            e.preventDefault();
            let iduser = $('#iduser').val();
            Swal.fire({
                title: 'Hapus User',
                html: `Yakin iduser <strong>${iduser}</strong> ini dihapus ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/users/hapus",
                        data: {iduser : iduser},
                        dataType: "json",
                        success: function (response) {
                            if(response.sukses){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.sukses,
                                });
                                dataUser.ajax.reload();
                            }
                        }
                    });
                }
            })

        });
        $('.chStatus').change(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/users/updateStatus",
                data: {
                    iduser : $('#iduser').val()
                },
                dataType: "json",
                success: function (response) {
                    if(response.sukses == ''){
                        dataUser.ajax.reload();
                    }
                }
            });
        });
        $('.frmsimpan').submit(function (e) { 
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                cache: false,
                beforeSend: function(){
                    $('.btnsimpan').prop('disabled', true);
                    $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function(){
                    $('.btnsimpan').prop('disabled', false);
                    $('.btnsimpan').html('Update');
                },
                success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'berhasil',
                            text: response.sukses
                        });
                        $('#modaledit').modal('hide');
                        dataUser.ajax.reload();
                },
                error : function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + '\n' + thrownError);
                }
            });
            return false;
        });
    });
</script>