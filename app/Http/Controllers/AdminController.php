<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        $totalStudent = User::where('role', 'student')->count();
        $totalLecturer = User::where('role', 'lecturer')->count();
        $totalDepartment = Department::count();
        $totalTopic = Topic::count();

        return view('admin.components.home', ['totalStudent' => $totalStudent, 'totalLecturer' => $totalLecturer, 'totalDepartment' => $totalDepartment, 'totalTopic' => $totalTopic]);
    }

    public function student()
    {
        $data = User::where('role', 'student')->with('department')->get();
        return view('admin.components.student', ['student' => $data]);
    }

    public function createStudent()
    {
        $data = Department::all();

        return view('admin.components.create-student', ['department' => $data]);
    }

    public function editStudent($id)
    {
        $data = User::find($id);
        $dataDepartment = Department::all();

        return view('admin.components.edit-student', ['student' => $data, 'department' => $dataDepartment]);
    }

    public function saveStudent(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'departments_id' => ['required'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'departments_id' => intval($request->departments_id),
        ]);

        event(new Registered($user));

        return redirect()->route('student');
    }

    public function updateStudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'departments_id'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']           = $request->name;
        $data['departments_id'] = $request->departments_id;

        User::find($id)->update($data);

        return redirect()->route('student');
    }

    public function deleteStudent($id)
    {
        User::find($id)->forceDelete();

        return redirect()->route('student');
    }

    public function lecturer()
    {
        $data = User::where('role', 'lecturer')->with('department')->get();
        return view('admin.components.lecturer', ['lecturer' => $data]);
    }

    public function createLecturer()
    {
        $data = Department::all();

        return view('admin.components.create-lecturer', ['department' => $data]);
    }

    public function editLecturer($id)
    {
        $data = User::find($id);
        $dataDepartment = Department::all();

        return view('admin.components.edit-lecturer', ['lecturer' => $data, 'department' => $dataDepartment]);
    }

    public function saveLecturer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'departments_id' => ['required'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'lecturer',
            'departments_id' => intval($request->departments_id),
        ]);

        event(new Registered($user));

        return redirect()->route('lecturer');
    }

    public function updateLecturer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'departments_id'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']           = $request->name;
        $data['departments_id'] = $request->departments_id;

        User::find($id)->update($data);

        return redirect()->route('lecturer');
    }

    public function deleteLecturer($id)
    {
        User::find($id)->forceDelete();

        return redirect()->route('lecturer');
    }

    public function department()
    {
        $data = Department::all();
        return view('admin.components.department', ['department' => $data]);
    }

    public function createDepartment()
    {
        return view('admin.components.create-department');
    }

    public function editDepartment($id)
    {
        $data = Department::find($id);

        return view('admin.components.edit-department', ['department' => $data]);
    }

    public function saveDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']       = $request->name;

        Department::create($data);

        return redirect()->route('department');
    }

    public function updateDepartment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']       = $request->name;

        Department::find($id)->update($data);

        return redirect()->route('department');
    }

    public function deleteDepartment($id)
    {
        Department::find($id)->forceDelete();

        return redirect()->route('department');
    }

    public function topic()
    {
        $data = Topic::all();
        return view('admin.components.topic', ['topic' => $data]);
    }

    public function createTopic()
    {
        return view('admin.components.create-topic');
    }

    public function editTopic($id)
    {
        $data = Topic::find($id);

        return view('admin.components.edit-topic', ['topic' => $data]);
    }

    public function saveTopic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']       = $request->name;
        $data['priority']   = intval($request->priority);

        Topic::create($data);

        return redirect()->route('topic');
    }

    public function updateTopic(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);;

        $data['name']       = $request->name;
        $data['priority']   = intval($request->priority);

        Topic::find($id)->update($data);

        return redirect()->route('topic');
    }

    public function deleteTopic($id)
    {
        Topic::find($id)->forceDelete();

        return redirect()->route('topic');
    }
}
