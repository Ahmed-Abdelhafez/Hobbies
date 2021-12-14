@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">{{ $user->name }}</div>
                    <div class="card-body">
                        <b>My Moto:</b><p>{{ $user->motto }}</p>
                        <b>About Me:</b><p>{{ $user->about_me }}</p>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ URL::previous() }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-circle-up"></i> All Hobbies</a>
                </div>
            </div>
        </div>
    </div>
@endsection
