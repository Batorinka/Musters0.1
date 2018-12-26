<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;
use Carbon\Carbon;

class DevicesController {
  
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
			$lastDate = Carbon::parse($muster['last_date']);
			$nextDate = $lastDate->addYears($muster['interval_of_muster']);
			$muster['is_overlooked'] = ($nextDate < Carbon::now()) ? 'overlooked' : '';
			$muster['is_overlooked_in_month'] =
				($nextDate >= Carbon::now()
					and $nextDate < Carbon::now()->addMonth()) ? 'overlooked_in_month' : '';
			$muster['next_date'] = $nextDate->format('Y-m-d');
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
