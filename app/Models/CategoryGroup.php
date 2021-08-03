<?php

namespace App\Models;

use App\Traits\UiValidationTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="CategoryGroup",
 *      required={"user_id", "language_id", "slug", "name", "description", "status", "is_master"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="language_id",
 *          description="language_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_master",
 *          description="is_master",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
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
class CategoryGroup extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use CascadeSoftDeletes;

    protected $cascadeDeletes = ['categories'];


    public $table = 'category_groups';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'language_id',
        'slug',
        'name',
        'description',
        'status',
        'is_master'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'language_id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status' => 'string',
        'is_master' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'language_id' => 'required',
        'name' => 'required|unique:category_groups,name|string|max:255',
        'description' => 'required|string',
        'status' => 'required|string',
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
            //if the user is not an admin overwrite is master with 0
            if (!$loggedInUser->hasRole('admin')) {
                $model->is_master = 0;
            }
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function language()
    {
        return $this->belongsTo(\App\Models\Language::class, 'language_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botCategoryGroups()
    {
        return $this->hasMany(\App\Models\BotCategoryGroup::class, 'category_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class, 'category_group_id');
    }


    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {
        $thisUserId = Auth::user()->id;

        //this is an admin so show everything
        if(Auth::user()->hasRole('admin')){

            return CategoryGroup::select([$this->table.'.*',
                'users.email as email',
                'languages.name as language'])
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id')
                ->leftJoin('languages', 'languages.id', '=', $this->table.'.language_id');

        }else{
            //this is a author so only show items which are owned by this user or are master items
            return CategoryGroup::select([$this->table.'.*',
                'users.email as email',
                'languages.name as language'])
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id')
                ->leftJoin('languages', 'languages.id', '=', $this->table.'.language_id')
                ->where($this->table.'.is_master', 1)
                ->orWhere($this->table.'.user_id', $thisUserId);

        }


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
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyEditableItems($query)
    {
        $thisLoggedInUser = Auth::user();
        //admins can get all ...
        if ($thisLoggedInUser->hasRole('admin')) {
            return $query;
        } else {
            return $query->where('user_id', $thisLoggedInUser->id);
        }
    }
}
