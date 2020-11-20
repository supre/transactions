# Simple Data Driven Transaction System

## Overview
This project has been created for TrackTik's technical test evaluation. The following scenario replictions have been provided
 to validate the questions asked in the technical test:
 
1. A REST api has been provided that allows making a dummy order. 
    1. Each transaction payload consists of multiple line items. 
    2. Each line item contains 1 electronic item. 
    3. Each electronic item can have extras ranging from 0 
to infinite as per configuration. 
    4. Line items return in descending order. 
       > This can be used for evaluation of first question. Line items return in descending order. 
       Transaction payload contains the total price of transaction.
    5. Line items in the transaction payload contain the total price of each electronic item & any extra item.
        > This can be used for evaluation of the second question i.e. the price of console & its additional controllers would be 
        specified in the line item.
2. Unit Tests have been created to validate the code integrity. Following test cases can be referred for validating the solutions
   to the technical questions:
   1. For first question following tests can be executed
       1. `TransactionTest::testTransactionTotalIsAsExpected` to validate that transaction total is as expected.
       Run test in terminal as following
           ```
            docker exec -t tt_service ./vendor/bin/codecept run -g total
           ```         
       2. `TransactionTest::testItemsAreSortedAsExpected` & `ElectronicItemsRepositoryTest::testItemsAreSortedAsExpected` 
       to validate that sorting is working as expected. Run test in terminal as following
           ```
            docker exec -t tt_service ./vendor/bin/codecept run -g sort
           ```    

---

### Requirements
Application is Dockerized, so the only requirement is to have the latest version of docker installed.
Environment has been verified to work on a Mac system. It should work on Windows with no issue but let me know if you
have any setup issues.

After installing Docker, run the following command in the root of the folder
```
docker-compose -f docker-compose.yml up
```

----
### Technical Stack
* PHP 7.4.9
* Lumen 8.0
---

### REST API
Basic REST API has been implemented to demonstrate the solution. The API documentation along with examples can be 
[accessed here](https://documenter.getpostman.com/view/42374/TVYF9zCq).

A postman collection & environment have been provided in the folder `docs`. Docker must be running for the endpoints 
to work.

---

### Unit Tests
Run the following command in your terminal to run all the unit tests
```
docker exec -t tt_service ./vendor/bin/codecept run 
```

 
