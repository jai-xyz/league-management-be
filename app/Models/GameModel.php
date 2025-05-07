<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameModel extends Model
{
    use HasFactory;
    protected $table = 'games';
    protected $primaryKey = 'game_id';

    public $fillable = [
        'home_team_id',
        'away_team_id',
        'game_date',
        'game_time',
        'location',
        'status',
        'home_team_final_score',
        'away_team_final_score'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(TeamModel::class, 'home_team_id', 'team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(TeamModel::class, 'away_team_id', 'team_id');
    }
}
