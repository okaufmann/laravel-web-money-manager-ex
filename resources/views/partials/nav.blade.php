<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('/')}}">{{config('app.name')}}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            @if(Auth::check())
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{url('/')}}"><i class="fa fa-usd"></i> Transactions</a>
                    </li>
                    <li>
                        <a href="{{url('/settings')}}"><i class="fa fa-cogs"></i> Settings</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::check())
                        <li>
                            <p class="navbar-text"><i class="fa fa-user"></i> {{$globalUser->name}}</p>
                        </li>
                    @endif
                    {{--<li>--}}
                    {{--<a href="{{url('profile')}}">Profil bearbeiten</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="{{url('logout')}}">Logout</a>
                    </li>
                </ul>
            @endif
        </div><!--/.nav-collapse -->
    </div>
</nav>
