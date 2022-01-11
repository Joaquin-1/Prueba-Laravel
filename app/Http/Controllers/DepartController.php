<?php

namespace App\Http\Controllers;

use App\Models\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartController extends Controller
{
    public function index()
    {
        $ordenes = ['denominacion', 'localidad'];
        $orden = request()->query('orden') ?: 'denominacion';
        abort_unless(in_array($orden, $ordenes), 404);

        $departs = DB::table('depart')
            ->orderBy($orden);

        if (($denominacion = request()->query('denominacion')) !== null) {
            $departs->where('denominacion', 'ilike', "%$denominacion%");
        }

        if (($localidad = request()->query('localidad')) !== null) {
            $departs->where('localidad', 'ilike', "%$localidad%");
        }

        $paginador = $departs->paginate(4);
        $paginador->appends(compact(
            'denominacion',
            'localidad',
            'orden'
        ));

        return view('depart.index', [
            'departamentos' => $paginador,
        ]);
    }

    public function create()
    {

        $departamento = new Depart();

        return view('depart.create', [
            'departamento' => $departamento,
        ]);
    }

    public function store()
    {
        $validados = $this->validar();

        $departamento = new Depart();
        $departamento->denominacion = $validados['denominacion'];
        $departamento->localidad = $validados['localidad'];
        $departamento->save();

        return redirect('/depart')
            ->with('success', 'Departamento insertado con éxito.');
    }

    public function edit($id)
    {
        $departamento = Depart::findOrFail($id);

        return view('depart.edit', [
            'departamento' => $departamento,
        ]);
    }

    public function update($id)
    {
        $validados = $this->validar();
        $departamento = Depart::findOrFail($id);
        $departamento->denominacion = $validados['denominacion'];
        $departamento->localidad = $validados['localidad'];
        $departamento->save();

        return redirect('/depart')
            ->with('success', 'Departamento modificado con éxito.');
    }

    private function validar()
    {
        $validados = request()->validate([
            'denominacion' => 'required|max:255',
            'localidad' => 'required|max:255',
        ]);

        return $validados;
    }

    public function destroy($id)
    {
        $departamento = Depart::findOrFail($id);
        
        $departamento->delete();

        

        return redirect()->back()
            ->with('success', 'Alumno borrado correctamente');
    }


    private function findDepartamento($id)
    {
        $departamentos = DB::table('depart')
            ->where('id', $id)
            ->get();

        abort_if($departamentos->isEmpty(), 404);

        return $departamentos->first();
    }
}
