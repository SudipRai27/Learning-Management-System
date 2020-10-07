<?php

namespace App;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;	

class DayAndDateTime 
{
    public function getDays()
    {	
    	return [
		'1' => 'Sunday',
		'2' => 'Monday',
		'3' => 'Tuesday',
		'4' => 'Wednesday',
		'5' => 'Thursday',
		'6' => 'Friday',
		'7' => 'Saturday'
		];
    }

    public function returnDayName($id = 0)
    {	
    	$days = $this->getDays();
    	if(array_key_exists($id, $days))
    	{
    		return $days[$id];
    	}
    	return "Day Not Found";
    }

    public function checkTimeRange($start_time, $end_time, $time_to_check)
    {
    	// This function work in the simple case that all time periods are on the same day which we want right now but if you're checking if 10pm is greater than 8pm and less than 2am, this code will fail.//
    	/*echo $start_time;
    	echo $end_time;
    	echo $time_to_check;*/
		
		$date1 = DateTime::createFromFormat('H:i a', $time_to_check);
		$date2 = DateTime::createFromFormat('H:i a', $start_time);
		$date3 = DateTime::createFromFormat('H:i a', $end_time);
		if ($date1 >= $date2 && $date1 <= $date3)
		{
			return true;
		}
		else
		{
			return false;
		}		
    }

    public function parseTimein24HourFormat($time)
    {
    	$time  = date("H:i", strtotime($time));
    	return strtotime($time);	
    }

    public function parseTimein12HourFormat($time)
    {
    	$time  = date("h:i A", strtotime($time));
    	return $time;
    }

    public function checkTimeRanges($startTime, $endTime, $chkStartTime, $chkEndTime)
	{		

	    $startTime = strtotime($startTime);
		$endTime   = strtotime($endTime);

		$chkStartTime = strtotime($chkStartTime);
		$chkEndTime   = strtotime($chkEndTime);
		
		$data = [];
		if($chkStartTime > $startTime && $chkEndTime < $endTime)
		{	#-> Check time is in between start and end time
			return true;
			//echo "1 Time is in between start and end time";
		}elseif(($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime))
		{	#-> Check start or end time is in between start and end time
			return true;
			//echo "2 ChK start or end Time is in between start and end time";
		}elseif($chkStartTime==$startTime || $chkEndTime==$endTime)
		{	#-> Check start or end time is at the border of start and end time
			return true;
			//echo "3 ChK start or end Time is at the border of start and end time";
		}elseif($startTime > $chkStartTime && $endTime < $chkEndTime)
		{	#-> start and end time is in between  the check start and end time.
			return true;
			//echo "4 start and end Time is overlapping  chk start and end time";
		}
		return false;
	}

	public function formatDateTime($date)
	{
		$dateandTime = explode(" ", $date);
		$date = explode("/", $dateandTime[0]);
		$newDate = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $dateandTime[1] . ' ' . $dateandTime[2];
		return $newDate;		
	}

	public function formatDateTimewithSlashes($date)
	{
		$dateandTime = explode(" ", $date);
		$date = explode("-", $dateandTime[0]);
		$newDate = $date[1] . '-' . $date[2] . '-' . $date[0] . ' ' . $dateandTime[1] . ' ' . $dateandTime[2];
		return $newDate;
	}

	public function changeDateFormat($date)
	{
		return date("M-d-Y h:i A", strtotime($date));
	}

}


