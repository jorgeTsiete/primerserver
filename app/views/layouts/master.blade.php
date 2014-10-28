<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@section('title')
            Primer Server
            @show
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{ HTML::style('css/bootstrap.min.css') }}
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        {{HTML::style("css/bootstrap-theme.min.css")}}
        {{HTML::style("css/main.css")}}

        {{HTML::script("js/vendor/modernizr-2.6.2-respond-1.1.0.min.js")}}
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    @if(Auth::check())
                    {{ HTML::LinkRoute('user.show','Primer Server',array(Auth::user()->id),array('class'=>'navbar-brand')) }}
                    @else
                    {{ HTML::Link('/','Primer Server',array('class'=>'navbar-brand')) }}
                    @endif

                </div>
                @if(Auth::check())
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{Auth::user()->email}} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Cuenta</a></li>
                            <li><a href="#">Dominios</a></li>
                            <li>{{HTML::LinkRoute('session.destroy',trans('frontend.system.logout'))}}</li>
                                                       
                        </ul>
                    </li>
                </ul>
                @else
                <div class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right" role="form">
                        <div class="form-group">
                            <input type="text" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form>
                </div><!--/.navbar-collapse -->
                @endif

            </div>
        </div>

        @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
            {{ Session::forget('message'); }}        
        </div>                        
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
            {{ Session::forget('error'); }}
        </div>                    
        @endif

        @yield('contenido')

        <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.js"><\/script>')</script>

        {{HTML::script("js/vendor/bootstrap.min.js")}}

        {{HTML::script("js/main.js")}}

        @section('scripts')@show

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
                      (function (b, o, i, l, e, r) {
                          b.GoogleAnalyticsObject = l;
                          b[l] || (b[l] =
                                  function () {
                                      (b[l].q = b[l].q || []).push(arguments)
                                  });
                          b[l].l = +new Date;
                          e = o.createElement(i);
                          r = o.getElementsByTagName(i)[0];
                          e.src = '//www.google-analytics.com/analytics.js';
                          r.parentNode.insertBefore(e, r)
                      }(window, document, 'script', 'ga'));
              ga('create', 'UA-XXXXX-X');
              ga('send', 'pageview');
        </script>
    </body>
</html>
