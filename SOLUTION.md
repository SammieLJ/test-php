SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 6.5 hours


Solution
--------
Comments on your solution

I have created stored procedure named TotalUserViewsByYear and TotalUserViewsByYear.sql file (destroy and create this stor. procedure). Use any sql tool to import and create stor. proc. 
TotalUserViewsByYear stor. procedure is used in ReportYearlyCommand.php and generates (summarize) single row with 12 months and user.

I have decided that best cource of action would be to use stor. proc. TotalUserViewsByYear for single user row data and in PHP code and I have combined with usage of two FOR statements. First FOR statement creates new table for every year. Second statement fills (sums) data for every user at single year. I have replaced null values with 'n/a' in PHP rather than in SQL. Because I would need to create SQL return function and use ISNULL SQL cmd. It was not possible to do in sotred procedure. Also PostgreSQL and other SQL's don't support ISNULL SQL cmd.

I have used console command "php bin/console report:profiles:yearly > output.txt" to catch data and to confirm that my result data comply COA.


Test (and edge) cases
---------------------
Test case1: how many (distinct) years are in this data?

Test case2: When I establish how many years are in the data, should I present as one table for all years or one table per year. I decided to go with last option.

Egde case: I decided that I would use single sql stmnt. to get years

Test case3: How many users are in the data?

Egde case2: Written single sql stmnt. that returns all users and their Id's. I would need latter in main loop to fill the data.

Test case4: How to summarize single entries, per 12 month per single user? I have written custom stored procedure in single select stmnt., that calculates for one row

Edge case3: I needed to calculate all data which is in range of first and last date of particular month and year and user ID. That only gives me data for one row.

Test case5: Form EC3 we can see that only one row is returned. In order to return data for all users, I needed FOR PHP loop that calls Stored Proc. for every user ID (and calculates visits for 12 moths)

Test case6: I needed clever way to fill and discard array (save memory) with table data. First outer FOR loop (in PHP) I needed for every year. So inside inner loop I have filled array. I was testing and degugging what structure $UsersYearReport needed to be, that Symfony $io->table would accept it without errors. Because Stored Proc. returns only one row, I needed to carefuly fill final array. This could be achived if SQL statement returned one whole table. There was no time to implement that way. 

Test case7: added 'n/a' where value was null in PHP through all arrays elements and called it $UsersYearReportFinal. I have used PHP static func. replace_null_with_empty_string (added to ReportYearlyCommand class)


How would I improve this Product?
---------------------------------
- No to use parameter injection. :) I'm new to Symfony frmwrk, I can do better. Time was of constraint.
- User Symfony way of executing Stored Procedures and ofcourse to add parameters
- Could write SQL function that loops (calling Stored Procedure) and sends filled table, so that only one FOR PHP loop is needed (for years)

How to run
==========
on Linux and Mac:
$> bin/console report:profiles:yearly

On Windows:
c:\wamp64\www\php-test>php bin/console report:profiles:yearly

Run to output from screen to file:
c:\wamp64\www\php-test>php bin/console report:profiles:yearly > output.txt

Technical Setup
===============
I suggest: mysql -uroot -p < TotalUserViewsByYear.sql