<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'duration_minutes',
        'agenda',
        'venue',
        'organizer',
        'attendees',
        'absentees',
        'transcription'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'attendees' => 'array',
        'absentees' => 'array'
    ];

    public function getDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%d hour%s %d minute%s', 
                $hours, $hours > 1 ? 's' : '',
                $minutes, $minutes > 1 ? 's' : ''
            );
        }

        return sprintf('%d minute%s', $minutes, $minutes > 1 ? 's' : '');
    }
} 