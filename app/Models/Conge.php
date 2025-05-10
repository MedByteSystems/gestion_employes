<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $table = 'conges';

    protected $fillable = [
        'start_date',
        'end_date',
        'type',
        'reason',
        'status',
        'employee_id',
        'approved_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Valeurs possibles pour le statut
    public const STATUSES = [
        'En attente',
        'Approuvé',
        'Rejeté'
    ];

    // Types de congé possibles
    public const TYPES = [
        'Annuel',
        'Maladie', 
        'Maternité',
        'Sans solde'
    ];

    // Relation avec l'employé
    public function employee()
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }

    // Relation avec l'approbateur (User)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Validation des données
    public static function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:' . implode(',', self::TYPES),
            'reason' => 'required|string|max:500',
            'status' => 'sometimes|in:' . implode(',', self::STATUSES)
        ];
    }
}