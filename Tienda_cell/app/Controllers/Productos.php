<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;
use App\Models\MarcasModel;
use App\Models\ProveedoresModel;

class Productos extends BaseController
{

    protected $productos;
    protected $reglas;

    public function __construct()
    {
        $this->productos = new ProductosModel();
        $this->marcas = new MarcasModel();
        $this->proveedores = new ProveedoresModel();


        helper(['form']);
        $this->reglas = [
            'codigo' => [

                'rules' => 'required|is_unique[productos.codigo]',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.',
                    'is_unique' => 'El campo {field} debe ser unico.',
                ]
            ],
            'nombre' => [

                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio'
                ]
            ],
            'id_marcas' => [

                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio'
                ]
            ],

            'id_proveedores' => [

                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio'
                ]
            ],

            'precio_venta' => [

                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio'
                ]
            ],

            'existencias' => [

                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio'
                ]
            ],
        ];
    }

    public function index($activo = 1)
    {
        //realizo la consulta a la base de datos y traigo el dato activo. 
        $productos = $this->productos->where('activo', $activo)->findAll();
        $marcas = $this->marcas->where('activo', 1)->findAll();

        $data = ['titulo' => 'Productos', 'datos' => $productos, 'marcas' => $marcas];

        echo view('header');
        echo view('productos/productos', $data);
        echo view('footer');
    }

    public function eliminados($activo = 0)
    {
        //realizo la consulta a la base de datos y traigo el dato activo. 
        $productos = $this->productos->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Productos eliminadas', 'datos' => $productos];

        echo view('header');
        echo view('productos/eliminados', $data);
        echo view('footer');
    }

    public function nuevo()
    {

        $marcas = $this->marcas->where('activo', 1)->findAll();
        $proveedores = $this->proveedores->where('activo', 1)->findAll();
        $data = ['titulo' => 'Agregar producto',  'marcas' => $marcas, 'proveedores' => $proveedores];

        echo view('header');
        echo view('productos/nuevo', $data);
        echo view('footer');
    }

    public function insertar()
    {
        if ($this->request->getMethod() == "post"  && $this->validate($this->reglas)) {

            $this->productos->save([
                'codigo' => $this->request->getPost('codigo'),
                'nombre' => $this->request->getPost('nombre'),
                'precio_venta' => $this->request->getPost('precio_venta'),            
                'inventariable' => $this->request->getPost('inventariable'),
                'id_marcas' => $this->request->getPost('id_marcas'),
                'existencias' => $this->request->getPost('existencias'),
                'id_proveedores' => $this->request->getPost('id_proveedores'),
                'tipo_producto' => $this->request->getPost('tipo_producto')
            ]);
            return redirect()->to(base_url() . '/productos');
        } else {

            $marcas = $this->marcas->where('activo', 1)->findAll();
            $proveedores = $this->proveedores->where('activo', 1)->findAll();
            $data = ['titulo' => 'Agregar producto',  'marcas' => $marcas, 'proveedores' => $proveedores, 'validation' => $this->validator];

            echo view('header');
            echo view('productos/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id)
    {

        $marcas = $this->marcas->where('activo', 1)->findAll();
        $proveedores = $this->proveedores->where('activo', 1)->findAll();
        $producto = $this->productos->where('id', $id)->first();
        $data = ['titulo' => 'Editar producto', 'producto' => $producto, 'marcas' => $marcas, 'proveedores' => $proveedores];

        echo view('header');
        echo view('productos/editar', $data);
        echo view('footer');
    }

    public function actualizar()
    {
        $this->productos->update($this->request->getPost('id'), [
            'codigo' => $this->request->getPost('codigo'),
            'nombre' => $this->request->getPost('nombre'),
            'precio_venta' => $this->request->getPost('precio_venta'),
            'inventariable' => $this->request->getPost('inventariable'),
            'id_marcas' => $this->request->getPost('id_marcas'),
            'existencias' => $this->request->getPost('existencias'),
            'id_proveedores' => $this->request->getPost('id_proveedores'),
            'tipo_producto' => $this->request->getPost('tipo_producto')
        ]);

        return redirect()->to(base_url() . '/productos');
    }


    public function eliminar($id)
    {
        $this->productos->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . '/productos');
    }

    public function reingresar($id)
    {
        $this->productos->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . '/productos');
    }
}
