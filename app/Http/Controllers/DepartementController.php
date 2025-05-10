<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::withCount('employes')->orderBy('name')->get();
        return view('admin.departements.index', compact('departements'));
    }

    public function create()
    {
        return view('admin.departements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departements',
        ]);

        Departement::create($request->only('name'));

        return redirect()->route('Admin.departements.index')
            ->with('success', 'Département créé avec succès');
    }

    public function show(Departement $departement)
    {
        $departement->load('employes');
        return view('admin.departements.show', compact('departement'));
    }

    public function edit(Departement $departement)
    {
        return view('admin.departements.edit', compact('departement'));
    }

    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departements,name,'.$departement->id,
        ]);

        $departement->update($request->only('name'));

        return redirect()->route('Admin.departements.index')
            ->with('success', 'Département mis à jour avec succès');
    }

    public function destroy(Departement $departement)
    {
        if ($departement->employes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un département contenant des employés');
        }

        $departement->delete();

        return redirect()->route('Admin.departements.index')
            ->with('success', 'Département supprimé avec succès');
    }
}