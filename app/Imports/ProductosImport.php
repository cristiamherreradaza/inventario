<?php

namespace App\Imports;

use App\Tipo;
use App\Marca;
use App\Precio;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Movimiento;
use App\Proveedore;
use App\Caracteristica;
use App\Configuracione;
use App\CategoriasProducto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
        //echo "Nombre ".$row[]
        $producto                  = new Producto();
        $producto->user_id         = Auth::user()->id;
        $producto->marca_id        = 1;
        $producto->tipo_id         = 1;
        $producto->codigo          = $row[4];
        $producto->nombre          = $row[2];
        $producto->nombre_venta    = $row[3];
        $producto->save();
        $producto_id = $producto->id;

        $busca_proveedor = Proveedore::where('nombre', 'like', "%$row[1]%")
            ->first();
        if ($busca_proveedor == null) {
            $proveedor = new Proveedore();
            $proveedor->user_id = Auth::user()->id;
            $proveedor->nombre = $row[1];
            $proveedor->save();
            $proveedorId = $proveedor->id;
        } else {
            $proveedorId = $busca_proveedor->id;
        }

       
        $movimiento                = new Movimiento();
        $movimiento->user_id       = Auth::user()->id;
        $movimiento->producto_id   = $producto_id;
        $movimiento->almacene_id   = 1;
        $movimiento->proveedor_id  = 1;
        $movimiento->tipo_id       = 1;
        $movimiento->fecha         = date("Y-m-d H:i:s");
        $movimiento->ingreso       = $row[8];
        $movimiento->estado        = "Ingreso";
        $movimiento->save();

       
    }

    function extraeCodigo($texto)
    {
        $palabra = explode(" ", $texto);
        $primeras = Str::substr($palabra[0], 0, 3);
        $sigla = str_replace(" ", "", $primeras);
        $siglaMayusculas = strtoupper($sigla);
        return $siglaMayusculas;
    }

    /*public function startRow(): int
    {
        return 2;
    }*/

    public function startRow(): int
    {
        return 2;
    }

}