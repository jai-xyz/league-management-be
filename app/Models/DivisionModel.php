<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class DivisionModel extends Model
{
    use HasFactory;
    protected $table = 'divisions';
    protected $primaryKey = 'division_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
    ];

    public function teams()
    {
        return $this->hasMany(TeamModel::class, 'division_id', 'division_id');
    }
}
