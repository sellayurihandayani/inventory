<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeluser extends Model
{

    protected $table            = 'users';
    protected $primaryKey       = 'userid';
    protected $allowedFields    = [
        'userid', 'usernama', 'userpassword', 'useraktif', 'userlevelid'
    ];
}
