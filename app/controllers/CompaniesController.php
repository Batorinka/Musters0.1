<?php
namespace App\controllers;

use App\QueryBuilder;
use Carbon\Carbon;
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
					$company['quantity_of_objects']++;
				}
			}
		}
		
		echo $this->templates->render('/companies/companies', [
			'companies' => $companies
		]);
	}
	
	public function getCompany($vars)
	{
		$company = $this->qb->getOne($vars['id'], 'companies');
		$objects = $this->qb->getAllWhere('company_id', $vars['id'], 'objects');
		$contract_types = $this->qb->getAll('contract_types');

		echo $this->templates->render('/companies/company', [
			'company'        => $company,
			'objects'        => $objects,
			'contract_types' => $contract_types
		]);
	}
	
	public function addCompanyForm()
	{
		$contract_types = $this->qb->getAll('contract_types');
		echo $this->templates->render('/companies/addCompany', [
			'contract_types' => $contract_types
		]);
	}
	
	public function addCompany()
	{
		$name_sub = ($_POST['name_sub'] == '') ? 'Новое предприятие' : $_POST['name_sub'];
		$name_full = ($_POST['name_full'] == '') ? 'Новое предприятие' : $_POST['name_full'];
		$contract_number = ($_POST['contract_number'] == '') ? 'Новый номер' : $_POST['contract_number'];
		$contract_date = ($_POST['contract_date'] == 0) ? Carbon::now()->format('Y-m-d') : $_POST['contract_date'];
		$email = ($_POST['email'] == '') ? 'example@example.com' : $_POST['email'];

		$this->qb->insert([
			'name_sub'        => $name_sub,
			'name_full'       => $name_full,
			'contract_type'   => $_POST['contract_type'],
			'contract_number' => $contract_number,
			'contract_date'   => $contract_date,
			'email'           => $email
		], 'companies');
		flash()->success("Добавлено новое предприятие");
		header('Location: /');
	}
	
	public function updateCompanyForm($vars)
	{
		$company = $this->qb->getOne($vars['id'], 'companies');
		$contract_types = $this->qb->getAll('contract_types');
		
		echo $this->templates->render('/companies/updateCompany', [
			'company' => $company,
			'contract_types' => $contract_types
		]);
	}
	
	public function updateCompany($vars)
	{
		$name_sub = ($_POST['name_sub'] == '') ? 'Новое предприятие' : $_POST['name_sub'];
		$name_full = ($_POST['name_full'] == '') ? 'Новое предприятие' : $_POST['name_full'];
		$contract_number = ($_POST['contract_number'] == '') ? 'Новый номер' : $_POST['contract_number'];
		$contract_date = ($_POST['contract_date'] == 0) ? Carbon::now()->format('Y-m-d') : $_POST['contract_date'];
		$email = ($_POST['email'] == '') ? 'example@example.com' : $_POST['email'];

		$this->qb->update([
			'name_sub'        => $name_sub,
			'name_full'       => $name_full,
			'contract_type'   => $_POST['contract_type'],
			'contract_number' => $contract_number,
			'contract_date'   => $contract_date,
			'email'           => $email
		], $vars['id'], 'companies');
		flash()->success("Предприятие отредактировано");
		header('Location: /');
	}
	
	public function deleteCompany($vars)
	{
		$objects = $this->qb->getAllWhere('company_id', $vars['id'], 'objects');
		if (count($objects) == 0) {
			$this->qb->delete($vars['id'], 'companies');
			flash()->success("Предприятие удалено");
			header('Location: /catalogues/companies');
		} else {
			flash()->error("Предприятие не может быть удалено, пока существует хотя бы один объект, принадлежащий ему.");
			header('Location: /');
		}
	}
	
}
