<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TeamModel extends Model
{
    use HasFactory;
    protected $table = 'teams';
    protected $primaryKey = 'team_id';

    protected $fillable = [
        'name',
        'division_id',
        'alias',
        'logo',
    ];

    public function players()
    {
        return $this->hasMany(PlayerModel::class, 'team_id', 'team_id');
    }
    public function division()
    {
        return $this->belongsTo(DivisionModel::class, 'division_id', 'division_id');
    }
}
