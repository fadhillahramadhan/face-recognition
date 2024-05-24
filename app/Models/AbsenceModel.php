<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenceModel extends Model
{
    protected $table            = 'absence';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields  = false;
}
