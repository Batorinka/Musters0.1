<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 05.01.19
 * Time: 21:05
 */

namespace App;

use Carbon\Carbon;


class Helper
{
	public function addCSSClassAndNextDate ($musters){
		foreach ($musters as &$muster) {
			//с помощью карбон расчитывается дата следующей поверки
			//  (last_date + interval_of_muster)
			//is_overlooked - содержит css класс отображающий просроченные поверки
			// или поверки, которые будут просрочены в течение месяца
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
			$muster['is_overlooked'] = ($nextDate < Carbon::now()) ? 'overlooked' : '';
			$muster['is_overlooked_in_month'] =
				($nextDate >= Carbon::now()
					and $nextDate < Carbon::now()->addMonth()) ? 'overlooked_in_month' : '';
			$muster['next_date'] = $nextDate->format('Y-m-d');
		}
		return $musters;
	}

	public function countMusters ($musters, $objects) {
		foreach ($objects as &$object) {
			$count_musters = 0;
			foreach ($musters as $muster) {
				if ($object['id'] == $muster['object_id']) {
					$count_musters++;
				}
			}
			$object['count_musters'] = $count_musters;
		}
		return $objects;
	}

}













