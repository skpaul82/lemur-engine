<?php namespace Tests\Requests;

use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use Tests\TestCase;
use Illuminate\Validation\Validator;

class UpdateLanguageRequestTest extends TestCase
{
    /** @var $rules */
    private $rules;

    /** @var Validator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new UpdateLanguageRequest())->rules();
    }

    public function validationProvider()
    {
        //this has to go here otherwise we cannot use the factory properly
        $this->createApplication();

        $requiredFields = ["name", "description"];

        foreach ($requiredFields as $field) {
            $languageDataArr = factory(Language::class)->make()->toArray();
            $languageDataArr = $this->cleanPostArray($languageDataArr);
            unset($languageDataArr[$field]);

            $items['request_should_fail_when_no_'.$field.'_is_provided'] = ['passed' => false,
                                                                            'data' => $languageDataArr];
        }

        $languageDataArr = factory(Language::class)->make(['name'=>'en'])->toArray();
        $languageDataArr = $this->cleanPostArray($languageDataArr);
        $items['request_should_fail_when_duplicate_data_is_provided'] = ['passed' => false,
                                                                        'data' => $languageDataArr];


        return $items;
    }


    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function testRequiredFieldsValidateAsExpected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }

    public function cleanPostArray($arr)
    {

        unset($arr['slug']);
        unset($arr['created_at']);
        unset($arr['updated_at']);
        return $arr;
    }
}
