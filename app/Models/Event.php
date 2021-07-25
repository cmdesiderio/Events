<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Event extends Model
{
	use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_name',
        'frequency',
        'start_date',
        'end_date',
        'duration'
    ];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'duration' => 'integer',
	];

	/**
     * Get invitees for the event.
     */
    public function invite()
    {
        return $this->hasMany(Invite::class);
    }

	/**
     * Get events by filter.
     */
	public function getByFilter(object $params): array
	{
		$sql = "SELECT
					e.id,
					e.event_name,
					e.frequency,
					e.start_date,
					e.end_date,
					e.duration
				FROM events e
				LEFT JOIN event_invitees ei
					ON e.id = ei.event_id
				WHERE 1 = 1
				AND e.duration is not null
				AND (
						(
						frequency != 'Once-Off' 
						AND (
								(e.start_date BETWEEN :startDateTime AND :endDateTime) 
								OR (e.end_date BETWEEN :startDateTime2  AND :endDateTime2) 
								OR (e.start_date <= :startDateTime3  AND (e.end_date >= :endDateTime3 OR end_date IS NULL))
							)
						)
				OR (
						frequency = 'Once-Off' 
						AND e.start_date BETWEEN :startDateTime4 AND :endDateTime4
					)
				)";
		
		if ($params->invitees) {
			$sql .= " AND ei.user_id in (". $params->invitees .") GROUP BY e.id";
		} else {
			$sql .= " GROUP BY e.id";
		}
				
		$params = [
            'startDateTime'  => $params->from,
            'endDateTime'    => $params->to,
            'startDateTime2' => $params->from,
            'endDateTime2'   => $params->to,
            'startDateTime3' => $params->from,
            'endDateTime3'   => $params->to,
            'startDateTime4' => $params->from,
            'endDateTime4'   => $params->to,
        ];

        return DB::select($sql, $params);
	}
}
