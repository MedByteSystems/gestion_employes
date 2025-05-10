<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CongeController extends Controller
{

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
        return $this->getRoutePrefix() . '.' . $action;
    }
    public function index()
    {
        $conges = Conge::with(['employee', 'approver'])
                     ->latest()
                     ->paginate(10); // Changed from get() to paginate()
        
        return view('conges.index', compact('conges'));
    }

    public function create()
    {
        return view('conges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:' . implode(',', Conge::TYPES),
            'reason' => 'required|string|max:500'
        ]);

        Conge::create([
            'employee_id' => Auth::user()->employe->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'status' => 'En attente'
        ]);

        return redirect()->route('Employé.dashboard')
               ->with('success', 'Demande de congé envoyée.');
    }

    public function update(Request $request, Conge $conge)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Conge::STATUSES)
        ]);

        $conge->update([
            'status' => $validated['status'],
            'approved_by' => Auth::id()
        ]);

        return redirect()->route($this->getRedirectRoute('dashboard'))
               ->with('success', 'Statut du congé mis à jour.');
    }
}