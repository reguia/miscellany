<?php

namespace App;

use App\Models\Campaign;
use App\Facades\CampaignLocalization;
use App\Models\CampaignBoost;
use App\Models\Concerns\Filterable;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Sortable;
use App\Models\Patreon;
use App\Models\Scopes\UserScope;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 *
 * @property string $name
 * @property string $email
 * @property integer $last_campaign_id
 * @property string $provider
 * @property integer $provider_id
 * @property string $last_login_at
 * @property integer $welcome_campaign_id
 * @property boolean $newsletter
 * @property boolean $has_last_login_sharing
 * @property string $patreon_pledge
 *
 * Virtual
 * @property bool $advancedMentions
 * @property bool $defaultNested
 * @property string $patreon_fullname
 * @property string $patreon_email
 * @property CampaignBoost[] $boosts
 */
class User extends \TCG\Voyager\Models\User
{
    /**
     * Cached calculation if the user is an admin of the current campaign he is viewing
     * @var null
     */
    protected $isAdminCached = null;

    protected static $currentCampaign = false;

    protected $cachedHasCampaign = null;


    public $additional_attributes = [
        'patreon_fullname',
        //'patreon_email'
    ];

    public $searchableColumns = ['email', 'settings'];
    public $sortableColumns = [];
    public $filterableColumns = ['patreon_pledge'];

