SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 6.5 hours


Solution
--------
Comments on your solution

I have used basic PHP functionality and basic SQL statements.

I have used console command "php bin/console report:profiles:yearly > output.txt" to catch data and to confirm that my result data comply COA.


Test (and edge) cases
---------------------
Test case1: how many (distinct) years are in this data?

Test case2: When I establish how many years are in the data, should I present as one table for all years or one table per year. I decided to go with last option.

Egde case: I decided that I would use single sql stmnt. to get years

Test case3: How many users are in the data?

Egde case2: Written single sql stmnt. that returns all users and their Id's. I would need latter in main loop to fill the data.

Test case4: How to summarize single entries, per 12 month per single user? I have written simple foreach PHP loop and simple SQL (with three params, id, year and month) that calculates for one row

Edge case3: I needed to calculate all data which is in range of first and last date of particular month and year and user ID. That only gives me data for one row.

Test case5: Form EC3 we can see that only one row is returned. In order to return data for all users, I needed FOR PHP loop that calls simple SQL statement and for every user ID (and calculates visits arrray for 12 moths)

Test case6: I needed clever way to fill and discard array (save memory) with table data. First outer FOR loop (in PHP) I needed for every year. So inside inner loop I have filled array. I was testing and degugging what structure $UsersYearReport needed to be, that Symfony $io->table would accept it without errors. Because Stored Proc. returns only one row, I needed to carefuly fill final array. This could be achived if SQL statement returned one whole table. There was no time to implement that way. 


How would I improve this Product?
---------------------------------
- I am out of ideas! :)

Technical Setup
===============

How to run app:
on Linux and Mac:
$> bin/console report:profiles:yearly

On Windows:
c:\wamp64\www\php-test>php bin/console report:profiles:yearly

Run cmd - write output from screen to file:
$> bin/console report:profiles:yearly > output.txt
c:\wamp64\www\php-test>php bin/console report:profiles:yearly > output.txt
