<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Carbon\Carbon;
use App\Helper;

class DevicesController {
  
	private $templates;
	private $qb;
	private $helper;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Helper $helper)
	{
		$this->qb        = $qb;
		$this->templates = $engine;
		$this->helper    = $helper;
	}
	
	public function getDevices()
	{
		$devices = $this->qb->getAll('devices');
		$musters = $this->qb->getAll('musters');
		$types = $this->qb->getAll('types');
		
		foreach ($devices as &$device) {
			$device['quantity_of_musters'] = 0;
			foreach ($musters as $muster) {
				if ($device['id'] == $muster['device_id']) {
					$device['quantity_of_musters'] += 1;
				}
			}
			foreach ($types as $type) {
				if ($type['id'] == $device['type_id']) {
					$device['type'] = $type['name'];
				}
			}
		}
		
		echo $this->templates->render('/devices/devices', [
			'devices' => $devices
		]);
	}
	
	public function getDevice($vars)
	{
		$device = $this->qb->getOne($vars['id'], 'devices');
		$musters = $this->helper->addCSSClassAndNextDate(
			$this->qb->getMustersWhere('device_id', $vars['id'])
		);
		$objects = $this->qb->getObjects();
		$type = $this->qb->getOneWhere('id', $device['type_id'],'types');
		

//		d($type, $device);die();
		echo $this->templates->render('/devices/device', [
			'device' => $device,
			'musters' => $musters,
			'objects' => $objects,
			'type' => $type
		]);
	}
	
	public function addDeviceForm()
	{
		$types = $this->qb->getAll('types');
		echo $this->templates->render('/devices/addDevice', [
			'types' => $types
		]);
	}
	
	public function addDevice()
	{
		$this->qb->insert([
			'name' => $_POST['name'],
			'type_id' => $_POST['type_id']
		], 'devices');
		flash()->success("Добавлен новый прибор");
		header('Location: /');
	}
	
	public function updateDeviceForm($vars)
	{
		$device = $this->qb->getOne($vars['id'], 'devices');
		$types = $this->qb->getAll('types');
		
		echo $this->templates->render('/devices/updateDevice', [
			'device' => $device,
			'types' => $types
		]);
	}
	
	public function updateDevice($vars)
	{
		$this->qb->update([
			'name' => $_POST['name'],
			'type_id' => $_POST['type_id']
		], $vars['id'], 'devices');
		flash()->success("Прибор отредактирован");
		header('Location: /');
	}
	
	public function deleteDevice($vars)
	{
		$musters = $this->qb->getAllWhere('device_id', $vars['id'], 'musters');
		if (count($musters) == 0) {
			$this->qb->delete($vars['id'], 'devices');
			flash()->success("Прибор удален");
			header('Location: /catalogues/devices');
		} else {
			flash()->error("Прибор не может быть удален, пока существует хотя бы одна поверка, принадлежащая ему.");
			header('Location: /');
		}
	}

}
