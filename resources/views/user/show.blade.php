@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ $user->name }}</div>
                    <div class="card-body">
                        <b>My Moto:</b><p>{{ $user->motto }}</p>
                        <b>About Me:</b><p>{{ $user->about_me }}</p>
                    
                    @isset($hobbies)
                    @if ( count($hobbies) > 0)
                        <h3>{{ $user->name }} Hobbies:</h3>
                    @endif
                    <ul class="list-group">
                        @foreach ($hobbies as $hobby)
                            <li class="list-group-item">
                                <a title="Show Details" href="/hobby/{{ $hobby->id }}">{{ $hobby->name }}</a>
                                <SPan class="float-right mx-2">{{ $hobby->created_at->diffForHumans() }}</SPan>
                                <br>
                                @foreach ($hobby->tags as $tag)
                                    <a href="/hobby/tag/{{ $tag->id }}"><span
                                            class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                @endisset
            </div>
                </div>
                <div class="mt-4">
                    <a href="{{ URL::previous() }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-circle-up"></i> All Hobbies</a>
                </div>
            </div>
        </div>
    </div>
@endsection
