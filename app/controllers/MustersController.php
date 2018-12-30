<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;

class MustersController
{
	private $templates;
	private $qb;
	
	public function __construct(QueryBuilder $qb, Engine $engine)
	{
		$this->templates = $engine;
		$this->qb = $qb;
	}
	
	public function getMusters()
	{
		$musters = $this->qb->getAll('musters');
		$devices = $this->qb->getAll('devices');
		$objects = $this->qb->getAll('objects');
		
		//Подсчет колличества поверок для каждого объекта
		foreach ($objects as &$object) {
			$object['quantity_of_musters'] = 0;
			foreach ($musters as $muster) {
				if ($object['id'] == $muster['object_id']) {
					$object['quantity_of_musters'] += 1;
				}
			}
		}
		
		//с помощью карбон расчитывается дата следующей поверки
		//  (last_date + interval_of_musters)
		foreach ($musters as &$muster) {
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
			//is_overlooked - содержит css класс отображающий просроченные поверки
			// или поверки, которые будут просрочены в течение месяца
			$muster['is_overlooked'] = ($nextDate < Carbon::now()) ? 'overlooked' : '';
			$muster['is_overlooked_in_month'] =
				($nextDate >= Carbon::now()
					and $nextDate < Carbon::now()->addMonth()) ? 'overlooked_in_month' : '';
			$muster['next_date'] = $nextDate->format('Y-m-d');
		}
		echo $this->templates->render('/musters/musters', [
			'musters' => $musters,
			'objects' => $objects,
			'devices' => $devices
		]);
	}
	
	public function addMusterForm()
	{
		$objects = $this->qb->getAll('objects');
		$devices = $this->qb->getAll('devices');
		echo $this->templates->render('/musters/addMuster', [
			'objects' => $objects,
			'devices' => $devices
		]);
	}
	
	public function addMuster()
	{
		$this->qb->insert([
			'object_id' => $_POST['object_id'],
			'device_id' => $_POST['device_id'],
			'number' => $_POST['number'],
			'last_date' => $_POST['last_date'],
			'interval_of_muster' => $_POST['interval_of_muster']
		], 'musters');
		
		flash()->success('Добавлена новая поверка');
		header('Location: /');
	}
	
	public function updateMusterForm($vars)
	{
		$muster = $this->qb->getOne($vars['id'], 'musters');
		$objects = $this->qb->getAll('objects');
		$devices = $this->qb->getAll('devices');
		
		echo $this->templates->render('/musters/updateMuster', [
			'muster' => $muster,
			'objects' => $objects,
			'devices' => $devices
		]);
	}
	
	public function updateMuster($vars)
	{
		$this->qb->update([
			'object_id' => $_POST['object_id'],
			'device_id' => $_POST['device_id'],
			'number' => $_POST['number'],
			'last_date' => $_POST['last_date'],
			'interval_of_muster' => $_POST['interval_of_muster']
		], $vars['id'], 'musters');
		
		flash()->success("Поверка отредактирована");
		header('Location: /');
	}
	
	public function deleteMuster($vars)
	{
		$this->qb->delete($vars['id'], 'musters');
		
		flash()->success("Поверка удалена");
		header('Location: /');
	}
}
