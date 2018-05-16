<?php
namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\PrsPersonaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ABASTV2\Mail\CambioPass;
use Carbon\Carbon;
use Validator;
use Mail;

class ForgotPasswordController extends Controller {
	/**
	 * Constructor de la clase
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 
	 * Despliega la vista del formulario de cambio de contraseña
	 * @return response 
	 */
	public function index() {
		return (Auth::check()) ? redirect('home') : view('login.forgot-password');
	}


	/**
	* 
	* Realiza el cambio del password por uno provisorio
	* @param request
	* @return response
	*/
	public function update(Request $request) {
		$email = $request->email;
		$id_usuario = SysUsuarioModel::whereNull('sys_usuarios.deleted_at')
								->where('sys_usuarios.email_usuario', $email)
								->select('sys_usuarios.id_usuario')
								->first();
		$sys_usuario = new SysUsuarioModel;						
		$usuario = SysUsuarioModel::find($id_usuario['id_usuario']);
		$inputs = $this->getInputs($request->all());
		if ($this->validateForms($inputs) === TRUE) {
			$id = $usuario->id_usuario;
			$pass = rand(100000 , 999999);
			$password= bcrypt($pass);
			$fecha = Carbon::now();
			$estado_registro = 'habilitado';
			$resultado = $sys_usuario->changePassword($id, $password, $fecha, $estado_registro);
			if ($resultado) {
				//ENVIAR CORREO CON USUARIO Y CONTRASEÑA  DEFINITIVO
				$this->enviaCambioPassword($email, $pass);
				return redirect('/')->with(array('mensaje' => 'La nueva contraseña ha sido enviada a su e-mail.'));
			} else {
				return redirect('forgot-password')->with(array('mensaje' => 'Ha ocurrido un error. Por favor, intente nuevamente.'));
			}
		} else {
			return redirect('forgot-password')->withErrors($this->validateForms($inputs))->withInput();
		}
	}

	/**
	 * Método que valida las entradas en el formulariio
	 * @param $inputs Array. Entradas del formulario
	 * @return $validation Array. Errores de validación
	 */
	private function validateForms($inputs = array()) {
		$rules = array('email' => 'required|email|exists:sys_usuarios,email_usuario');
		$messages = array(
			'email.required'	=> 'Por favor, ingrese su e-mail', 
			'email.email'		=> 'El e-mail ingresado es incorrecto', 
			'email.exists'		=> 'El e-mail ingresado no se encuentra en los registros'
		);
		$validation = Validator::make($inputs, $rules, $messages);
		return ($validation->fails()) ? $validation : TRUE;
	}

	/**
	 * Método privado que obtiene los inputs del formulario
	 * @param $inputs Array. Entradas del formulario
	 * @return $inputs Array. Valores del formulario
	 */
	private function getInputs($inputs = array()) {
		foreach ($inputs as $key => $val) {
			$inputs[$key] = $val;
		}
		return $inputs;
	}

	/**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    public function enviaCambioPassword($receptor, $password)
    {
        $content = [
            'title'=> 'Cambio de contraseña.', 
            'body'=> 'Ud. ha pedido un cambio de contraseña, su nueva contraseña es ',
            'resto_body' => 'Si ud. no ha pedido un cambio de contraseña pongase en contacto con soporte soporte@prueba.cl'
            ];
        Mail::to($receptor)->send(new CambioPass($content, $password));
        return true;

    }
}