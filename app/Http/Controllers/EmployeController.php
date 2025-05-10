<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\User;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class EmployeController extends Controller
{
    /**
     * Get the proper route prefix based on user role
     */
    private function getRoutePrefix(): string
    {
        return match(auth()->user()->role) {
            'Admin' => 'Admin',
            'Manager' => 'Manager',
            'Employé' => 'Employé',
            default => 'manager' // fallback
        };
    }

    /**
     * Get the proper redirect route after operations
     */
    private function getRedirectRoute(string $action = 'index'): string
    {
        return $this->getRoutePrefix() . '.employes.' . $action;
    }

    public function index(Request $request)
    {
        $query = Employe::query();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Apply department filter
        if ($request->has('department') && $request->department) {
            $query->where('departement_id', $request->department);
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Fetch employees with pagination
        $employes = $query->paginate(10);

        // Fetch departments for the filter dropdown
        $departements = Departement::all();

        return view('employe.index', compact('employes', 'departements'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employe')->get();
        $departements = Departement::all();
        return view('employe.create', compact('users', 'departements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'cin' => 'required|string|max:20|unique:employes,cin',
            'marital_status' => 'required|string|max:50',
            'birth_date' => 'required|date|before:-18 years',
            'gender' => 'required|in:male,female',
            'position' => 'required|string|max:50',
            'hire_date' => 'required|date|after_or_equal:birth_date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'departement_id' => 'required|exists:departements,id',
            'user_id' => 'required|exists:users,id|unique:employes,user_id'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Employe::create($validated);

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Employé créé avec succès');
    }

    public function edit(Employe $employe)
    {
        $departements = Departement::all();
        $users = User::whereDoesntHave('employe')
                     ->orWhere('id', $employe->user_id)
                     ->get();

        return view('employe.edit', compact('employe', 'departements', 'users'));
    }

    public function update(Request $request, Employe $employe): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'cin' => 'required|string|max:20|unique:employes,cin,' . $employe->id,
            'marital_status' => 'required|string|max:50',
            'birth_date' => 'required|date|before:-18 years',
            'gender' => 'required|in:male,female',
            'position' => 'required|string|max:50',
            'hire_date' => 'required|date|after_or_equal:birth_date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'departement_id' => 'required|exists:departements,id',
            'user_id' => 'required|exists:users,id|unique:employes,user_id,' . $employe->id
        ]);

        if ($request->hasFile('photo')) {
            if ($employe->photo) {
                Storage::disk('public')->delete($employe->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->has('remove_photo')) {
            if ($employe->photo) Storage::disk('public')->delete($employe->photo);
            $validated['photo'] = null;
        }

        $employe->update($validated);

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Mise à jour réussie');
    }

    public function destroy(Employe $employe): RedirectResponse
    {
        if ($employe->photo) {
            Storage::disk('public')->delete($employe->photo);
        }

        $employe->delete();

        return redirect()->route($this->getRedirectRoute('index'))
            ->with('success', 'Employé supprimé avec succès');
    }
}