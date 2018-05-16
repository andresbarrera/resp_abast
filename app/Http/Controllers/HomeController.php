<?php
namespace ABASTV2\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller {
	/**
	 * Constructor de la clase.
	 */
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Método de inicialización.
	 * @return response.
	 */
	public function index() {
		return view('home.index');
	}
}