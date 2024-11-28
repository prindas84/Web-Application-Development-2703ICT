<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CourseController;
use App\Models\UserCourses;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'user_number' => ['required', 'string', 'unique:users,user_number', 'max:9'],
            'first_name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $user = User::create([
            'user_number' => $request->user_number,
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'email' => $request->email,
            'role' => 'student',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Fetch the courses from the user_courses table based on the user_number
        $userCourses = UserCourses::where('user_number', $user->user_number)->get();

        // Instantiate the CourseController to reuse its enrollStudent method
        $courseController = new CourseController();

        // Enroll the user in each course found in user_courses
        foreach ($userCourses as $userCourse) {
            $courseController->enrollStudent($request->merge(['user_id' => $user->id]), $userCourse->course_id);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
