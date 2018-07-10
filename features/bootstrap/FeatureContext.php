<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Person;
use App\Company;
use App\Card;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

/**
 * Defines application features from the specific context.
 */
abstract class FeatureContext extends TestCase implements Context
{
    use DatabaseMigrations;

    protected $response;
    protected $data;

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
    }

    /** @AfterScenario */
    public function after(AfterScenarioScope $scope) {}

    /**
     * @When I :method this data to the database using the :route_name route
     */
    public function iSaveThisDataOnDB($method, $route_name)
    {
        Session::start();
        $this->data['_token'] = csrf_token();
        if($method === 'put') {
            $this->data['_method'] = 'PUT';
        }
        $this->response = $this->call('POST', route($route_name), $this->data);
    }

    /**
     * @Then the response shouldn't have errors
     */
    public function responseShouldNotHaveErrors()
    {
        $this->response->assertSessionHasNoErrors();
    }

    /**
     * @Then the response should have errors in:
     */
    public function responseShouldHaveErrors(TableNode $table)
    {
        $fields = [];
        foreach($table as $row) {
            array_push($fields, $row['field']);
        }
        $this->response->assertSessionHasErrors($fields);
    }
}
