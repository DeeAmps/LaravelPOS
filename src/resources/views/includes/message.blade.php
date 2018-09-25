@if(Session::has('message'))
    <div class="row">
        <div class="col-md-12">
            {{session()->get('message')}}
        </div>
    </div>
@endif