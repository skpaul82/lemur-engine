<?php

namespace App\Models;

use App\Classes\LemurStr;
use App\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Turn",
 *      required={"conversation_id", "slug", "input", "output", "status", "source", "is_display"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="conversation_id",
 *          description="conversation_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="category_id",
 *          description="category_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="input",
 *          description="input",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="output",
 *          description="output",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="source",
 *          description="source",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_display",
 *          description="is_display",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Turn extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;

    public $table = 'turns';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'conversation_id',
        'client_category_id',
        'category_id',
        'parent_turn_id',
        'slug',
        'input',
        'output',
        'status',
        'source',
        'is_display',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'conversation_id' => 'integer',
        'category_id' => 'integer',
        'client_category_id' => 'integer',
        'parent_turn_id'=> 'integer',
        'slug' => 'string',
        'input' => 'string',
        'output' => 'string',
        'status' => 'string',
        'source' => 'string',
        'is_display' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'conversation_id' => 'required',
        'category_id' => 'nullable',
        'client_category_id' => 'nullable',
        'input' => 'required|string|max:255',
        'output' => 'required|string',
        'status' => 'string|max:1',
        'source' => 'required|string|max:255',
        'is_display' => 'required|boolean',
    ];

    /**
     * Get the validation rules
     *
     * return array
     */
    public function getRules()
    {
        return self::$rules;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class, 'conversation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function emptyResponses()
    {
        return $this->hasMany(\App\Models\EmptyResponse::class, 'turn_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Turn::select([$this->table.'.*','users.email','conversations.slug as conversation',
                                'bots.slug as bot','clients.slug as client'])
                ->leftJoin('conversations', 'conversations.id', '=', $this->table.'.conversation_id')
                ->leftJoin('bots', 'bots.id', '=', 'conversations.bot_id')
                ->leftJoin('clients', 'clients.id', '=', 'conversations.client_id')
                ->leftJoin('users', 'users.id', '=', 'bots.user_id');
    }


    public static function totalInDays($days)
    {

        return Turn::whereDate('created_at', '>', Carbon::now()->subDays($days))
            ->count();
    }

    public function scopeHuman($query)
    {
        return $query->where('source', 'human');
    }

    public function scopeOpenRecursiveTags($query)
    {
        return $query->where('source', 'srai')->where('status', 'O');
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('input')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
