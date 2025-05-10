@foreach($pointages as $pointage)
<div class="flex items-center justify-between p-4 border-b">
    <div>{{ $pointage->created_at->format('d/m/Y') }}</div>
    
    @if($pointage->statut === 'absent')
        <span class="text-red-500">❌ Absent</span>
    @else
        <div class="flex items-center gap-2">
            <span class="{{ $pointage->statut === 'retard' ? 'text-yellow-500' : 'text-green-500' }}">
                {{ $pointage->statut === 'retard' ? '⚠ Retard' : '✅ À l\'heure' }}
            </span>
            @if($pointage->retard_minutes)
                <span class="text-sm text-gray-500">
                    (+{{ $pointage->retard_minutes }} minutes)
                </span>
            @endif
        </div>
    @endif
</div>
@endforeach