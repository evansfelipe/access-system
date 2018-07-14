Feature: Update an existing person
    In order to update the person information
    The administration user should be able to edit a person

    Background:
      Given Im logged as an "administration" user
      When  I add the following data to the request:
            | attribute | value       |
            | last_name | Last Name   |
            | name      | Name        |
            | cuil      | 20366578959 |
            | sex       | M           |
            | birthday  | 2018-01-01  |
      And   I add a random company id to the request
      And   I "post" this data using the "people.store" route
      Then  the response shouldn't have errors
      And  I should be able to retrieve the same person I created from the database

    Scenario: Editing the profile picture existing person with a jpg photo
      Given The cuil "20366578959" of an existing person
      When I add the following data to the request:
           | attribute | value          |
           | last_name | The Last Name  |
      And   I "put" this data using the "people.update" route
      Then  the response shouldn't have errors
      And   I should be able to retrieve the same person I updated from the database

    # Scenario: Editing the profile picture existing person with a jpg photo
    #   Given The cuil "20366578959" of an existing person
    #   When  I add a picture to the request with the "jpg" extension under the "picture" field
    #   And   I "put" this data using the "people.update" route
    #   Then  the response shouldn't have errors
    #   And   I should be able to retrieve the same person I created from the database