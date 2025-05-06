<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerModel extends Model
{
    use HasFactory;
    protected $table = 'players';
    protected $primaryKey = 'player_id';

    protected $fillable = [
        'team_id',
        'first_name',
        'middle_name',
        'last_name',
        'nickname',
        'age',
        'height',
        'weight',
        'position',
        'jersey_number'
    ];

    public function team()
    {
        return $this->belongsTo(TeamModel::class, 'team_id', 'team_id');
    }
}
