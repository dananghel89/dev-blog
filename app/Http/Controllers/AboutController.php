<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $techStack = [
            'PHP' => ['laravel', 'wordpress', 'vanilla'],
            'MySQL' => ['database design', 'optimization', 'queries'],
            'Frontend' => ['HTML5', 'CSS3', 'JavaScript', 'Vue.js'],
            'Tools' => ['Git', 'Composer', 'npm', 'VS Code'],
        ];

        $stats = [
            'years_experience' => 5,
            'posts_written' => 42,
            'technologies' => 15,
            'countries_reached' => 30,
        ];

        // Your photo when public/images/avatar.jpg exists, the neutral placeholder otherwise.
        $avatar = is_file(public_path('images/avatar.jpg'))
            ? asset('images/avatar.jpg')
            : asset('images/placeholders/avatar.svg');

        return view('pages.about', compact('techStack', 'stats', 'avatar'));
    }
}
