<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;
use PHPStamp\Document\WordDocument;
use PHPStamp\Templator;

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
//		d($overlookedMusters);die();
		$cachePath = 'cache/';
		$templator = new Templator($cachePath); // опционально можно задать свой формат скобочек
		// Для того чтобы каждый раз шаблон генерировался заново:
		// $templator->debug = true;
		
		$templator->trackDocument = true;
		$documentPath = 'Template.docx';
		$document = new WordDocument($documentPath);
		
		$values = array(
			'library' => 'PHPStamp 0.1',
			'simpleValue' => 'I am simple value',
			'nested' => array(
				'firstValue' => 'First child value',
				'secondValue' => 'Second child value'
			),
			'header' => 'test of a table row',
			'students' => array(
				array('id' => 1, 'name' => 'Student 1', 'mark' => '10'),
				array('id' => 2, 'name' => 'Student 2', 'mark' => '4'),
				array('id' => 3, 'name' => 'Student 3', 'mark' => '7')
			),
			'maxMark' => 10,
			'todo' => array(
				'TODO 1',
				'TODO 2',
				'TODO 3'
			),
			'over' => $overlookedMusters
		);
		$result = $templator->render($document, $values);
		$result->download('Акт проверки.docx');

	}
	
}
