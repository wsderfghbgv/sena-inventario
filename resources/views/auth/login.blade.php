@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 p-3" style="width: 70px; height: 70px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#39A900" class="bi bi-shield-lock" viewBox="0 0 16 16">
                            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.546-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                            <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 1 1 9.5 6.5"/>
                        </svg>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #39A900;">Iniciar Sesión</h3>
                    <p class="text-muted small">Sistema de Gestión de Inventario SENA / SENNOVA</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">Correo Institucional:</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="ejemplo@sena.edu.co" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="••••••••" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label class="form-check-label small text-muted" for="remember">Recordar mi sesión</label>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Ingresar al Sistema</button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-white border-0 text-center py-3">
                <small class="text-muted">Centro de Formación SENNOVA &bull; Gestión Segura</small>
            </div>
        </div>
    </div>
</div>
@endsection
