<?php
namespace App\Http\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;

class Comments extends Component
{
    use WithFileUploads;

    public $task;
    public $newComment = '';
    public $attachments = [];

    protected $rules = [
        'newComment' => 'required|string|max:1000',
        'attachments.*' => 'file|max:10240|mimes:jpg,png,pdf,docx,txt' // max 10MB
    ];

    public function mount(Task $task)
    {
        $this->task = $task;
    }

    public function addComment()
    {
        $this->validate();

        $comment = Comment::create([
            'task_id' => $this->task->id,
            'user_id' => Auth::id(),
            'body' => $this->newComment,
        ]);

        
        foreach ($this->attachments as $file) {
            $path = $file->store('attachments', 'public');
            Attachment::create([
                'task_id' => $this->task->id,
                'file_path' => $path,
                'uploaded_by' => Auth::id(),
            ]);
        }

         
        $this->newComment = '';
        $this->attachments = [];

        $this->emit('commentAdded');  
    }

    public function render()
    {
        $comments = Comment::where('task_id', $this->task->id)
                            ->with('user', 'task')
                            ->latest()
                            ->get();

        $attachments = Attachment::where('task_id', $this->task->id)
                                 ->with('user')
                                 ->latest()
                                 ->get();

        return view('livewire.tasks.comments', [
            'comments' => $comments,
            'attachments' => $attachments,
        ]);
    }
}
