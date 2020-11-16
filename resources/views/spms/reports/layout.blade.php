<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Enterprise Resource Planner">
    <meta name="keywords" content="Access Control, ERP, HRMS, SPMS">
    <meta name="author" content="Owor Yoakim">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <style type="text/css">
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .text-bold {
            font-weight: bolder !important;
        }

        .font-weight-bolder {
            font-weight: bolder !important;
        }

        .b-1 {
            border: 1px solid #dee2e6 !important;
        }
        .bt-1,
        .by-1{
            border-top: 1px solid #dee2e6 !important;
        }
        .bb-1,
        .by-1{
            border-bottom: 1px solid #dee2e6 !important;
        }
        .bl-1,
        .bx-1 {
            border-left: 1px solid #dee2e6  !important;
        }
        .br-1,
        .bx-1{
            border-right: 1px solid #dee2e6 !important;
        }

        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
            border: 0;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive > .table-bordered {
            border: 0;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }
        .text-left {
            text-align: left !important;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        small,
        .small {
            font-size: 80%;
            font-weight: 400;
        }
        .border-0 {
            border: 0 !important;
        }
        .border-none {
            border: none !important;
        }

        .border-bottom-none {
            border-bottom: none !important;
        }

        .border-top-none {
            border-top: none !important;
        }

        .border-right-none {
            border-right: none !important;
        }

        .border-left-none {
            border-left: none !important;
        }

        .mb-5,
        .my-5 {
            margin-bottom: 3rem !important;
        }
        .mr-5,
        .mx-5 {
            margin-right: 3rem !important;
        }

        .mt-5,
        .my-5 {
            margin-top: 3rem !important;
        }

        .ml-5,
        .mx-5 {
            margin-left: 3rem !important;
        }

        .m-2 {
            margin: 0.5rem !important;
        }

        .mt-2,
        .my-2 {
            margin-top: 0.5rem !important;
        }

        .mr-2,
        .mx-2 {
            margin-right: 0.5rem !important;
        }

        .mb-2,
        .my-2 {
            margin-bottom: 0.5rem !important;
        }

        .ml-2,
        .mx-2 {
            margin-left: 0.5rem !important;
        }

        .mr-1,
        .mx-1 {
            margin-right: 0.25rem !important;
        }

        .ml-1,
        .mx-1 {
            margin-left: 0.25rem !important;
        }

        .mt-1,
        .my-1 {
            margin-top: 0.25rem !important;
        }

        .mb-1,
        .my-1 {
            margin-bottom: 0.25rem !important;
        }

        .mr-10,
        .mx-10 {
            margin-right: 6rem !important;
        }

        .ml-10,
        .mx-10 {
            margin-left: 6rem !important;
        }

        .mt-10,
        .my-10 {
            margin-top: 6rem !important;
        }

        .mb-10,
        .my-10 {
            margin-bottom: 6rem !important;
        }

        .m-3 {
            margin: 1rem !important;
        }

        .mt-3,
        .my-3 {
            margin-top: 1rem !important;
        }

        .mr-3,
        .mx-3 {
            margin-right: 1rem !important;
        }

        .mb-3,
        .my-3 {
            margin-bottom: 1rem !important;
        }

        .ml-3,
        .mx-3 {
            margin-left: 1rem !important;
        }
        .float-left{
            float: left !important;
        }
        .float-right{
            float: right !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    @yield('content')
</div>
</body>
</html>
