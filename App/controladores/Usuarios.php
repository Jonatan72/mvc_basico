<?php

    class Usuarios extends Controlador{

        public function __construct(){

            $this->usuarioModelo = $this->modelo('Usuario');

            $this->datos['menuActivo'] = 1;         // Definimos el menu que sera destacado en la vista
            
        }


        public function index(){
            //Obtenemos los usuarios
            $usuarios = $this->usuarioModelo->obtenerUsuarios();

            $this->datos['usuarios'] = $usuarios;

            $this->vista('usuarios/inicio',$this->datos);
        }


        public function agregar(){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $usuarioNuevo = [
                    'nombre' => trim($_POST['nombre']),
                    'email' => trim($_POST['email']),
                    'telefono' => trim($_POST['telefono']),
                    'id_rol' => trim($_POST['rol']),
                ];

                if ($this->usuarioModelo->agregarUsuario($usuarioNuevo)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            } else {
                $this->datos['usuario'] = (object) [
                    'nombre' => '',
                    'email' => '',
                    'telefono' => '',
                    'id_rol' => 3
                ];

                $this->datos['listaRoles'] = $this->usuarioModelo->obtenerRoles();

                $this->vista('usuarios/agregar_editar',$this->datos);
            }
        }


        public function editar($id){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $usuarioModificado = [
                    'id_usuario' => $id,
                    'nombre' => trim($_POST['nombre']),
                    'email' => trim($_POST['email']),
                    'telefono' => trim($_POST['telefono']),
                    'id_rol' => trim($_POST['rol']),
                ];

                if ($this->usuarioModelo->actualizarUsuario($usuarioModificado)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            } else {
                //obtenemos información del usuario y el listado de roles desde del modelo
                $this->datos['usuario'] = $this->usuarioModelo->obtenerUsuarioId($id);
                $this->datos['listaRoles'] = $this->usuarioModelo->obtenerRoles();

                $this->vista('usuarios/agregar_editar',$this->datos);
            }
        }


        public function borrar($id){
            //obtenemos información del usuario desde del modelo
            $usuario = $this->usuarioModelo->obtenerUsuarioId($id);

            $this->datos['id_usuario'] = $usuario->id_usuario;
            $this->datos['nombre'] = $usuario->nombre;
            $this->datos['email'] = $usuario->email;
            $this->datos['telefono'] = $usuario->telefono;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->usuarioModelo->borrarUsuario($id)){
                    redireccionar('/usuarios');
                } else {
                    die('Algo ha fallado!!!');
                }
            }
            $this->vista('usuarios/borrar',$this->datos);
        }


    }
