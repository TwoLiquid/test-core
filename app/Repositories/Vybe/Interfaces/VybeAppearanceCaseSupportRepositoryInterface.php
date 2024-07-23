<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\VybeAppearanceCaseSupport;
use App\Models\MySql\AppearanceCase;

/**
 * Interface VybeAppearanceCaseSupportRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeAppearanceCaseSupportRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeAppearanceCaseSupport|null
     */
    public function findById(
        ?string $id
    ) : ?VybeAppearanceCaseSupport;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param AppearanceCase $appearanceCase
     * @param string $unitSuggestion
     * @param array|null $platformsIds
     *
     * @return VybeAppearanceCaseSupport|null
     */
    public function store(
        AppearanceCase $appearanceCase,
        string $unitSuggestion,
        ?array $platformsIds
    ) : ?VybeAppearanceCaseSupport;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
     * @param AppearanceCase|null $appearanceCase
     * @param string|null $unitSuggestion
     * @param array|null $platformsIds
     *
     * @return VybeAppearanceCaseSupport
     */
    public function update(
        VybeAppearanceCaseSupport $vybeAppearanceCaseSupport,
        ?AppearanceCase $appearanceCase,
        ?string $unitSuggestion,
        ?array $platformsIds
    ) : VybeAppearanceCaseSupport;

    /**
     * This method provides deleting existing rows
     * with an eloquent model
     *
     * @param AppearanceCase $appearanceCase
     *
     * @return bool
     */
    public function deleteForAppearanceCase(
        AppearanceCase $appearanceCase
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
     *
     * @return bool
     */
    public function delete(
        VybeAppearanceCaseSupport $vybeAppearanceCaseSupport
    ) : bool;
}
