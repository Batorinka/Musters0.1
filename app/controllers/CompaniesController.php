<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use App\Helper;

class CompaniesController {
	
	private $templates;
	private $qb;
	private $helper;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Helper $helper)
	{
		$this->qb        = $qb;
		$this->templates = $engine;
		$this->helper    = $helper;
	}
	
	public function getCompanies()
	{
		$companies = $this->qb->getAll('companies');
		$objects = $this->qb->getAll('objects');
		
		foreach ($companies as &$company) {
			$company['quantity_of_objects'] = 0;
			foreach ($objects as $object) {
				if ($company['id'] == $object['company_id']) {
					$company['quantity_of_objects'] += 1;
				}
			}
		}
		
		echo $this->templates->render('/companies/companies', [
			'companies' => $companies
		]);
	}
	
	public function getObject($vars)
	{
		$object = $this->qb->getOne($vars['id'], 'objects');
		$musters = $this->qb->getAllWhere('object_id', $vars['id'],'musters');
		$devices = $this->qb->getAll('devices');
		
		foreach ($musters as &$muster) {
			$muster = $this->helper->addCSSClassAndNextDate($muster);
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
