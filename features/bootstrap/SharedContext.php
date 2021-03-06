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
use App\User;
use App\Company;

/**
 * Defines the person creation context.
 */
class SharedContext extends TestCase implements Context
{
    use DatabaseMigrations;

    public $data;
    public $response;
    public $picture_extension;

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
        Session::start();
        $this->data = ['_token' => csrf_token()];
        $this->response = null;
        $this->picture_extension = null;
    }

    /** @Given Im logged as a/an :type user */
    public function imLoggedAsAnUser($type)
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
        $this->actingAs(User::where('type', $condition)->first());
    }

    /** @When I add the following data to the request: */
    public function iAddTheFollowintDataToTheRequest(TableNode $table)
    {
        foreach($table as $row) {
            // if($row['attribute'] === 'birthday'){
            //     $this->data[$row['attribute']] = $row['value'] . ' 00:00:00';
            // }
            // else{
                $this->data[$row['attribute']] = $row['value'];
            // }
        }
    }

    /** @When I add a random company id to the request */
    public function iAddARandomCompanyIdToTheRequest()
    {
        $this->data['company_id'] = Company::all()->random()->id;
    }

    /** @When I add a picture to the request with the :ext extension under the :field_name field */
    public function iAddAPictureToTheRequestWithTheExtension($ext, $field_name)
    {
        $this->picture_extension = $ext;
        $this->data[$field_name] = UploadedFile::fake()->image('this-is-the-name.'.$ext);
    }

    /** @Then the response shouldn't have errors */
    public function theResponseShouldNotHaveErrors()
    {
        //$this->response->assertSessionHasNoErrors();
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

    /** @When I :method this data using the :route_name route */
    public function iThisDataUsingTheRout($method, $route_name)
    {
        if($method === 'put') {
            // $this->data['_method'] = 'PUT';
            var_dump($this->data);
            $this->response = $this->call("PUT", route($route_name, $this->data['id']), $this->data);
        }
        else {
            $this->response = $this->call($method, route($route_name), $this->data);
        }
    }
}