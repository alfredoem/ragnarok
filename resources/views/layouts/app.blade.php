<!DOCTYPE html>
<html lang="en">
<head>
    @include('Ragnarok::layouts.common.head')

    @yield('style')
</head>
<body>
    <!-- Vue App For Spark Screens -->
    <div id="container-fluid">
        <!-- Navigation -->

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->

        <!-- JavaScript Application -->
        @yield('script')
    </div>
</body>
</html>
