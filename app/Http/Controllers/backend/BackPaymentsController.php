<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\FrontBaseController;


class BackPaymentsController extends FrontBaseController {

	function __construct()
	{
		parent::__construct();

		$this->beforeFilter('auth');
		$this->beforeFilter('admin');
		
		// View folder
		$this->view 	= 'backend.payments';

		// Validation messages
		$this->messages = array(
			'required'		=> 'Būtina užpildyti',
		);
	}

	public function index()
	{
		$entries = Payment::orderBy('updated_at', 'desc')->paginate(10);

		return View::make($this->view . '.index')
			->withEntries($entries);
	}

}