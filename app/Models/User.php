<?php

namespace App\Models;

use App\Traits\UiValidationTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={ "slug", "name", "email", "password"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_verified_at",
 *          description="email_verified_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="api_token",
 *          description="api_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
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
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use HasSlug;
    use UiValidationTrait;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'api_token' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'password' => 'string|max:255'
    ];


    /**
     * @return void
     */
    protected static function booted()
    {
        //when we create a new user we want to create a temp password for them
        static::creating(function ($model) {
            $model->password = 'tmp_'.bin2hex(random_bytes(10));
        });
    }



    /**
     * Parse the validation rules for the front end
     *
     * @return string
     * @var array
     */
    public static function getFormValidation($fieldName)
    {

        $theRules = self::$rules;
        $validationArr = [];

        if (!isset($theRules[$fieldName])) {
            return 'data-validation=""';
        }

        $theRuleParts = explode("|", $theRules[$fieldName]);

        foreach ($theRuleParts as $rule) {
            if ($rule=='required') {
                $validationArr[]='data-validation="required"';
            } elseif (strpos($rule, "max:")!==false) {
                $lengthParts = explode("max:", $rule);
                $validationArr[]='data-validation-length="max'.$lengthParts[1].'"';
            } elseif (strpos($rule, "min:")!==false) {
                $lengthParts = explode("min:", $rule);
                $validationArr[]='data-validation-length="min'.$lengthParts[1].'"';
            }
        }

        if (!empty($validationArr)) {
            return implode(" ", $validationArr);
        } else {
            return 'data-validation=""';
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function botTraits()
    {
        return $this->hasMany(\App\Models\BotTrait::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function bots()
    {
        return $this->hasMany(\App\Models\Bot::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function categories()
    {
        return $this->hasMany(\App\Models\Category::class, 'user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mapValues()
    {
        return $this->hasMany(\App\Models\MapValue::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function maps()
    {
        return $this->hasMany(\App\Models\Map::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function setValues()
    {
        return $this->hasMany(\App\Models\SetValue::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function sets()
    {
        return $this->hasMany(\App\Models\Set::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function wordSpellings()
    {
        return $this->hasMany(\App\Models\WordSpelling::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function wordTransformations()
    {
        return $this->hasMany(\App\Models\WordTransformation::class, 'user_id');
    }


    /**
     * @return mixed
     */
    public function dataTableQuery()
    {

        return User::select(['users.deleted_at','users.slug','users.id','users.name','users.email','users.created_at'])->withTrashed();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Str::uuid()->toString();
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Does this model use slugs?
     *
     * @return string
     */
    public static function hasSlug()
    {
        return true;
    }
}
