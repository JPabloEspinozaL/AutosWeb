<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// Rutas para Usuarios (Admin)
Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
// --- RUTA PRINCIPAL (LOGIN) ---
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// --- PROCESAR LOGIN ---
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// --- CERRAR SESIÓN ---
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- DASHBOARD (PROTEGIDO) ---
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- RUTAS DE VENTA ---
Route::post('/comprar', [DashboardController::class, 'comprar'])->name('venta.post');

// --- RUTAS DE VEHÍCULOS (ADMIN) ---
Route::get('/vehiculos/crear', [DashboardController::class, 'createVehiculo'])->name('vehiculos.create');
Route::post('/vehiculos', [DashboardController::class, 'storeVehiculo'])->name('vehiculos.store');
Route::get('/vehiculos/{vin}/editar', [DashboardController::class, 'editVehiculo'])->name('vehiculos.edit');
Route::put('/vehiculos/{vin}', [DashboardController::class, 'updateVehiculo'])->name('vehiculos.update');