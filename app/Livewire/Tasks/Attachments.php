<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;

class Attachments extends Component
{
    use WithFileUploads;

    public $task;
    public $file;

    public function upload()
    {
        $this->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf,docx|max:2048'
        ]);

        $path = $this->file->store('attachments', 'public');

        Attachment::create([
            'task_id'       => $this->task->id,
            'user_id'       => Auth::id(),
            'path'          => $path,
            'original_name' => $this->file->getClientOriginalName(),
            'size'          => $this->file->getSize(),
            'mime'          => $this->file->getMimeType(),
        ]);

        $this->file = null;
        $this->task->refresh();
    }

    public function delete($id)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->delete();
    }

    public function render()
    {
        return view('livewire.tasks.attachments', [
            'attachments' => $this->task->attachments
        ]);
    }
}