    use Notifiable, HasApiTokens, UserScope, UserSetting, Searchable, Filterable, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_campaign_id',
        'provider',
        'provider_id',
        'newsletter',
        'timezone',
        'campaign_role',
        'theme',
        'date_format',
        'default_pagination',
        'locale', // Keep this for the LocaleChange middleware
        'last_login_at',
        'has_last_login_sharing',
        'patreon_pledge'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login_at',
    ];

    /**
     * Get the user's campaign.
     * This is the equivalent of calling user->campaign or user->getCampaign
     * @return Campaign|null
     */
    public function getCampaignAttribute()
    {
        // We use a dirty static system because relying on the last_campaign_id doesn't work when two sessions
        // are active form the same user.
        if (self::$currentCampaign === false) {
            self::$currentCampaign = CampaignLocalization::getCampaign();
        }
        return self::$currentCampaign;
    }

    /**
     * Last campaign the user switched to.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastCampaign()
    {
        return $this->belongsTo(Campaign::class, 'last_campaign_id', 'id');
    }

    /**
     * Get the user's campaign
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dashboardSetting()
    {
        return $this->hasOne('App\Models\UserDashboardSetting', 'user_id', 'id');
    }

    /**
     * Get a list of campaigns the user is in
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function campaigns()
    {
        return $this->hasManyThrough(
            'App\Models\Campaign',
            'App\Models\CampaignUser',
            'user_id',
            'id',
            'id',
            'campaign_id'
        );
    }

    /**
     * @return mixed
     */
    public function following()
    {
        return $this->hasManyThrough(
            'App\Models\Campaign',
            'App\Models\CampaignFollower',
            'user_id',
            'id',
            'id',
            'campaign_id'
        );
    }

    /**
     * Get the other campaigns of the user
     * @param bool $hasEmpty
     * @return array
     */
    public function moveCampaignList(bool $hasEmpty = true): array
    {
        $campaigns = $hasEmpty ? [0 => ''] : [];
        foreach ($this->campaigns()->whereNotIn('campaign_id', [$this->campaign->id])->get() as $campaign) {
            $campaigns[$campaign->id] = $campaign->name;
        }
        return $campaigns;
    }

    /**
     * @return string
     */
    public function getAvatarUrl($thumb = false): string
    {
        if (!empty($this->avatar) && $this->avatar != 'users/default.png') {
            return Storage::url(($thumb ? str_replace('.', '_thumb.', $this->avatar) : $this->avatar));
        } else {
            return '/images/defaults/user.svg';
        }
    }

    /**
     * @param bool $thumb
     * @return string
     */
    public function getImageUrl($thumb = false): string
    {
        if (empty($this->avatar)) {
            return asset('/images/defaults/' . $this->getTable() . ($thumb ? '_thumb' : null) . '.jpg');
        } else {
            return Storage::url(($thumb ? str_replace('.', '_thumb.', $this->avatar) : $this->avatar));
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany('App\Models\UserLog', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany('App\Models\CampaignPermission', 'user_id');
    }

    /**
     * @param null $campaignId
     * @return mixed
     */
    public function rolesList($campaignId = null)
    {
        if (empty($campaignId) && !empty($this->campaign)) {
            $campaignId = $this->campaign->id;
        }
        $roles = $this->campaignRoles($campaignId)->get();
        return $roles->implode('name', ', ');
    }

    /**
     * @param null $campaignId
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function campaignRoles($campaignId = null)
    {
        if (empty($campaignId) && !empty($this->campaign)) {
            $campaignId = $this->campaign->id;
        }

        return $this->hasManyThrough(
            'App\Models\CampaignRole',
            'App\Models\CampaignRoleUser',
            'user_id',
            'id',
            'id',
            'campaign_role_id'
        )
            ->where('campaign_id', $campaignId);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @deprecated User::campaignRoleUser is deprecated
     */
    public function campaignRoleUser()
    {
        return $this->hasMany('App\Models\CampaignRoleUser')
            ->where('campaign_id', $this->campaign->id);
    }

    /**
     * Figure out if the user is an admin of the current campaign
     */
    public function isAdmin(): bool
    {
        if ($this->isAdminCached === null) {
            $this->isAdminCached = $this->campaignRoles()->where(['is_admin' => true])->count() > 0;
        }
        return $this->isAdminCached;
    }

    /**
     * Check if a user has campaigns
     * @return bool
     */
    public function hasCampaigns($count = 0): bool
    {
        if ($this->cachedHasCampaign === null) {
            $this->cachedHasCampaign = $this->campaigns()->count() > $count;
        }
        return $this->cachedHasCampaign;
    }

    /**
     * Check if the user has other campaigns than the current one
     * @param int $campaignId
     * @return bool
     */
    public function hasOtherCampaigns(int $campaignId): bool
    {
        return $this->campaigns()->where('campaign_id', '!=', $campaignId)->count() > 0;
    }

    /**
     * Get the user's avatar
     * @param bool $thumb
     * @return string
     */
    public function getAvatar($thumb = false)
    {
        return "<span class=\"entity-image\" style=\"background-image: url('" .
            $this->getImageUrl(true) . "');\" title=\"" . e($this->name) . "\"></span>";
    }


    /**
     * Get max file size of user
     * @param bool $readable
     * @return int|string
     */
    public function maxUploadSize($readable = false, $what = 'image')
    {
        $campaign = CampaignLocalization::getCampaign();
        if ($this->isPatron() || ($campaign && $campaign->boosted())) {
            // Elementals get massive upload sizes
            if ($this->isElementalPatreon()) {
                return $readable ? '25MB' : 25600;
            }
            if ($what == 'map') {
                return $readable ? '10MB' : 10240;
            }
            return $readable ? '8MB' : 8192;
        }
        return $readable ? '2MB' : 2048;
    }

    /**
     * Determine if a user is a patron
     * @return bool
     */
    public function isPatron(): bool
    {
        return $this->hasRole('patreon') || $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isGoblinPatron(): bool
    {
        return ($this->hasRole('patreon') && !empty($this->patreon_pledge)
                && $this->patreon_pledge != Patreon::PLEDGE_KOBOLD)
           || $this->hasRole('admin')
        ;
    }

    /**
     * @return bool
     */
    public function isElementalPatreon(): bool
    {
        return !empty($this->patreon_pledge) && $this->patreon_pledge == Patreon::PLEDGE_ELEMENTAL;
    }


    /**
     * List of boosts the user is giving
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boosts()
    {
        return $this->hasMany('App\Models\CampaignBoost', 'user_id', 'id');
    }

    /**
     * Get available boosts for the user
     * @return int
     */
    public function availableBoosts(): int
    {
        return $this->maxBoosts() - $this->boosting();
    }

    /**
     * Get amount of campaigns the user is boosting
     * @return int
     */
    public function boosting(): int
    {
        return $this->boosts->count();
    }

    /**
     * Get max number of boosts a user can give
     * @return int
     */
    public function maxBoosts(): int
    {
        if (!$this->isPatron()) {
            return 0;
        }

        if ($this->hasRole('admin')) {
            return 3;
        }

        $levels = [
            Patreon::PLEDGE_KOBOLD => 0,
            Patreon::PLEDGE_GOBLIN => 1,
            Patreon::PLEDGE_OWLBEAR => 3,
            Patreon::PLEDGE_ELEMENTAL => 10,
        ];

        // Default 3 for admins and owlbears
        return Arr::get($levels, $this->patreon_pledge, 0);
    }

    /**
     * API throttling is increased for patrons
     * @return int
     */
    public function getRateLimitAttribute(): int
    {
        return $this->isGoblinPatron() ? 90 : 30;
    }
}
