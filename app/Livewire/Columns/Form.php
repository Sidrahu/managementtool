<?php

namespace App\Livewire\Columns;

use Livewire\Component;
use App\Models\Column;
use App\Models\Board;

class Form extends Component
{
    protected $listeners = ['open-column-modal' => 'openModal'];
    public Board $board;
    public Column $column;
    public $name = '';

    public function mount(Board $board, Column $column = null)
    {
        $this->board = $board;
        $this->column = $column ?? new Column();
        $this->name = $this->column->name ?? '';
    }

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $this->column->board_id = $this->board->id;
        $this->column->name = $this->name;
        $this->column->position = $this->board->columns()->count() + 1;
        $this->column->save();

        $this->emit('columnSaved');
        return redirect()->route('projects.boards.columns.index', $this->board->id);
    }

    public function render()
    {
        return view('livewire.columns.form')->layout('layouts.app');
    }
}
