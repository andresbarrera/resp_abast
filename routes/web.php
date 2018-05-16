<?php
Route::get('/', function () {
	if (Auth::check()) {
		return redirect('home');
	} else {
		return view('login.index');
	}
});

/***************************************************LOGIN***************************************************/
Route::get('login', 'LoginController@getIndex')->name('login');
Route::post('login', 'LoginController@postIndex');
Route::get('forgot-password', 'ForgotPasswordController@index');
Route::post('forgot-password', 'ForgotPasswordController@update');
Route::get('verify-password', 'LoginController@getVerifyPassword');
Route::post('verify-password', 'LoginController@postVerifyPassword');
Route::get('expired-password', 'LoginController@getExpiredPassword');
Route::post('expired-password', 'LoginController@postExpiredPassword');
Route::get('logout', 'LoginController@logout')->name('logout');

/***************************************************HOME****************************************************/
Route::get('home', 'HomeController@index');

/**********************************************PERFILES**************************************/
Route::post('perfil/existePerfil', 'SysPerfilController@existePerfil');
Route::resource('perfil', 'SysPerfilController');
/*********************************************USUARIOS*******************************/
Route::post('usuarios/existeUsuario', 'SysUsuarioController@existeUsuario');
Route::post('usuarios/existeMail', 'SysUsuarioController@existeMail');
Route::resource('usuarios','SysUsuarioController');

/*********************************************FAMILIAS********************************/
Route::post('tiposarticulos/existeTipoArticulo', 'TipoArticuloController@existeTipoArticulo');
Route::resource('tiposarticulos', 'TipoArticuloController');

/********************************************ARTICULOS********************************/
Route::post('articulo/existeArticulo', 'ArticuloController@existeArticulo');
Route::resource('articulo', 'ArticuloController');

/********************************************AREAS********************************/
Route::post('areas/existeArea', 'AreAreaController@existeArea');
Route::resource('areas', 'AreAreaController');

/**********************************BLOQUEAR USUARIO********************************/
Route::post('usuarios/block/{id}',array('uses' => 'SysUsuarioController@block', 'as' => 'block'));
/**********************************TIPOS DE LICENCIAS***********************************/
Route::post('tiposlicencias/existeTipoLicencia', 'StaTipoLicenciaController@existeTipoLicencia');
Route::resource('tiposlicencias', 'StaTipoLicenciaController');
/**********************************TIPOS DE VEHICULOS***********************************/
Route::post('tiposvehiculos/existeTipoVehiculo', 'StaTipoVehiculoController@existeTipoVehiculo');
Route::resource('tiposvehiculos', 'StaTipoVehiculoController');
/**********************************CENTROS DE COSTOS***********************************/
Route::post('centroscostos/existeCentroCosto', 'CecCentroCostoController@existeCentroCosto');
Route::resource('centroscostos', 'CecCentroCostoController');
/**********************************TIPOS DE DOCUMENTOS***********************************/
Route::post('tiposdocumentos/existeTipoDocumento', 'DocTipoDocumentoController@existeTipoDocumento');
Route::resource('tiposdocumentos', 'DocTipoDocumentoController');

/****************************DOCUMENTOS***************************************/
Route::post('storage/create/', 'PrsPersonaController@save');
Route::post('storage/create/doc', 'DocManualController@save');
Route::get('storage/files/{archivo}', function ($archivo) {
     $archivo = Crypt::decrypt($archivo);
     $storage_path = storage_path().'/files/';
     $url = $storage_path.$archivo;
     //verificamos si el archivo existe y lo retornamos
     if (Storage::exists($archivo))
     {
       return response()->download($url);
     }
     //si no se encuentra lanzamos un error 404.
     abort(404);
 
});
Route::get('storage/files/prs/{archivo}', function ($archivo) {
     $archivo = Crypt::decrypt($archivo);
     $storage_path = storage_path().'/files/prs/';
     $url = $storage_path.$archivo;
     //verificamos si el archivo existe y lo retornamos
     if (Storage::disk('prs')->exists($archivo))
     {
       return response()->download($url);
     }
     //si no se encuentra lanzamos un error 404.
     abort(404);
 
});
Route::get('storage/files/doc/{archivo}', function ($archivo) {
     $archivo = Crypt::decrypt($archivo);
     $storage_path = storage_path().'/files/doc/';
     $url = $storage_path.$archivo;
     //verificamos si el archivo existe y lo retornamos
     if (Storage::disk('doc')->exists($archivo))
     {
       return response()->download($url);
     }
     //si no se encuentra lanzamos un error 404.
     abort(404);
 
});
/*********************************************PERSONAS*******************************/
Route::post('personas/existePersona', 'PrsPersonaController@existePersona');
Route::resource('personas','PrsPersonaController');
/*********************************************ACREDITACION*******************************/
Route::get('acreditacion/{persona}', 'AcrAcreditacionController@create');
Route::get('acreditacion/show/{persona}', 'AcrAcreditacionController@show');
Route::resource('acreditacion','AcrAcreditacionController');
Route::get('/acreditar/{id}',array('uses' => 'AcrAcreditacionController@aprobar', 'as' => 'aprobar'));
Route::get('/rechazar/{id}',array('uses' => 'AcrAcreditacionController@rechazar', 'as' => 'rechazar'));
Route::post('/observaciones/{id}', 'AcrAcreditacionController@store');
Route::get('/acreditacion/{id}/observaciones', 'AcrAcreditacionController@observaciones');
Route::get('/aprobadas', 'AcrAcreditacionController@aprobadas');
Route::get('/rechazadas', 'AcrAcreditacionController@rechazadas');

/****************************************MANUALES*******************************************/
Route::get('manuales', 'DocManualController@index');
/****************************************SOLICITUDES*****************************************/
//Route::get('solicitud/create/{persona}', 'SolSolicitudController@create');
//Route::get('solicitud/show/{persona}', 'SolSolicitudController@show');
Route::resource('solicitud','SolSolicitudController');
Route::get('/solicitud/aprobar/{id}',array('uses' => 'SolSolicitudController@aprobar', 'as' => 'aprobar'));
Route::get('/solicitud/rechazar/{id}',array('uses' => 'SolSolicitudController@rechazar', 'as' => 'rechazar'));
Route::get('lista-art/ajax/{id}',array('uses'=>'SolSolicitudController@listaarticulos', 'as'=>'lista-art.ajax'));
