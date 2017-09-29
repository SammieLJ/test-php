DROP PROCEDURE IF EXISTS TotalUserViewsByYear;
DELIMITER //
CREATE PROCEDURE TotalUserViewsByYear(IN whatYear VARCHAR(5), IN Profile_ID INT)
 BEGIN
	SELECT
		(SELECT p1.profile_name FROM profiles p1 where p1.profile_id=p.profile_id) as ProfileName,
	  	sum(case when v.date between date_format(v.date, '%Y-01-01') and last_day(date_format(v.date, '%Y-01-01')) then v.views end) as `jan`,
		sum(case when v.date between date_format(v.date, '%Y-02-01') and last_day(date_format(v.date, '%Y-02-01')) then v.views end) as `feb`,
	   sum(case when v.date between date_format(v.date, '%Y-03-01') and last_day(date_format(v.date, '%Y-03-01')) then v.views end) as `mar`,
	   sum(case when v.date between date_format(v.date, '%Y-04-01') and last_day(date_format(v.date, '%Y-04-01')) then v.views end) as `apr`,
	   sum(case when v.date between date_format(v.date, '%Y-05-01') and last_day(date_format(v.date, '%Y-05-01')) then v.views end) as `may`,
	   sum(case when v.date between date_format(v.date, '%Y-06-01') and last_day(date_format(v.date, '%Y-06-01')) then v.views end) as `jun`,
	   sum(case when v.date between date_format(v.date, '%Y-07-01') and last_day(date_format(v.date, '%Y-07-01')) then v.views end) as `jul`,
	   sum(case when v.date between date_format(v.date, '%Y-08-01') and last_day(date_format(v.date, '%Y-08-01')) then v.views end) as `avg`,
	   sum(case when v.date between date_format(v.date, '%Y-09-01') and last_day(date_format(v.date, '%Y-09-01')) then v.views end) as `sep`,
	   sum(case when v.date between date_format(v.date, '%Y-10-01') and last_day(date_format(v.date, '%Y-10-01')) then v.views end) as `oct`,
	   sum(case when v.date between date_format(v.date, '%Y-11-01') and last_day(date_format(v.date, '%Y-11-01')) then v.views end) as `nov`,
	  	sum(case when v.date between date_format(v.date, '%Y-12-01') and last_day(date_format(v.date, '%Y-12-01')) then v.views end) as `dec`
	FROM profiles p
	LEFT JOIN views v
	ON p.profile_id=v.profile_id
	WHERE year(v.date) = whatYear AND p.profile_id=Profile_ID;
END //
DELIMITER ;