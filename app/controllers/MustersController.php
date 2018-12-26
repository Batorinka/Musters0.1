<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;
use Carbon\Carbon;

class MustersController
{
	
	private $templates;
	private $auth;
	private $qb;
	private $carbon;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth, Carbon $carbon)
	{
		$this->templates = $engine;
		$this->auth = $auth;
		$this->qb = $qb;
		$this->carbon = $carbon;
	}
	
	public function getMusters()
	{
		$musters = $this->qb->getAll('musters');
		$objects = $this->qb->getAll('objects');
		$devices = $this->qb->getAll('devices');
		
		foreach ($musters as &$muster) {
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
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
		$object = $this->qb->getOne($_POST['object_id'], 'objects');
		$this->qb->update([
			'quantity_of_musters' => $object['quantity_of_musters'] + 1
		], $_POST['object_id'], 'objects');
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
		$muster = $this->qb->getOne($vars['id'], 'musters');
		$object = $this->qb->getOne($muster['object_id'], 'objects');
		$this->qb->update([
			'quantity_of_musters' => $object['quantity_of_musters'] - 1
		], $muster['object_id'], 'objects');
		
		$object = $this->qb->getOne($_POST['object_id'], 'objects');
		$this->qb->update([
			'quantity_of_musters' => $object['quantity_of_musters'] + 1
		], $_POST['object_id'], 'objects');
		
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
		$muster = $this->qb->getOne($vars['id'], 'musters');
		$object = $this->qb->getOne($muster['object_id'], 'objects');
		$this->qb->update([
			'quantity_of_musters' => $object['quantity_of_musters'] - 1
		], $muster['object_id'], 'objects');
		$this->qb->delete($vars['id'], 'musters');
		flash()->success("Поверка удалена");
		header('Location: /');
	}
}
