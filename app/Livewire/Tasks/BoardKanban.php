<?php

namespace App\Livewire\Tasks;

use App\Models\User;
use App\Notifications\TaskNotification;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BoardKanban extends Component
{
    use WithFileUploads;

    // -------- Board / Columns ----------
    public Board $board;
    public $columns;

    // -------- Task Modal ----------
    public $showTaskModal = false;
    public $taskModalColumnId = null;
    public $taskModalTaskId = null;

    public $taskTitle = '';
    public $taskDescription = '';
    public $taskStatus = 'draft';
    public $taskPriority = 'normal';
    public $taskDueDate = null;
    public $taskAssignees = [];

    // Detail sections
    public $attachments = [];
    public $comments = [];
    public $newAttachment;
    public $newComment = '';

    // -------- Column inline edit ----------
    public $editingColumnId = null;
    public $editingColumnName = '';

    protected $listeners = [
        'createColumn' => 'createColumn',
    ];

    // -------- Validation ----------
    protected function rules()
    {
        return [
            'taskTitle'       => 'required|string|max:255',
            'taskDescription' => 'nullable|string|max:2000',
            'taskStatus'      => 'required|in:draft,in_progress,done',
            'taskPriority'    => 'required|in:low,normal,high',
            'taskDueDate'     => 'nullable|date',
            'taskAssignees'   => 'array',
            'newComment'      => 'nullable|string|max:1000',
            'newAttachment'   => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
        ];
    }

    // -------- Lifecycle ----------
    public function mount(Board $board)
    {
        $this->board = $board;
        $this->loadColumns();
    }

    public function loadColumns()
    {
        $this->columns = $this->board->columns()
            ->with(['tasks.assignees'])
            ->orderBy('position')
            ->get();
    }

    // =========================================================
    // Columns CRUD
    // =========================================================
    public function createColumn($boardId)
    {
        Column::create([
            'board_id' => $boardId,
            'name'     => 'New Column',
            'position' => $this->columns->count(),
        ]);

        $this->loadColumns();
    }

    public function deleteColumn($columnId)
    {
        if ($col = Column::find($columnId)) {
            $col->delete();
        }
        $this->loadColumns();
    }

    public function startEditColumn($columnId, $currentName)
    {
        $this->editingColumnId = $columnId;
        $this->editingColumnName = $currentName;
    }

    public function saveColumn($columnId)
    {
        if ($col = Column::find($columnId)) {
            $col->update(['name' => $this->editingColumnName]);
        }
        $this->editingColumnId = null;
        $this->editingColumnName = '';
        $this->loadColumns();
    }

    public function cancelEditColumn()
    {
        $this->editingColumnId = null;
        $this->editingColumnName = '';
    }

    public function updateColumnOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            if ($col = Column::find($id)) {
                $col->update(['position' => $index]);
            }
        }
        $this->loadColumns();
    }

    // =========================================================
    // Task Modal (open/save)
    // =========================================================
    public function openTaskModal($taskId = null, $columnId = null)
    {
        $this->taskModalTaskId   = $taskId;
        $this->taskModalColumnId = $columnId;
        $this->showTaskModal     = true;

        if ($taskId) {
            $task = Task::with(['assignees', 'attachments', 'comments.user'])->findOrFail($taskId);

            $this->taskTitle       = $task->title;
            $this->taskDescription = $task->description;
            $this->taskStatus      = $task->status ?? 'draft';
            $this->taskPriority    = $task->priority ?? 'normal';
            $this->taskDueDate     = optional($task->due_date)->format('Y-m-d');
            $this->taskAssignees   = $task->assignees()->pluck('users.id')->toArray();

            $this->attachments     = $task->attachments()->latest()->get();
            $this->comments        = $task->comments()->with('user')->latest()->get();
        } else {
            $this->resetTaskModal();
            $this->taskModalColumnId = $columnId;
        }
    }

    public function saveTaskModal()
    {
        $this->validate();

        if ($this->taskModalTaskId) {
            
            $task = Task::findOrFail($this->taskModalTaskId);

            $task->update([
                'title'       => $this->taskTitle,
                'description' => $this->taskDescription,
                'status'      => $this->taskStatus,
                'priority'    => $this->taskPriority,
                'due_date'    => $this->taskDueDate,
                'column_id'   => $task->column_id ?? $this->taskModalColumnId,
            ]);

            $action = 'task_updated';
        } else {
             
            $task = Task::create([
                'title'       => $this->taskTitle,
                'description' => $this->taskDescription,
                'status'      => $this->taskStatus,
                'priority'    => $this->taskPriority,
                'due_date'    => $this->taskDueDate,
                'column_id'   => $this->taskModalColumnId,
                'created_by'  => Auth::id(),
                'sort_order'  => (Task::where('column_id', $this->taskModalColumnId)->max('sort_order') ?? 0) + 1,
            ]);

            $this->taskModalTaskId = $task->id;
            $action = 'task_created';
        }

         
        $task->assignees()->sync($this->taskAssignees);

         
        if (!empty($this->taskAssignees)) {
            foreach ($this->taskAssignees as $assigneeId) {
                if ($user = User::find($assigneeId)) {
                    $user->notify(new TaskNotification(
                        'Task Assigned',
                        "You have been assigned to task: {$task->title}",
                        route('projects.boards.kanban', $task->id)
                    ));
                }
            }
        }

         
        $project = $task->column?->board?->project;
        if ($project) {
            $project->activityLogs()->create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'action'  => $action,
                'meta'    => [
                    'title'      => $task->title,
                    'assignees'  => $this->taskAssignees,
                    'due_date'   => $task->due_date,
                    'status'     => $task->status,
                    'priority'   => $task->priority,
                ],
            ]);
        }

        $this->loadColumns();
        $this->showTaskModal = false;
        $this->resetTaskModal();
    }

    private function resetTaskModal()
    {
        $this->taskModalTaskId   = null;
        $this->taskTitle         = '';
        $this->taskDescription   = '';
        $this->taskStatus        = 'draft';
        $this->taskPriority      = 'normal';
        $this->taskDueDate       = null;
        $this->taskAssignees     = [];
        $this->attachments       = [];
        $this->comments          = [];
        $this->newAttachment     = null;
        $this->newComment        = '';
    }

    // =========================================================
    // Task quick ops
    // =========================================================
    public function deleteTask($taskId)
    {
        if (!$task = Task::find($taskId)) {
            return;
        }
        $project = $task->column?->board?->project;

        if ($project) {
            $project->activityLogs()->create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'action'  => 'task_deleted',
                'meta'    => ['title' => $task->title],
            ]);
        }

        $task->delete();
        $this->loadColumns();
    }

    public function toggleStatus($taskId)
    {
        if (!$task = Task::find($taskId)) return;

        $statuses     = ['draft', 'in_progress', 'done'];
        $currentIndex = array_search($task->status, $statuses, true);
        $nextIndex    = ($currentIndex === false ? 0 : ($currentIndex + 1) % count($statuses));

        $oldStatus    = $task->status;
        $task->status = $statuses[$nextIndex];
        $task->save();

        $project = $task->column?->board?->project;
        if ($project) {
            $project->activityLogs()->create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'action'  => 'status_changed',
                'meta'    => ['from' => $oldStatus, 'to' => $task->status],
            ]);
        }

        session()->flash('statusMessage', 'Task status updated to ' . ucfirst(str_replace('_',' ',$task->status)));
        session()->flash('statusTaskId', $taskId);
        $this->loadColumns();
    }

    public function updateTaskOrder($tasksOrder)
    {
        foreach ($tasksOrder as $taskId => $data) {
            if (!$task = Task::find($taskId)) continue;

            $oldColumnId = $task->column_id;

            $task->update([
                'column_id'  => $data['column_id'],
                'sort_order' => $data['sort_order'],
            ]);

            $project = $task->column?->board?->project;
            if ($project && (int)$oldColumnId !== (int)$data['column_id']) {
                $project->activityLogs()->create([
                    'user_id' => Auth::id(),
                    'task_id' => $task->id,
                    'action'  => 'moved_column',
                    'meta'    => ['from' => $oldColumnId, 'to' => $data['column_id']],
                ]);
            }
        }
        $this->loadColumns();
    }

    // =========================================================
    // Attachments
    // =========================================================
    public function uploadAttachment()
    {
        $this->validateOnly('newAttachment');

        if (!$this->taskModalTaskId || !$this->newAttachment) return;

        $path = $this->newAttachment->store('attachments', 'public');

        Attachment::create([
            'task_id'       => $this->taskModalTaskId,
            'user_id'       => Auth::id(),
            'path'          => $path,
            'original_name' => $this->newAttachment->getClientOriginalName(),
            'mime'          => $this->newAttachment->getMimeType(),
            'size'          => $this->newAttachment->getSize(),
        ]);

        $this->newAttachment = null;

        $this->attachments = Attachment::where('task_id', $this->taskModalTaskId)
            ->latest()->get();
    }

    public function deleteAttachment($attachmentId)
    {
        if (!$att = Attachment::find($attachmentId)) return;

        Storage::disk('public')->delete($att->path);
        $att->delete();

        $this->attachments = Attachment::where('task_id', $this->taskModalTaskId)
            ->latest()->get();
    }

    // =========================================================
    // Comments
    // =========================================================
    public function addComment()
    {
        $this->validateOnly('newComment', ['newComment' => 'required|string|max:1000']);

        if (!$this->taskModalTaskId) return;

        $comment = Comment::create([
            'task_id' => $this->taskModalTaskId,
            'user_id' => Auth::id(),
            'body'    => $this->newComment,
        ]);

        $this->newComment = '';

        $this->comments = Comment::with('user')
            ->where('task_id', $this->taskModalTaskId)
            ->latest()->get();

        
        if ($task = Task::find($this->taskModalTaskId)) {
            $project = $task->column?->board?->project;
            if ($project) {
                $project->activityLogs()->create([
                    'user_id' => Auth::id(),
                    'task_id' => $task->id,
                    'action'  => 'comment_added',
                    'meta'    => ['excerpt' => mb_substr($comment->body, 0, 40)],
                ]);
            }
        }
    }

    // =========================================================
    // Derived
    // =========================================================
    public function getBoardProgressProperty()
    {
        $total = $this->board->tasks()->count();
        $done  = $this->board->tasks()->where('status', 'done')->count();
        return $total > 0 ? round(($done / $total) * 100, 2) : 0;
    }

    // =========================================================
    // Render
    // =========================================================
    public function render()
    {
        return view('livewire.tasks.board-kanban', [
            'projectUsers'  => $this->board->project?->members ?? [],
            'boardProgress' => $this->boardProgress,
        ])->layout('layouts.app');
    }
}
