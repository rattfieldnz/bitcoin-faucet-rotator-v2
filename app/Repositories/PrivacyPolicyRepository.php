<?php

namespace App\Repositories;

use App\Models\PrivacyPolicy;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class PrivacyPolicyRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class PrivacyPolicyRepository extends Repository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'short_description',
        'content',
        'keywords'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PrivacyPolicy::class;
    }

    /**
     * Create privacy policy data.
     *
     * @param array $data
     * @return PrivacyPolicy
     * @throws ValidatorException
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $privacyPolicyData = self::cleanInput($data);
        $privacyPolicy = parent::create($privacyPolicyData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($privacyPolicy, $privacyPolicyData);
        $privacyPolicy->save();
        return $this->parserResult($privacyPolicy);
    }

    /**
     * Update privacy policy data,
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
        $privacyPolicyData = self::cleanInput($data);
        $privacyPolicy = parent::update($privacyPolicyData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $privacyPolicy = $this->updateRelations($privacyPolicy, $privacyPolicyData);
        $privacyPolicy->save();
        return $this->parserResult($privacyPolicy);
    }

    /**
     * Sanitize inout for main meta.
     *
     * @param  array $input
     * @return array
     */
    public static function cleanInput(array $input)
    {
        $input['title'] = Purify::clean($input['title'], self::$generalFieldsConfig);
        $input['short_description'] = Purify::clean($input['short_description'], self::$generalFieldsConfig);
        $input['keywords'] = Purify::clean($input['keywords'], self::$generalFieldsConfig);
        $input['content'] = Purify::clean($input['content']);

        return $input;
    }
}
