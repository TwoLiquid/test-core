<?php

namespace App\Models\MySql;

use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Eloquent;

/**
 * App\Models\MySql\AppearanceCasePlatform
 *
 * @property int $appearance_case_id
 * @property int $platform_id
 * @property-read AppearanceCase $appearanceCase
 * @property-read Platform $platform
 * @property-read Collection<int, Vybe> $vybes
 * @property-read int|null $vybes_count
 * @method static Builder|AppearanceCasePlatform newModelQuery()
 * @method static Builder|AppearanceCasePlatform newQuery()
 * @method static Builder|AppearanceCasePlatform query()
 * @method static Builder|AppearanceCasePlatform whereAppearanceCaseId($value)
 * @method static Builder|AppearanceCasePlatform wherePlatformId($value)
 * @mixin Eloquent
 */
class AppearanceCasePlatform extends Pivot
{
    /**
     * @return BelongsTo
     */
    public function platform() : BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * @return BelongsTo
     */
    public function appearanceCase() : BelongsTo
    {
        return $this->belongsTo(AppearanceCase::class);
    }

    /**
     * @return HasManyThrough
     */
    public function vybes() : HasManyThrough
    {
        return $this->hasManyThrough(Vybe::class, AppearanceCase::class);
    }
}
