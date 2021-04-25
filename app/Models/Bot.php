<?php

namespace App\Models;

use App\Traits\ImageTrait;
use App\Traits\UiValidationTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;
use willvincent\Rateable\Rateable;

/**
 * @SWG\Definition(
 *      definition="BotRequest",
 *      required={"name", "summary", "description", "status"},
 *      @SWG\Property(
 *          property="language",
 *          description="language",
 *          type="string",
 *          default="en"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="summary",
 *          description="summary",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="default_response",
 *          description="default_response",
 *          type="string",
 *          default="I do not have a response for that"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string",
 *          default="testing",
 *          enum={"active", "hidden", "testing"}
 *      ),
 *      @SWG\Property(
 *          property="is_public",
 *          description="is_public",
 *          type="boolean",
 *          default=false
 *      )
 * )
 */


/**
 * @SWG\Definition(
 *      definition="BotResponse",
 *      required={"slug", "language", "name", "summary", "description", "default_response", "status"},
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="language",
 *          description="language",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="summary",
 *          description="summary",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="default_response",
 *          description="default_response",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_public",
 *          description="is_public",
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
 *      )
 * )
 */
class Bot extends Model
{
    use SoftDeletes;
    use ImageTrait;
    use UiValidationTrait;
    use HasSlug;
    use Rateable;

    use CascadeSoftDeletes;

    public $table = 'bots';

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['botCategoryGroups', 'botKeys','botProperties',
                                    'botWordSpellingGroups' ,'conversations','conversations'];


    public $fillable = [
        'slug',
        'language_id',
        'user_id',
        'name',
        'summary',
        'description',
        'default_response',
        'lemurtar_url',
        'image',
        'status',
        'is_public',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'language_id' => 'integer',
        'user_id' => 'integer',
        'name' => 'string',
        'summary' => 'string',
        'description' => 'string',
        'default_response' => 'string',
        'lemurtar_url' => 'string',
        'image' => 'string',
        'status' => 'string',
        'is_public' => 'boolean',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'language_id' => 'required',
        'name' => 'required|unique:bots,name|string|max:50',
        'summary' => 'required|string|max:75',
        'description' => 'required|string|max:255',
        'default_response' => 'required|string|max:255',
        'lemurtar_url' => 'nullable|string',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:max_width=256,max_height=256|max:512',
        'status' => 'required|in:A,T,H',
        'is_public' => 'boolean',
    ];


    /**
     * Add the user_id on create
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $loggedInUser = Auth::user();
            //set the user to the current logged in user
            $model->user_id = $loggedInUser->id;
            //always create with the default image.. we can update in a moment when we process the image
            $model->image = config('lemur.default_bot_image');
        });
    }

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
    public function language()
    {
        return $this->belongsTo(\App\Models\Language::class, 'language_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botCategoryGroups()
    {
        return $this->hasMany(\App\Models\BotCategoryGroup::class, 'bot_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botProperties()
    {
        return $this->hasMany(\App\Models\BotProperty::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botKeys()
    {
        return $this->hasMany(\App\Models\BotKey::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function botAvatar()
    {
        return $this->hasOne(\App\Models\BotAvatar::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botRatings()
    {
        return $this->hasMany(\App\Models\BotRating::class, 'bot_id');
    }

    /**
     **/
    public function botRatingAvg()
    {
        return $this->botRatings->avg('rating');
    }

    /**
     **/
    public function botRatingMin()
    {
        return $this->botRatings->min('rating');
    }

    /**
     **/
    public function botRatingMax()
    {
        return $this->botRatings->max('rating');
    }

    /**
     **/
    public function botRatingCount()
    {
        return $this->botRatings->count();
    }

    /**
     * @param $name
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function botProperty($name)
    {
        return $this->hasOne(\App\Models\BotProperty::class, 'bot_id')->where('name', $name);
    }

    /**
     * @param $name
     */
    public function botPropertyByName($name)
    {
        return $this->botProperties->where('name', $name)->first();
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botWordSpellingGroups()
    {
        return $this->hasMany(\App\Models\BotWordSpellingGroup::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function conversations()
    {
        return $this->hasMany(\App\Models\Conversation::class, 'bot_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function conversationTurns()
    {
        return $this->hasManyThrough(\App\Models\Turn::class, \App\Models\Conversation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function conversationTurnsLast28Days()
    {
        $date = Carbon::now()->subDays(28);
        return $this->hasManyThrough(\App\Models\Turn::class, \App\Models\Conversation::class)
            ->whereDate('turns.created_at', '>=', $date);
    }


    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->slug.')';
    }

    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Bot::select([$this->table.'.*','users.email','languages.name as language'])
                ->leftJoin('languages', 'languages.id', '=', $this->table.'.language_id')
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id')
                ->myEditableItems();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Scope a query a specific property.
     * Get the bots this user is allowed to read
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyReadableItems($query)
    {
        $thisLoggedInUser = Auth::user();
        if ($thisLoggedInUser->hasRole('admin')) {
            //admins can read all ...
            return $query;
        } else {
            //users can read their own or any master bots
            return $query->where('user_id', $thisLoggedInUser->id)->orWhereTrue('is_public');
        }
    }

    /**
     * Scope a query a specific property.
     * Get the bots this user is allowed to edit
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyEditableItems($query)
    {
        $thisLoggedInUser = Auth::user();
        if ($thisLoggedInUser->hasRole('admin')) {
            //admins can edit all ...
            return $query;
        } else {
            //users can edit their own
            return $query->where('user_id', $thisLoggedInUser->id);
        }
    }
}
