<?php
// Behat classes
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
// Php tests class
use Tests\TestCase;
// Our data models
use App\Card;
use App\Person;


/**
 * Defines the person creation context.
 */
class PersonContext extends TestCase implements Context
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
        $this->picture_extension = &$shared->picture_extension;
    }

    /** @Then I should be able to retrieve the same person I created/updated from the database */
    public function iShouldBeAbleToRetrieveTheSamePersonICreatedFromTheDatabase()
    {
        $retrieve = Person::where('cuil', $this->data['cuil'])->first();
        $this->assertEquals($this->data['last_name'], $retrieve->last_name);
        $this->assertEquals($this->data['name'], $retrieve->name);
        $this->assertEquals($this->data['cuil'], $retrieve->cuil);
        $this->assertEquals($this->data['sex'], $retrieve->sex);
        $this->assertEquals($this->data['company_id'], $retrieve->company_id);
        $this->assertEquals(date($this->data['birthday']).' 00:00:00', $retrieve->birthday);
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

    /** @Given The cuil :cuil of an existing person */
    public function theCuilOfAnExistingPerson($cuil)
    {
        $person = Person::where('cuil', $cuil)->first();
        $this->data['id'] = $person->id;
        $this->data['last_name'] = $person->last_name;
        $this->data['name'] = $person->name;
        $this->data['cuil'] = $person->cuil;
        $this->data['sex'] = $person->sex;
        $this->data['company_id'] = $person->company_id;
        // $this->data['birthday'] = $person->birthday;
    }

}
