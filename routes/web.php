<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? ['password.confirm']
                : []
        )
        ->name('two-factor.show');


    Route::view('tasks', 'tasks.index')->name('tasks.index');
    Route::view('tasks/create', 'tasks.form')->name('tasks.create');
    Route::get('tasks/{task}/edit', function (\App\Models\Task $task) {
        return view('tasks.form', compact('task'));
    })->middleware('task.owner')->name('tasks.edit');

    Route::view('categories', 'categories.index')->name('categories.index');
    Route::view('tags', 'tags.index')->name('tags.index');
});
