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
                        <strong class="float-left">Report End Period: </strong>
                        <span class="float-right">
                            {{$reportData->dateParams->currentQuarter->name}} {{$reportData->dateParams->financialYear}}
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
            @foreach($reportData->objectives as $objective)
                <table class="table-bordered table-sm my-2"
                       style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                    <tr>
                        <td colspan="8">
                            <strong class="float-left mr-5">Strategic Objective: </strong>
                            <span class="ml-10">{{$objective->name}}</span>
                        </td>
                    </tr>
                    @if(count($objective->interventions) > 0)
                        @foreach($objective->interventions as $intervention)
                            <tr>
                                <td colspan="8">
                                    <strong class="float-left">Strategic Intervention: </strong>
                                    <span class="ml-10">{{$intervention->name}}</span>
                                </td>
                            </tr>
                            @if(count($intervention->outputs) > 0)
                                <tr style="text-align: center; vertical-align: center;">
                                    <th>Output</th>
                                    <th>Indicator</th>
                                    <th>MA</th>
                                    <th>TA</th>
                                    <th>AV</th>
                                    <th>PA</th>
                                    <th>VA</th>
                                    <th>Comments</th>
                                </tr>
                                @foreach($intervention->outputs as $output)
                                    @if(count($output->indicators) > 0)
                                        <tr>
                                            <td rowspan="{{count($output->indicators) + 1}}" style="font-weight: bold;">
                                                {{$output->name}}
                                            </td>
                                        </tr>
                                        @foreach($output->indicators as $indicator)
                                            <tr>
                                                <td>{{$indicator->name}}</td>
                                                <td class="text-center" style="text-align: center;">
                                                    @if($indicator->unit === 'percent')
                                                        <span>%</span>
                                                    @else
                                                        <span>CT</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">{{$indicator->target}}</td>
                                                <td style="text-align: center;">{{$indicator->actual}}</td>
                                                <td style="text-align: center;">{{$indicator->achieved}}</td>
                                                <td style="text-align: center;">{{$indicator->variance}}</td>
                                                <td style="width: 25%;">{{$indicator->comments}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{$output->name}}</td>
                                            <td colspan="7">No indicators!</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">No outputs!</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No interventions!</td>
                        </tr>
                    @endif
                </table>
            @endforeach
        </div>
    </div>
@endsection
