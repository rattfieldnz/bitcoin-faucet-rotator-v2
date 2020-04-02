<?php

namespace App\Repositories;

use App\Models\AdBlock;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class AdBlockRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class AdBlockRepository extends Repository implements IRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ad_content',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AdBlock::class;
    }

    /**
     * Create main meta data.
     *
     * @param array $data
     * @return AdBlock
     * @throws ValidatorException
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $adBlockData = self::cleanInput($data);
        $adBlock = parent::create($adBlockData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($adBlock, $adBlockData);
        $adBlock->save();
        return $this->parserResult($adBlock);
    }


    /**
     * Update main meta data,
     *
     * @param array $data
     * @param  $id
     * @return mixed
     * @throws ValidatorException
     */
    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $adBlockData = self::cleanInput($data);
        $adBlock = parent::update($adBlockData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $adBlock = $this->updateRelations($adBlock, $adBlockData);
        $adBlock->save();
        return $this->parserResult($adBlock);
    }

    /**
     * @param array $input
     * @return array
     */
    public static function cleanInput(array $input)
    {
        $input['ad_content'] = Purify::clean($input['ad_content']);
        $input['user_id'] = Purify::clean($input['user_id'], self::$generalFieldsConfig);

        return $input;
    }
}
