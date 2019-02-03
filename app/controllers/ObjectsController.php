<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;
use App\Helper;

class ObjectsController {
	
	private $templates;
	private $qb;
	private $helper;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Helper $helper)
	{
		$this->qb        = $qb;
		$this->templates = $engine;
		$this->helper    = $helper;
	}
	
	public function getObjects()
	{
		$objects = $this->qb->getObjects();
		$companies = $this->qb->getAll('companies');
		
		echo $this->templates->render('/objects/objects', [
			'objects' => $objects,
			'companies' => $companies
		]);
	}
	
	public function getObject($vars)
	{
		$object = $this->qb->getObjectsWhere('objects.id', $vars['id']);
		$musters = $this->helper->addCSSClassAndNextDate($this->qb->getMustersWhere('object_id', $vars['id']));
//		d($object);die();
		echo $this->templates->render('/objects/object', [
			'object' => $object[0],
			'musters' => $musters
		]);
	}
	
	public function addObjectForm()
	{
		$companies = $this->qb->getAll('companies');
		echo $this->templates->render('/objects/addObject', [
			'companies' => $companies
		]);
	}
	
	public function addObject()
	{
		$this->qb->insert([
			'name' => $_POST['name'],
			'company_id' => $_POST['company_id']
		], 'objects');
		flash()->success("Добавлен новый объект");
		header('Location: /');
	}
	
	public function updateObjectForm($vars)
	{
		$object = $this->qb->getOne($vars['id'], 'objects');
		$companies = $this->qb->getAll('companies');
		
		echo $this->templates->render('/objects/updateObject', [
			'object' => $object,
			'companies' => $companies
		]);
	}
	
	public function updateObject($vars)
	{
		$this->qb->update([
			'name' => $_POST['name'],
			'company_id' => $_POST['company_id']
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
