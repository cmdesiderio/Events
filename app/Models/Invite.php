<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
	use HasFactory;
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_invitees';

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'user_id'
    ];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'event_id' => 'integer',
		'user_id'  => 'integer'
	];

	public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
