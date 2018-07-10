Feature: Create a new person
    In order to track the people activity inside the system
    The administrator should be able to create new people

    Background:
      Given   Im logged as an "administration" user

    Scenario: Creating a new person with all the data required
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response shouldn't have errors
      And  I should be able to retrieve the same person I created from the database
      And  there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a jpg profile picture
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I add a picture to this person with the "jpg" extension
      And  I "post" this data using the "people.store" route
      Then the response shouldn't have errors
      And  I should be able to retrieve the same person I created from the database
      And  there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a jpeg profile picture
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I add a picture to this person with the "jpeg" extension
      And  I "post" this data using the "people.store" route
      Then the response shouldn't have errors
      And  I should be able to retrieve the same person I created from the database
      And  there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a png profile picture
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I add a picture to this person with the "png" extension
      And  I "post" this data using the "people.store" route
      Then the response shouldn't have errors
      And  I should be able to retrieve the same person I created from the database
      And  there must be at least "1" card with the "active" status associated with the "20366578959" cuil

    Scenario: Creating a new person with all the data required and a profile picture with an invalid extension
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I add a picture to this person with the "bmap" extension
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field    |
           | picture  |

    Scenario: Creating a new person without the last name
      When I add the following data to the request:
           | attribute | value       |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field     |
           | last_name |

    Scenario: Creating a new person with a long last name
      When I add the following data to the request:
           | attribute | value                                               |
           | last_name | AaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaa |
           | name      | Name                                                |
           | cuil      | 20366578959                                         |
           | sex       | M                                                   |
           | birthday  | 2018-01-01                                          |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field     |
           | last_name |

    Scenario: Creating a new person without the name
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | name  |

    Scenario: Creating a new person with a long name
      When I add the following data to the request:
           | attribute | value                                               |
           | last_name | Last Name                                           |
           | name      | AaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaAaaaaaaaaaa |
           | cuil      | 20366578959                                         |
           | sex       | M                                                   |
           | birthday  | 2018-01-01                                          |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | name  |

    Scenario: Creating a new person without the cuil
      When I add the following data to the request:
           | attribute | value      |
           | last_name | Last Name  |
           | name      | Name       |
           | sex       | M          |
           | birthday  | 2018-01-01 |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | cuil  |

    Scenario: Creating a new person whit a short cuil
      When I add the following data to the request:
           | attribute | value      |
           | last_name | Last Name  |
           | name      | Name       |
           | cuil      | 2000       |
           | sex       | M          |
           | birthday  | 2018-01-01 |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | cuil  |

    Scenario: Creating a new person whit a long cuil
      When I add the following data to the request:
           | attribute | value                |
           | last_name | Last Name            |
           | name      | Name                 |
           | cuil      | 20366578959999999999 |
           | sex       | M                    |
           | birthday  | 2018-01-01           |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | cuil  |

    Scenario: Creating a new person without the sex
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | sex   |
    
    Scenario: Creating a new person with an invalid sex
      When I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | Z           |
           | birthday  | 2018-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field |
           | sex   |

    Scenario: Creating a new person without the company_id
      When  I add the following data to the request:
           | attribute | value       |
           | last_name | Last Name   |
           | name      | Name        |
           | cuil      | 20366578959 |
           | sex       | M           |
           | birthday  | 2018-01-01  |
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field      |
           | company_id |

    Scenario: Creating a new person with a company_id that doesn't exists on the database
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
           | birthday   | 2018-01-01  |
           | company_id | 1111111     |
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field      |
           | company_id |

    Scenario: Creating a new person with a company_id that isn't an integer value
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
           | birthday   | 2018-01-01  |
           | company_id | TESTING     |
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field      |
           | company_id |

    Scenario: Creating a new person without the birthday
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field    |
           | birthday |

    Scenario: Creating a new person with a birthday that has an invalid format
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
           | birthday   | 1950-01     |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field    |
           | birthday |
    
    Scenario: Creating a new person with a birthday that is previous to 1900-01-01
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
           | birthday   | 1850-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field    |
           | birthday |

    Scenario: Creating a new person with a birthday that is far away on the future
      When I add the following data to the request:
           | attribute  | value       |
           | last_name  | Last Name   |
           | name       | Name        |
           | cuil       | 20366578959 |
           | sex        | M           |
           | birthday   | 2900-01-01  |
      And  I add a random company id to this person
      And  I "post" this data using the "people.store" route
      Then the response should have errors in:
           | field    |
           | birthday |