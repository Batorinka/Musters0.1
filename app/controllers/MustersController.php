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
		$musters = $this->helper->addCSSClassAndNextDate($this->qb->getMusters());
		$objects = $this->helper->countMusters($musters, $this->qb->getObjects());

		echo $this->templates->render('/musters/musters', [
			'title'   => 'Поверки',
			'musters' => $musters,
			'objects' => $objects
		]);
	}
	
	public function addMusterForm()
	{
		$objects = $this->qb->getAll('objects');
		$companies = $this->qb->getAll('companies');
		$devices = $this->qb->getAll('devices');
		echo $this->templates->render('/musters/addMuster', [
			'objects' => $objects,
			'companies' => $companies,
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
		$companies = $this->qb->getAll('companies');
		$devices = $this->qb->getAll('devices');
		
		echo $this->templates->render('/musters/updateMuster', [
			'muster' => $muster,
			'objects' => $objects,
			'companies' => $companies,
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
		$musters = $this->helper->addCSSClassAndNextDate($this->qb->getMusters());
		$overlookedMusters = [];
		foreach ($musters as &$muster) {
			if ($muster['next_date'] < Carbon::now()->addMonth()) {
				array_push($overlookedMusters, $muster);
			}
		}
		$objects = $this->helper->countMusters($overlookedMusters, $this->qb->getObjects());
		echo $this->templates->render('musters/musters', [
			'title'   => 'Просроченные поверки',
			'musters' => $overlookedMusters,
			'objects' => $objects
		]);
	}
}
