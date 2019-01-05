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
		
		foreach ($devices as &$device) {
			$device['quantity_of_musters'] = 0;
			foreach ($musters as $muster) {
				if ($device['id'] == $muster['device_id']) {
					$device['quantity_of_musters'] += 1;
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
		$musters = $this->qb->getAllWhere('device_id', $vars['id'],'musters');
		$objects = $this->qb->getAll('objects');
		
		foreach ($musters as &$muster) {
			$muster = $this->helper->addCSSClassAndNextDate($muster);
		}
		
		echo $this->templates->render('/devices/device', [
			'device' => $device,
			'musters' => $musters,
			'objects' => $objects,
		]);
	}
	
	public function addDeviceForm()
	{
		echo $this->templates->render('/devices/addDevice');
	}
	
	public function addDevice()
	{
		$this->qb->insert([
			'name' => $_POST['name']
		], 'devices');
		flash()->success("Добавлен новый прибор");
		header('Location: /');
	}
	
	public function updateDeviceForm($vars)
	{
		$device = $this->qb->getOne($vars['id'], 'devices');
		
		echo $this->templates->render('/devices/updateDevice', [
			'device' => $device
		]);
	}
	
	public function updateDevice($vars)
	{
		$this->qb->update([
			'name' => $_POST['name']
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
