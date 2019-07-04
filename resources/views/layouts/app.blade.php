<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href=" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"  crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.6/datatables.min.css"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
	label{margin-left: 20px;}
#datepicker{width:180px; margin: 0 20px 20px 20px;}
#datepicker > span:hover{cursor: pointer;}
</style>
        
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'CFTool') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php 
                        $submissions = (object) DB::table('submissions')->get();
                        //var_dump($submissions);
                        $curtime = strtotime('today 1am');
                        $d = date('m-d-Y H:i:s',$curtime);
                        $begin = \Carbon\Carbon::createFromFormat('m-d-Y H:i:s',$d)->timestamp - 3599; 
                        $curtime = strtotime('today 11pm');
                        $d = date('m-d-Y H:i:s',$curtime);
                        $end = \Carbon\Carbon::createFromFormat('m-d-Y H:i:s',$d)->timestamp + 3599; 
                        //$sessionStart = \Carbon\Carbon::createFromFormat('m-d-Y H:i:s',date('m-d-Y','06-29-2019'))->timestamp;
                        $sessionStart = 1561803854;
                        $sessionEnd = \Carbon\Carbon::createFromFormat('m-d-Y H:i:s',date('m-d-Y H:i:s'))->timestamp;
                        $dayCnt = 0;
                        $monthCnt = 0;
                        foreach($submissions as $single){
                            if($single->verdict === "OK"){
                                $submitTime = $single->creationTimeSeconds;
           
                                if($submitTime >= $begin && $submitTime <= $end){
                                    //var_dump($single["creationTimeSeconds"]);
                                    $dayCnt++;
                                }
                                if($submitTime >= $sessionStart && $submitTime <= $sessionEnd){
                                    $monthCnt++;
                                }
                                
                            }
                        }
                    ?>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest
                    @else

                    <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('/') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('/contest') }}">{{ __('Contest') }}</a>
                            </li>
                        
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Update <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('/submission/update') }}">
                                        {{ __('Update Submission') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('/contest/update') }}">
                                        {{ __('Update Contest') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('/problem/update') }}">
                                        {{ __('Update Problem') }}
                                    </a>
                                </div>
                         
                            </li>
                          
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Sgtlaugh <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('/submissionZahin') }}">
                                
                                        {{ __('Submission') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('/updateZahin') }}">
                                
                                        {{ __('Update') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                           
                       
                    </ul>
                   
                    <ul class="navbar-nav">
                        <li class="nav-item">Accpeted Today : <span>{{$dayCnt}} <span></li> &nbsp; &nbsp; &nbsp;
                        <li class="nav-item">Accpeted Till Now : <span>{{$monthCnt}} <span></li>
                    </ul>
                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <script  src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">

$('.input-group.date').datepicker({
});
function toggleFunction(id) {
  var htmId = "expand"+id;
  var hideId = "hide"+id
  var x = document.getElementById(htmId);
  var y = document.getElementById(hideId);
  console.log(hideId);
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display ="none"
  } else {
    x.style.display = "none";
  }
} 
$("#table tr").click(function(){
   $(this).addClass('selected').siblings().removeClass('selected');    
   
});
</script>
</body>
</html>
