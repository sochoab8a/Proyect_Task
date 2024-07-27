<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;
class TaskController extends Controller
{

     
    public function store(Request $request){
        // Valida la solicitud
        $validator = Validator::make($request->all(), [
            'name_task' => 'required',
            'description_task' => 'required',
            'priority_task' => 'required',
            'user_id' => 'required',
            'email_user' => 'required|email'
        ]);
    
        // Verifica si la validación falla
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $validatedData = $validator->validated();
    
       
        $newTask = Task::create([
            'name_task' => $validatedData['name_task'],
            'description_task' => $validatedData['description_task'],
            'priority_task' => $validatedData['priority_task'],
            'user_id' => $validatedData['user_id'],
        ]);
    
       
        if ($newTask->priority_task === 'Alta') {
            $userEmail = $validatedData['email_user']; 
            $details = [
                'subject' =>"Recordatorio Tarea",
                'message' => "Tienes una nueva tarea de alta prioridad, vamos a completarla",
            ];
    
            Mail::to($userEmail)->send(new BasicMail($details));
        }
    
        // Respuesta de éxito
        return response()->json([
            'message' => 'Tarea creada con éxito',
            'user' => $newTask
        ], 201);
    }

    public function update(Request $request, $id) {
        $task = Task::find($id);
    
        if (!$task) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name_task' => 'required',
            'description_task' => 'required',
            'priority_task' => 'required',
            'email_user' => 'required|email'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $task->update($validator->validated());
    
        if ($task->priority_task === 'Alta') {
            $userEmail = $request->input('email_user'); // Usa el correo electrónico del request
            $details = [
                'subject' => "Recordatorio Tarea",
                'message' => "Tienes una nueva tarea de alta prioridad, vamos a completarla",
            ];
    
            Mail::to($userEmail)->send(new BasicMail($details));
        }
    
        return response()->json([
            'message' => 'Tarea actualizada con éxito',
            'task' => $task
        ], 200);
    }

    public function destroy($id){

        $task= Task::find($id);

        if(!$task){

            return response()->json(['message'=>'tarea no encontrada'],404);


        }

        $task->delete();

        return response()->json([
            'message'=>'tarea eliminada con exito '
        ], 200);


    }


    public function index(Request $request,$id)
    {
        // Obtén el número de tareas por página desde la solicitud o usa un valor predeterminado
        $perPage = $request->input('per_page', 10);
    
        // Obtén el número de la página actual desde la solicitud o usa 1 como valor predeterminado
        $page = $request->input('page', 1);
    
        // Obtén las tareas paginadas
        $tasks = Task::where('user_id', $id)->paginate($perPage, ['*'], 'page', $page);
    
        // Retorna la respuesta en formato JSON
        return response()->json($tasks);
    }


}
