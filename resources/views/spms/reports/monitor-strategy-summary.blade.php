@extends('spms.reports.layout')
@section('content')
    <div class="row" id="strategic-plan-monitor-report">
        <div class="col-12 table-responsive"
             style="display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <p class="text-center mb-2">
                <img src="data:image/png;base64,{{$reportData->companyLogo}}" alt="UNEB-SPMS"/>
            </p>
            <h3 class="text-center mb-3">UGANDA NATIONAL EXAMINATIONS BOARD</h3>
            <h4 class="text-center mb-3" style="font-weight: bold;  text-transform: capitalize;">
                PERFORMANCE REPORT FOR THE UNEB {{$reportData->plan}}
            </h4>
            <table class="table-borderless my-2" style="width: 100%;">
                <tr class="by-1">
                    <td>
                        <strong class="float-left">Report Period: </strong>
                        <span class="float-right">
                            {{$reportData->startDate}} {{$reportData->endDate}}
                        </span>
                    </td>
                    <td>
                        <strong class="float-left">Report Frequency</strong>
                        <span class="float-right">{{$reportData->reportFrequency}}</span>
                    </td>
                    <td>
                        <strong class="float-left">Report Date</strong>
                        <span class="float-right">{{$reportData->reportDate}}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
