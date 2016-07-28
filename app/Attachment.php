<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Storage;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id', 'file_name', 'original_name', 'mime_type', 'article_id',
    ];

    public function toArray($options = 0)
    {
        $data = parent::toArray($options);
        $data['url'] = Storage::url($this->file_name);
        return $data;
    }
}
