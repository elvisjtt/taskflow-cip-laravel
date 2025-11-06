<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta funcionalidad');
        }

        $routeTask = $request->route('task');
        $taskId = $routeTask instanceof Task ? $routeTask->id : ($routeTask ?? $request->route('id') ?? $request->input('task_id'));

        if (!$taskId) {
            abort(400, 'ID de tarea no proporcionado');
        }

        $task = $routeTask instanceof Task ? $routeTask : Task::find($taskId);

        if (!$task) {
            abort(404, 'Tarea no encontrada');
        }

        if ($task->user_id !== auth()->id()) {
            Log::warning('Intento de acceso no autorizado a tarea', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'task_id' => $taskId,
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            abort(403, 'No tienes permisos para acceder a esta tarea');
        }

        $request->merge(['validated_task' => $task]);

        return $next($request);
    }
}
