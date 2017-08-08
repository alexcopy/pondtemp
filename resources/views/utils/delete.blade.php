<form action="{{ $url or Request::url() }}" method="POST">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type='submit' class="{{ $class or 'btn btn-danger' }}" value="{{ $value or 'delete' }}">{!! $text or 'delete' !!}</button>
</form>