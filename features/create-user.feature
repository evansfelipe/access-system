Feature: Create a new person
    In order to track the people activity inside the system
    The administrator should be able to create new people

    Scenario: Creating a new person with all the data required
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response shouldn't have errors
        And   I should be able to retrieve the same person I created from the database
        And   there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a jpg profile picture
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I add a picture to this person with the "jpg" extension
        And   I store this new person to the database
        Then  the response shouldn't have errors
        And   I should be able to retrieve the same person I created from the database
        And   there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a jpeg profile picture
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I add a picture to this person with the "jpeg" extension
        And   I store this new person to the database
        Then  the response shouldn't have errors
        And   I should be able to retrieve the same person I created from the database
        And   there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a png profile picture
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I add a picture to this person with the "png" extension
        And   I store this new person to the database
        Then  the response shouldn't have errors
        And   I should be able to retrieve the same person I created from the database
        And   there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a profile picture with an invalid extension
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I add a picture to this person with the "bmap" extension
        And   I store this new person to the database
        Then  the response should have errors in:
              | field    |
              | picture  |

    Scenario: Creating a new person without the last name
        When  I create a new person with the following data:
              | name | cuil        | sex | company_id | birthday   |
              | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field     |
              | last_name |

    Scenario: Creating a new person with a long last name
        When  I create a new person with the following data:
              | last_name                                           | name | cuil        | sex | company_id | birthday   |
              | AaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaa | Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field     |
              | last_name |

    Scenario: Creating a new person without the name
        When  I create a new person with the following data:
              | last_name | cuil        | sex | company_id | birthday   |
              | Last Name | 20366578959 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | name  |

    Scenario: Creating a new person with a long name
        When  I create a new person with the following data:
              | last_name | name                                                | cuil        | sex | company_id | birthday   |
              | Last Name | AaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaa | 20366578959 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | name  |

    Scenario: Creating a new person without the cuil
        When  I create a new person with the following data:
              | last_name | name | sex | company_id | birthday   |
              | Last Name | Name | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | cuil  |

    Scenario: Creating a new person whit a short cuil
        When  I create a new person with the following data:
              | last_name | name | cuil | sex | company_id | birthday   |
              | Last Name | Name | 2000 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | cuil  |

    Scenario: Creating a new person whit a long cuil
        When  I create a new person with the following data:
              | last_name | name | cuil             | sex | company_id | birthday   |
              | Last Name | Name | 2036657895999999 | M   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | cuil  |

    Scenario: Creating a new person without the sex
        When  I create a new person with the following data:
              | last_name | name | cuil        | company_id | birthday   |
              | Last Name | Name | 20366578959 | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | sex   |
    
    Scenario: Creating a new person with an invalid sex
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | Z   | random     | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field |
              | sex   |

    Scenario: Creating a new person without the company_id
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | birthday   |
              | Last Name | Name | 20366578959 | M   | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field      |
              | company_id |

    Scenario: Creating a new person with a company_id that doesn't exists on the database
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | 11111      | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field      |
              | company_id |

    Scenario: Creating a new person with a company_id that isn't an integer value
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | TESTING    | 2018-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field      |
              | company_id |

    Scenario: Creating a new person without the birthday
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id |
              | Last Name | Name | 20366578959 | M   | random     |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field    |
              | birthday |

    Scenario: Creating a new person with a birthday that has an invalid format
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday |
              | Last Name | Name | 20366578959 | M   | random     | 1950-01  |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field    |
              | birthday |
    
    Scenario: Creating a new person with a birthday that is previous to 1900-01-01
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 1899-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field    |
              | birthday |

    Scenario: Creating a new person with a birthday that is far away on the future
        When  I create a new person with the following data:
              | last_name | name | cuil        | sex | company_id | birthday   |
              | Last Name | Name | 20366578959 | M   | random     | 2899-01-01 |
        And   I store this new person to the database
        Then  the response should have errors in:
              | field    |
              | birthday |