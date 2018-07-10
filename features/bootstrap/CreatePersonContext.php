<?php
// Behat classes
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
// Php tests class
use Tests\TestCase;
// Laravel test helpers
use Illuminate\Http\UploadedFile;
// Our data models
use App\Card;
use App\Person;
use App\Company;

/**
 * Defines the person creation context.
 */
class CreatePersonContext extends TestCase implements Context
{
    protected $data; // Must be a reference to shared context data
    protected $response; // Must be a reference to shared context response
    protected $picture_extension;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        parent::setUp();
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $shared = $environment->getContext('SharedContext');

        $this->data = &$shared->data;
        $this->response = &$shared->response;

        $this->picture_extension = null;
    }

    /** @When I add a random company id to this person */
    public function iAddARandomCompanyIdToThisPerson()
    {
        $this->data['company_id'] = Company::all()->random()->id;
    }

    /** @When I add a picture to this person with the :ext extension */
    public function iAddAPictureToThisPersonWithTheExtension($ext)
    {
        $this->picture_extension = $ext;
        $this->data['picture'] = UploadedFile::fake()->image('this-is-the-name.'.$ext);
    }

    /** @Then I should be able to retrieve the same person I created from the database */
    public function iShouldBeAbleToRetrieveTheSamePersonICreatedFromTheDatabase()
    {
        $retrieve = Person::where('cuil', '=', $this->data['cuil'])->first();
        $this->assertEquals($this->data['last_name'], $retrieve->last_name);
        $this->assertEquals($this->data['name'], $retrieve->name);
        $this->assertEquals($this->data['cuil'], $retrieve->cuil);
        $this->assertEquals($this->data['sex'], $retrieve->sex);
        $this->assertEquals($this->data['company_id'], $retrieve->company_id);
        $this->assertEquals(date($this->data['birthday'].' 00:00:00'), $retrieve->birthday);
        if($this->picture_extension) {
            $this->assertEquals($this->data['cuil'] . '.' . $this->picture_extension, $retrieve->picture_name);
        }
    }

    /** @Then there must be at least :number card/s with the :status status associated with the :cuil cuil */
    public function thereMustBeAtLeastCardWithTheStatusAssociatedWithTheCuil($number, $status, $cuil)
    {
        $retrieve = Person::where('cuil', $cuil)->first();
        $cards = Card::where('person_id', $retrieve->id)->where('active', $status === 'active' ? true : false)->get();
        $this->assertTrue($cards->count() >= $number);
    }
}
