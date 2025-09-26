<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Manager\Dashboard as ManagerDashboard;
 
use App\Livewire\Contributor\Dashboard as ContributorDashboard;

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Users as AdminUsers;
use App\Livewire\Admin\Projects as AdminProjects;

use App\Livewire\Projects\Index as ProjectIndex;
use App\Livewire\Projects\Create as ProjectCreate;
use App\Livewire\Projects\Edit as ProjectEdit;
use App\Livewire\Projects\Show as ProjectShow;
use App\Livewire\Projects\InviteMember;

 
use App\Livewire\Boards\Index as BoardsIndex;
use App\Livewire\Boards\Create as BoardsCreate;
use App\Livewire\Tasks\BoardKanban;

 
use App\Livewire\Columns\Index as ColumnsIndex;
use App\Livewire\Columns\Form as ColumnsForm;

 
use App\Livewire\Tasks\TaskModal;


// --------------------
// Auth Routes
// --------------------


// Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';


Route::get('/', function () {
    if(auth()->check()){
        $user = auth()->user();

        if($user->hasRole('admin')){
            return redirect()->route('admin.dashboard');
        } elseif($user->hasRole('manager')){
            return redirect()->route('manager.dashboard');
        } elseif($user->hasRole('contributor')){
            return redirect()->route('contributor.dashboard');
        } 

        return redirect()->route('projects.index');  
    }

    return view('welcome'); 
});


// --------------------
// Admin Routes (only for admin)
// --------------------


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/users', AdminUsers::class)->name('users');
        Route::get('/projects', AdminProjects::class)->name('projects');
    });



Route::middleware(['auth', 'role:manager|admin'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', ManagerDashboard::class)->name('dashboard');
    });

Route::middleware(['auth', 'role:contributor'])->prefix('contributor')->name('contributor.')->group(function () {
    Route::get('/dashboard', ContributorDashboard::class)->name('dashboard');
});



// --------------------
// Projects Routes
// --------------------


Route::middleware(['auth'])->prefix('projects')->name('projects.')->group(function () {

    // --------------------
    // Admin + Manager → full project control
    // --------------------
    Route::middleware('role:admin|manager')->group(function () {
        Route::get('/create', ProjectCreate::class)->name('create');
        Route::get('/{project}/edit', ProjectEdit::class)->name('edit');
        Route::get('/{project}/invite', InviteMember::class)->name('invite');

        
        Route::get('/{project}/boards/create', BoardsCreate::class)->name('boards.create');

         
        Route::prefix('boards/{board}/columns')->group(function () {
            Route::get('/create', ColumnsForm::class)->name('columns.create');
            Route::get('/{column}/edit', ColumnsForm::class)->name('columns.edit');
        });
    });

    // --------------------
    // Admin + Manager + Contributor → can manage tasks
    // --------------------
    Route::middleware('role:admin|manager|contributor')->group(function () {
        Route::prefix('boards/{board}/tasks')->group(function () {
            Route::get('/create', TaskModal::class)->name('tasks.create');
            Route::get('/{task}/edit', TaskModal::class)->name('tasks.edit');
        });
    });

  
    Route::middleware('role:admin|manager|contributor|viewer')->group(function () {
        Route::get('/', ProjectIndex::class)->name('index');
        Route::get('/{project}', ProjectShow::class)->name('show');

         
        Route::get('/{project}/boards', BoardsIndex::class)->name('boards.index');

       
        Route::get('boards/{board}/kanban', BoardKanban::class)->name('boards.kanban');

       
        Route::get('boards/{board}/columns', ColumnsIndex::class)->name('columns.index');
    });
});
