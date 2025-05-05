<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'description'
    ];

    /**
     * Get the meeting that owns the attachment.
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedSizeAttribute()
    {
        if ($this->file_size < 1024) {
            return $this->file_size . ' bytes';
        } elseif ($this->file_size < 1048576) {
            return round($this->file_size / 1024, 2) . ' KB';
        } elseif ($this->file_size < 1073741824) {
            return round($this->file_size / 1048576, 2) . ' MB';
        } else {
            return round($this->file_size / 1073741824, 2) . ' GB';
        }
    }
    
    /**
     * Get a user-friendly file type.
     */
    public function getFileTypeDisplayAttribute()
    {
        $extension = pathinfo($this->original_filename, PATHINFO_EXTENSION);
        
        $types = [
            // Documents
            'pdf' => 'PDF Document',
            'doc' => 'Word Document',
            'docx' => 'Word Document',
            'xls' => 'Excel Spreadsheet',
            'xlsx' => 'Excel Spreadsheet',
            'ppt' => 'PowerPoint Presentation',
            'pptx' => 'PowerPoint Presentation',
            'txt' => 'Text Document',
            
            // Images
            'jpg' => 'JPEG Image',
            'jpeg' => 'JPEG Image',
            'png' => 'PNG Image',
            'gif' => 'GIF Image',
            'bmp' => 'BMP Image',
            
            // Other common formats
            'zip' => 'ZIP Archive',
            'rar' => 'RAR Archive',
            'csv' => 'CSV File',
        ];
        
        $extension = strtolower($extension);
        
        return $types[$extension] ?? 'File';
    }
}
