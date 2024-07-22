<?php

namespace App\Controllers;

use App\Models\usuario_Model;
use CodeIgniter\Controller;

class login_controller extends BaseController
{

    public function index()
    {
        helper(['form', 'url']);
        $data['titulo'] = 'login';
        echo view('front/head_view', $data);
        echo view('front/navbar_view');
        echo view('back/usuario/login');
        echo view('front/footer_view');
    }

    public function auth()
    {
        $session = session();
        $model = new usuario_Model();
        //traemos los datos del formulario
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('pass');

        $data = $model->where('email', $email)->first();
        if ($data) {
            $pass = $data['pass'];
            $ba = $data['baja'];
            if ($ba == 'SI') {
                $session->setFlashdata('msg', 'usuario dado de baja');
                return redirect()->to('/login_controller');
            }
            $verify_pass = password_verify($password, $pass);
            if ($verify_pass) {
                $ses_data = [
                    'id_usuario' => $data['id_usuario'],
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'email' => $data['email'],
                    'usuario' => $data['usuario'],
                    'perfil_id' => $data['perfil_id'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);
                session()->setFlashdata('msg', 'Bienvenido!!');
                return redirect()->to('/panel');
            } else {
                $session->setFlashdata('msg', 'password incorrecta');
                return redirect()->to('/login_controller');
            }
        } else {
            $session->setFlashdata('msg', 'no existe el email o es incorrecto');
            return redirect()->to('/login_controller');
        }
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
