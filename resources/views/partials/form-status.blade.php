@if (session('status'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            &times;
        </button>
        {{ session()->pull('status') }}
    </div>
@endif