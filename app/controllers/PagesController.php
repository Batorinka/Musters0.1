<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class PagesController {
	
	private $qb;
	private $templates;
	
	public function __construct(QueryBuilder $qb, Engine $engine)
	{
		$this->qb = $qb;
		$this->templates = $engine;
	}
	
	public function getOverlooked()
	{
		$musters = $this->qb->getAll('musters');
		$objects = $this->qb->getAll('objects');
		$devices = $this->qb->getAll('devices');
		$overlookedMusters = [];
		foreach ($musters as &$muster) {
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
			$muster['is_overlooked'] = ($nextDate < Carbon::now()) ? 'overlooked' : '';
			$muster['is_overlooked_in_month'] =
				($nextDate >= Carbon::now()
					and $nextDate < Carbon::now()->addMonth()) ? 'overlooked_in_month' : '';
			$muster['next_date'] = $nextDate->format('Y-m-d');
			if ($nextDate < Carbon::now()->addMonth()) {
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
	
	public function pageNotFound()
	{
		echo $this->templates->render('404');
	}
	
	public function test()
	{
		phpinfo();die;
		
		$templateProcessor = new TemplateProcessor('Template.docx');
		$templateProcessor->setValue('name', 'John Doe');
		$templateProcessor->setValue(array('city', 'street'), array('Detroit', '12th Street'));
		$templateProcessor->saveAs('Result.docx');
		
		die;
		$tests = $this->qb->getAll('test');
		echo $this->templates->render('test', [
			'tests' => $tests
		]);
	}
	
	public function testAjax() {
		$this->qb->insert([
			'title' => $_POST['title'],
			'name' => $_POST['name']
		], 'test');
	}
}
