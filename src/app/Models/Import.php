<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'imports';

    protected $fillable = [
        'user_id',
        'admin_id',
        'name',
        'path',
        'mime',
        'group_id',
        'type',
        'contact_structure'
    ];

    /**
     * Mutator for the contact_structure attribute.
     *
     * @param mixed $value
     */
    public function setContactStructureAttribute($value)
    {
        $this->attributes['contact_structure'] = serialize($value);
    }

    /**
     * Accessor for the contact_structure attribute.
     *
     * @return mixed
     */
    public function getContactStructureAttribute($value)
    {
        return unserialize($value);
    }
}
