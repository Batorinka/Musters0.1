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
	private $auth;
	
	public function __construct(QueryBuilder $qb, Engine $engine)
	{
		$this->qb = $qb;
		$this->templates = $engine;
	}
	
	public function pageNotFound()
	{
		echo $this->templates->render('404');
	}
	
	public function test()
	{
		$object_id = 30;
		$object = $this->qb->getOne($object_id, 'objects');
		$musters = $this->qb->getAllWhere('object_id', $object_id, 'musters');
		$devices = $this->qb->getAll('devices');
		$types = $this->qb->getAll('types');
		$curator['email'] = $_SESSION['auth_email'];
		
		foreach ($musters as &$muster) {
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
			$muster['next_date'] = $nextDate->format('Y-m-d');
			foreach ($devices as $device) {
				if ($device['id'] == $muster['device_id']) {
					$muster['device'] = $device['name'];
					foreach ($types as $type) {
						if ($type['id'] == $device['type_id']) {
							$muster['type'] = $type['name'];
						}
					}
				}
			}
		}
		
		$cachePath = 'cache/';
		$templator = new Templator($cachePath); // опционально можно задать свой формат скобочек
		// Для того чтобы каждый раз шаблон генерировался заново:
		// $templator->debug = true;
		
		$templator->trackDocument = true;
		$documentPath = 'Template.docx';
		$document = new WordDocument($documentPath);
		
		$values = array(
			'object' => $object,
			'musters' => $musters,
			'curator' => $curator
		);
		
		$fileName = substr($object['name'], 0, 20);
		$result = $templator->render($document, $values);
		$result->download("$fileName.docx");
	}
	
}
