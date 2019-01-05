<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;
use App\Helper;

class MustersController
{
	private $templates;
	private $qb;
	private $helper;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Helper $helper)
	{
		$this->qb        = $qb;
		$this->templates = $engine;
		$this->helper    = $helper;
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
		
		foreach ($musters as &$muster) {
			$muster = $this->helper->addCSSClassAndNextDate($muster);
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
		$number = ($_POST['number'] == 0) ? '1' : $_POST['number'];
		$last_date = ($_POST['last_date'] == 0) ? Carbon::now()->format('Y-m-d') : $_POST['last_date'];
		$interval_of_muster = ($_POST['interval_of_muster'] == 0) ? '0' : $_POST['interval_of_muster'];
		
		$this->qb->insert([
			'object_id' => $_POST['object_id'],
			'device_id' => $_POST['device_id'],
			'number' => $number,
			'last_date' => $last_date,
			'interval_of_muster' => $interval_of_muster
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
	
	public function getOverlooked()
	{
		$musters = $this->qb->getAll('musters');
		$objects = $this->qb->getAll('objects');
		$devices = $this->qb->getAll('devices');
		$overlookedMusters = [];
		foreach ($musters as &$muster) {
			$muster = $this->helper->addCSSClassAndNextDate($muster);
			if ($muster['next_date'] < Carbon::now()->addMonth()) {
				array_push($overlookedMusters, $muster);
			}
		}
		
		echo $this->templates->render('overlooked', [
			'musters' => $overlookedMusters,
			'objects' => $objects,
			'devices' => $devices
		]);
		return $overlookedMusters;
	}
}
