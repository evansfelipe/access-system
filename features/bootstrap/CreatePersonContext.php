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
use Illuminate\Foundation\Testing\DatabaseMigrations;
// Our data models
use App\Card;
use App\Person;
use App\Company;
use App\User;

/**
 * Defines the person creation context.
 */
class CreatePersonContext extends TestCase implements Context
{
    use DatabaseMigrations;

    protected $data;
    protected $response;
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
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');
        $this->data = [];
        $this->response = null;
        $this->picture_extension = null;
    }

    /** @AfterScenario */
    public function after(AfterScenarioScope $scope) {}

    /** @Given Im logged as an :type */
    public function imLoggedAsAn($type)
    {
        $condition = null;        
        switch($type) {
            case "root":
                $condition = User::ROOT;
                break;
            case "administration":
                $condition = User::ADMINISTRATION;
                break;
            case "security":
                $condition = User::SECURITY;
                break;
            default:
                throw new Exception();
                break;
        }
        $user = User::where('type', $condition)->first();
        Session::start();
        $this->actingAs($user);
    }

    /** @When I create a new person with the following data: */
    public function iCreateNewPersonWithTheFollowingData(TableNode $table)
    {
        foreach($table as $row) {
            $this->data['last_name']  = $row['last_name'] ?? null;
            $this->data['name']       = $row['name']      ?? null;
            $this->data['cuil']       = $row['cuil']      ?? null;
            $this->data['sex']        = $row['sex']       ?? null;
            $this->data['birthday']   = $row['birthday']  ?? null;
            $this->data['company_id'] = null;
            if(isset($row['company_id'])) {
                $this->data['company_id'] = $row['company_id'] === 'random' ? Company::all()->random()->id : $row['company_id'];
            }
        }
    }

    /** @When I add a picture to this person with the :ext extension */
    public function iAddAPictureToThisPersonWithTheExtension($ext)
    {
        $this->picture_extension = $ext;
        $this->data['picture'] = UploadedFile::fake()->image('this-is-the-name.'.$ext);
    }

    /** @When I store this new person to the database */
    public function iStoreThisNewPersonToTheDatabase()
    {
        $this->data['_token'] = csrf_token();
        $this->response = $this->call('POST', route('people.store'), $this->data);
    }

    /** @Then the response shouldn't have errors */
    public function theResponseShouldNotHaveErrors()
    {
        // A singleton response should resolve the problem of sharing this method within contexts
        $this->response->assertSessionHasNoErrors();
    }

    /** @Then the response should have errors in: */
    public function theResponseShouldHaveErrorsIn(TableNode $table)
    {
        $fields = [];
        foreach($table as $row) {
            array_push($fields, $row['field']);
        }
        $this->response->assertSessionHasErrors($fields);
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
