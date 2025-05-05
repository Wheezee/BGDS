<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'place_of_birth',
        'date_of_birth',
        'age',
        'sex',
        'civil_status',
        'citizenship',
        'occupation',
        'labor_status',
        'contact_number',
        'email',
        'education',
        'mother_name',
        'father_name',
        'philsys_number',
        'household_id',
        'program_participation',
        'family_group',
        'blood_type',
        'height',
        'weight',
        'skin_complexion',
        'voter',
        'resident_voter',
        'year_last_voted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'age' => 'integer',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'year_last_voted' => 'integer',
    ];

    /**
     * Get the resident's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        
        $name .= ' ' . $this->last_name;
        
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        
        return $name;
    }
} 