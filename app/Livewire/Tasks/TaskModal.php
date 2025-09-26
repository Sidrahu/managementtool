<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskModal extends Component
{
    use WithFileUploads;

    public $taskId;
    public $columnId;
    public $showModal = false;
    public $task;

    public $title;
    public $description;
    public $priority = 'normal';
    public $due_date;
    public $assignees = [];
    public $projectUsers = [];

    public $newAttachment;
    public $newComment;

    protected $listeners = ['showTaskModal' => 'open'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'priority' => 'required|in:low,normal,high',
        'newComment' => 'nullable|string|max:1000',
        'newAttachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,docx,txt',
    ];

    public function open($taskId = null, $columnId = null, $projectUsers = [])
    {
        $this->taskId = $taskId;
        $this->columnId = $columnId;
        $this->projectUsers = $projectUsers;
        $this->showModal = true;

        if ($taskId) {
            $this->task = Task::with(['assignees', 'comments.user', 'attachments.user'])->find($taskId);

            $this->title       = $this->task->title;
            $this->description = $this->task->description;
            $this->priority    = $this->task->priority;
            $this->due_date    = $this->task->due_date?->format('Y-m-d');
            $this->assignees   = $this->task->assignees->pluck('id')->toArray();
        } else {
            $this->resetExcept(['showModal']);
        }
    }

    public function saveTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:low,normal,high',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
        ];

        if ($this->taskId) {
            $task = Task::find($this->taskId);
            $task->update($data);
        } else {
            $data['column_id'] = $this->columnId;
            $data['created_by'] = Auth::id();
            $task = Task::create($data);
            $this->taskId = $task->id;
        }

        $task->assignees()->sync($this->assignees);

        $this->task = Task::with(['assignees', 'comments.user', 'attachments.user'])->find($this->taskId);

        $this->emitUp('taskSaved');
        $this->dispatchBrowserEvent('task-updated');
        $this->showModal = false;
    }

    public function addAttachment()
{
    if (!$this->newAttachment || !$this->taskId) return;

    $path = $this->newAttachment->store('attachments', 'public');

    Attachment::create([
        'task_id' => $this->taskId,
        'user_id' => Auth::id(),
        'path' => $path,
        'original_name' => $this->newAttachment->getClientOriginalName(),
        'mime' => $this->newAttachment->getMimeType(),
        'size' => $this->newAttachment->getSize(),
    ]);

    $this->newAttachment = null;

    
    $this->task = Task::with(['attachments.user', 'comments.user', 'assignees'])
        ->find($this->taskId);
}


   public function deleteAttachment($attachmentId)
{
    $attachment = Attachment::find($attachmentId);
    if ($attachment) {
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

         
        $this->task = Task::with(['attachments.user', 'comments.user', 'assignees'])
            ->find($this->taskId);
    }
}


    public function addComment()
    {
        $this->validate(['newComment' => 'required|string']);

        Comment::create([
            'task_id' => $this->taskId,
            'user_id' => Auth::id(),
            'body'    => $this->newComment,
        ]);

        $this->newComment = '';
        $this->task->load('comments.user');
    }

    public function render()
    {
        return view('livewire.tasks.task-modal')
            ->layout('layouts.app');
    }
}
