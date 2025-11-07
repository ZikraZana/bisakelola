@extends('layouts.layout')

@section('title')
    Dashboard Admin
@endsection

@section('title_nav')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Halo {{ Auth::user()->role }}!</h4>
                        <p class="card-text">Selamat datang di dashboard admin Bisakelola.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
