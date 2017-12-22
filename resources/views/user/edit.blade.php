@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit your profile</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('user.update', $user) }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input name="name" type="text" class="form-control" id="name" placeholder="Name"
                                           value="{{ $user->name }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input name="email" type="email" class="form-control" id="email"
                                           value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input name="password" type="password" class="form-control" id="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="objective" class="col-sm-2 control-label">Objective to reached</label>
                                <div class="col-sm-10">
                                    <input name="objective" type="number" class="form-control"
                                           id="objective" value="{{ $user->objective }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alert" class="col-sm-2 control-label">Alert me when delta is under</label>
                                <div class="col-sm-10">
                                    <input name="alert" type="number" class="form-control"
                                           id="alert" value="{{ $user->alert }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notification" class="col-sm-2 control-label">Notifications</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label>
                                            <input name="notification" type="radio"
                                                   {{ $user->notification ? 'checked' : '' }} value="1">
                                            Yes
                                        </label>
                                        <label>
                                            <input name="notification" type="radio"
                                                   {{ !$user->notification ? 'checked' : '' }} value="0">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection