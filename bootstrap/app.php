<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => App\Http\Middleware\RoleMiddleware::class,
            'account.active' => App\Http\Middleware\EnsureAccountIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()
                ->route('login')
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->withErrors([
                    'login' => 'Sesi login kedaluwarsa. Silakan muat ulang halaman lalu coba login kembali.',
                ]);
        });

        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return redirect()
                ->back()
                ->withInput($request->except(['file_dokumen', 'file_project', 'file_excel', 'file']))
                ->withErrors([
                    'file_dokumen' => 'Ukuran file atau total data yang diunggah terlalu besar. Maksimal file PDF 10 MB dan file Project (ZIP/RAR) 800 MB.',
                ])
                ->with('error', 'Ukuran file yang diunggah melebihi batas maksimum server (Maksimal PDF 10 MB, Project 800 MB).');
        });
    })->create();
