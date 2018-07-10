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
class CreateUserContext extends FeatureContext
{

    protected $picture_extension;

    /**
     * @When I create a new person with the following data:
     */
    public function iCreateNewPersonWith(TableNode $table)
    {
        $this->person = new stdClass();

        foreach($table as $row) {
            $this->data['last_name'] = $row['last_name'] ?? null;
            $this->data['name'] = $row['name'] ?? null;
            $this->data['cuil'] = $row['cuil'] ?? null;
            $this->data['sex'] = $row['sex'] ?? null;
            if(isset($row['company_id'])) {
                $this->data['company_id'] = $row['company_id'] === 'random' ? Company::all()->random()->id : $row['company_id'];
            }
            else {
                $this->data['company_id'] = null;
            }
            $this->data['birthday'] = $row['birthday'] ?? null;
        }
    }

    /**
     * @When I add a picture to this person with the :ext extension
     */
    public function iAddAPicture($ext)
    {
        $this->picture_extension = $ext;
        $this->data['picture'] = UploadedFile::fake()->image('this-is-the-name.'.$ext);
    }

    /**
     * @Then I should be able to retrieve the same person I created from the database
     */
    public function iShouldRetrieveTheSamePerson()
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

    /**
     * @Then there must be at least :number card/s with the :status status associated with the :cuil cuil
     */
    public function thereMustBeAtLeastCardWithTheStatusAssociatedWithTheCuil($number, $status, $cuil)
    {
        $retrieve = Person::where('cuil', $cuil)->first();
        $cards = Card::where('person_id', $retrieve->id)->where('active', $status === 'active' ? true : false)->get();
        $this->assertTrue($cards->count() >= $number);
    }
}
