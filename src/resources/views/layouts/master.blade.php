<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>easyPOS | @yield('title')</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/navbar.css">  
    <link rel="stylesheet" href="/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="/css/fa-solid.min.css">  
    <link rel="stylesheet" href="/css/jquery-ui.min.css">    
    <link rel="stylesheet" href="@yield('css_file2')">     
    <link rel="stylesheet" href="@yield('css_file')">
    <link rel="stylesheet" href="/css/footer.css">
</head>
<body>
    @include('includes.navbar')
    @include('includes.message')

    <div class="container-fluid">
        @yield('content')
        {{-- @include('includes.footer') --}}
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script> 
    <script src="/js/bootstrap.min.js""></script> 
    <script src="/js/jquery-ui.min.js"></script>
     <script src="/js/master.js"></script>  
     <script src="/js/notifier.js"></script>
    <script src="/js/main.js"></script>   
    <script src="@yield('script')"></script> 
    <script src="@yield('script2')"></script>  
</body>
</html>