<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CastMember
 *
 * @property string $id
 * @property string $name
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CastMember onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CastMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CastMember withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CastMember withoutTrashed()
 * @mixin \Eloquent
 */
class CastMember extends Model
{
    use SoftDeletes, \App\Models\Traits\Uuid;
    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;
    protected $fillable = [
        'name',
        'type'
    ];
    protected $casts = [
        'id' => 'string',
        'type' => 'integer'
    ];
    protected $dates = ['deleted_at'];
    public $incrementing = false;
}
