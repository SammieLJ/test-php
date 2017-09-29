SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 6.5 hours


Solution
--------
Comments on your solution

I have created stored procedure named TotalUserViewsByYear. I have created TotalUserViewsByYear.sql file, to create this stor. procedure. TotalUserViewsByYear stor. procedure is used in ReportYearlyCommand.php

I have decided that best cource of action would be to use stor. proc. TotalUserViewsByYear for single user row data and in PHP I have combined with usage of two for statements. First for statement creates new table for every year. Second statement fills (sums) data for every user. I have replaced null values with 'n/a' in PHP rather than in SQL. Because I would need to create SQL return function and use ISNULL SQL cmd. It was not possible to do in sotred procedure. And PostgreSQL and other SQL's don't support MySQL ISNULL SQL cmd, that replaces null values.

I have used console command "php bin/console report:profiles:yearly > error.txt"s
