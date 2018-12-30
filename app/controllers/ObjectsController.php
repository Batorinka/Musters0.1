<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;

class ObjectsController {
	
	private $templates;
	private $qb;
	
	public function __construct(QueryBuilder $qb, Engine $engine)
	{
		$this->qb = $qb;
		$this->templates = $engine;
	}
	
	public function getObjects()
	{
		$objects = $this->qb->getAll('objects');
		
		echo $this->templates->render('/objects/objects', [
			'objects' => $objects
		]);
	}
	
	public function getObject($vars)
	{
		$object = $this->qb->getOne($vars['id'], 'objects');
		$musters = $this->qb->getAllWhere('object_id', $vars['id'],'musters');
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
		
		echo $this->templates->render('/objects/object', [
			'object' => $object,
			'musters' => $musters,
			'devices' => $devices,
		]);
	}
	
	public function addObjectForm()
	{
		echo $this->templates->render('/objects/addObject');
	}
	
	public function addObject()
	{
		$this->qb->insert([
			'name' => $_POST['name'],
			'email' => $_POST['email']
		], 'objects');
		flash()->success("Добавлен новый объект");
		header('Location: /');
	}
	
	public function updateObjectForm($vars)
	{
		$object = $this->qb->getOne($vars['id'], 'objects');
		
		echo $this->templates->render('/objects/updateObject', [
			'object' => $object
		]);
	}
	
	public function updateObject($vars)
	{
		$this->qb->update([
			'name' => $_POST['name'],
			'email' => $_POST['email']
		], $vars['id'], 'objects');
		flash()->success("Объект отредактирован");
		header('Location: /');
	}
	
	public function deleteObject($vars)
	{
		$musters = $this->qb->getAllWhere('object_id', $vars['id'], 'musters');
		if (count($musters) == 0) {
			$this->qb->delete($vars['id'], 'objects');
			flash()->success("Объект удален");
			header('Location: /catalogues/objects');
		} else {
			flash()->error("Объект не может быть удален, пока существует хотя бы одна поверка, принадлежащая ему.");
			header('Location: /');
		}
	}
	
}
