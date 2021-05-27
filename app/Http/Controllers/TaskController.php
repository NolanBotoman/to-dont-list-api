<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
        ]);

        if ($validator->fails()) {
        	return response()->json([
			    'errors' => $validator->errors()->first()
			], 422);
        }

        $task = Task::create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
			    'message' => 'Successfully created a new task.',
				'task' => $task
		], 201);
    }

    public function getAll(Request $request)
    {
    	return response()->json([
			'tasks' => $request->user()->tasks
		], 201);
    }

    public function getTaskById(Request $request, $id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json([
				'errors' => 'Task not found.'
			], 404);
        }

        if ($task->user_id != $request->user()->id) {
            return response()->json([
				'errors' => 'This task belongs to another user.'
			], 403);
        }

        return response()->json([
			'task' => $task
		], 200);
    }

    public function delete(Request $request, $id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json([
				'errors' => 'Task not found.'
			], 404);
        }

        if ($task->user_id != $request->user()->id) {
            return response()->json([
				'errors' => 'This task belongs to another user.'
			], 403);
        }

        Task::destroy($id);

        return response()->json([
			'message' => 'This task has been successfully deleted',
			'task' => $task
		], 200);
    }

    public function update(Request $request, $id)
    {
    	$task = Task::find($id);

        if (empty($task)) {
            return response()->json([
				'errors' => 'Task not found.'
			], 404);
        }

        if ($task->user_id != $request->user()->id) {
            return response()->json([
				'errors' => 'This task belongs to another user.'
			], 403);
        }

        $bodyValidator = Validator::make($request->all(), [
            'body' => 'required',
        ]);

        $doneValidator = Validator::make($request->all(), [
            'done' => 'required',
        ]);

        if ($bodyValidator->fails() && $doneValidator->fails()) {
        	return response()->json([
			    'errors' => $bodyValidator->errors()->first()
			], 422);
        }

        $data = [];

        if (!$bodyValidator->fails()) {
        	$data['body'] = $request->body;
        } 

        if (!$doneValidator->fails()) {
            $data['done'] = $request->done;
        }

        $task->update($data);

        return response()->json([
			'message' => 'This task has been successfully updated',
			'task' => $task
		], 200);
    }
}
