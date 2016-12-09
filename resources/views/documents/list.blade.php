@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>{{ trans('messages.list_documents') }}</h1>
    </div>

    @include('components.errors')

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->title }}</td>
                    <td>
                        {{ Html::linkRoute('documents.edit', trans('messages.edit'), ['document' => $document->id]) }}
                        &middot;
                        {{ Form::open(['route' => ['documents.destroy', $document->id], 'method'
        => 'delete']) }}
                            <button type="submit">{{ trans('messages.delete') }}</button>
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $documents->links() }}
@endsection
