<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Show;

class WatchlistGrid extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';
    public $typeFilter = '';
    public $sortField = 'updated_at';

    protected $queryString = [
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'search' => ['except' => ''],
        'typeFilter' => ['except' => '', 'as' => 'type'],
        'sortField' => ['except' => 'updated_at', 'as' => 'sort'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $query = Show::where('user_id', auth()->id())->withCount('episodes');

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->search !== '') {
            $query->where('title', 'like', "%{$this->search}%");
        }

        // Apply sorting
        if (in_array($this->sortField, ['updated_at', 'created_at', 'title', 'year', 'rating'])) {
            $direction = in_array($this->sortField, ['title']) ? 'asc' : 'desc';
            $query->orderBy($this->sortField, $direction);
        }

        $shows = $query->paginate(18);

        // Stats for the filter tabs (ignoring current filters to always show totals)
        $userId = auth()->id();
        $stats = [
            'total' => Show::where('user_id', $userId)->count(),
            'watching' => Show::where('user_id', $userId)->where('status', 'watching')->count(),
            'plan_to_watch' => Show::where('user_id', $userId)->where('status', 'plan_to_watch')->count(),
            'completed' => Show::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        return view('livewire.watchlist-grid', [
            'shows' => $shows,
            'stats' => $stats,
        ]);
    }
}
