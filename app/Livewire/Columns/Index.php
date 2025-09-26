<?php

namespace App\Livewire\Columns;

use Livewire\Component;
use App\Models\Board;
use App\Models\Column;

class Index extends Component
{
    public Board $board;
    public $columns;
    
   
    public $editingColumnId;
    public $name;
    public $wip_limit;
    public $showEditModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'wip_limit' => 'nullable|integer|min:1',
    ];

    protected $listeners = ['refreshColumns' => 'loadColumns'];

    public function mount()
    {
        $this->loadColumns();
    }

    public function loadColumns()
    {
        $this->columns = $this->board->columns()->orderBy('position')->get();
    }

   
    public function editColumn($columnId)
    {
        $column = Column::findOrFail($columnId);
        $this->editingColumnId = $column->id;
        $this->name = $column->name;
        $this->wip_limit = $column->wip_limit;
        $this->showEditModal = true;
    }

    
    public function updateColumn()
    {
        $this->validate();

        $column = Column::findOrFail($this->editingColumnId);
        $column->update([
            'name' => $this->name,
            'wip_limit' => $this->wip_limit,
        ]);

        $this->showEditModal = false;
        $this->emit('refreshColumns');  
        session()->flash('success', 'Column updated successfully.');
    }

   
    public function deleteColumn($columnId)
    {
        $column = Column::findOrFail($columnId);
        $column->delete();

        $this->loadColumns();
        session()->flash('success', 'Column deleted successfully.');
    }

    
    public function updateColumnOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Column::find($id)->update(['position' => $index]);
        }

        $this->loadColumns();
    }

    public function render()
    {
        return view('livewire.columns.index', [
            'columns' => $this->columns
        ])->layout('layouts.app');
    }
}
