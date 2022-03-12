@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <form method="POST" action="{{ route('admin.regions.update', $region) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $region->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="slug" class="col-form-label">Slug</label>
            <input id="slug" type="email" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ old('slug', $region->slug) }}" required>
            @if ($errors->has('slug'))
                <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
            @endif
        </div>

        {{--<div class="form-group">
            <label for="role" class="col-form-label">Role</label>
            <select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role">
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}"{{ $value === old('role', $user->role) ? ' selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if ($errors->has('role'))
                <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
            @endif
        </div>--}}

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
