<?php
namespace App\Controller;

use App\Controller\AppController;

class AdminController extends AppController
{

	public function index()
	{
		$this->paginate = [
			'contain' => ['Roles']
		];

	}
	public function patientSearch()
	{
		$reqData = ['value'=>$this->request->query['value']];
		$this->loadComponent('PeopleHub', ['liveMode' => 1, 'throwErrorMode' => false]);
		$response = $this->PeopleHub->searchPatient($reqData);
		$this->set('response', 	$response->data);
	}
	public function isAuthorized($user){

		if(in_array($user['role']['label'], ['Admin']))
		return true;
		return parent::isAuthorized($user);
	}

}
