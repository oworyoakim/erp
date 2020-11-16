@extends('spms.reports.layout')
@section('content')
    <div class="row" id="activity-monitor-report">
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
                @if(!empty($reportData->responsibilityCenter) || !empty($reportData->quarter))
                    <tr class="by-1">
                        <td>
                            <strong class="float-left">Directorate: </strong>
                            @if(!empty($reportData->responsibilityCenter))
                                <span class="float-right">
                                    {{$reportData->responsibilityCenter}}
                                </span>
                            @endif
                        </td>
                        <td></td>
                        <td>
                            <strong class="float-left">Quarter: </strong>
                            @if(!empty($reportData->quarter))
                                <span class="float-right">
                                    {{$reportData->quarter}}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endif
            </table>
            @foreach($reportData->activityBlocks as $activityBlock)
                <table class="table-bordered table-sm my-2"
                       style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                    <tr>
                        <td colspan="8">
                            <strong class="float-left mr-5">Main Activity: </strong>
                            <span class="ml-10">{{$activityBlock->name}}</span>
                        </td>
                    </tr>
                    @if(count($activityBlock->activities) > 0)
                        @foreach($activityBlock->activities as $activity)
                            <tr>
                                <td colspan="6">
                                    <strong class="float-left">Activity: </strong>
                                    <span class="ml-10">{{$activity->name}}</span>
                                </td>
                                <td colspan="6">
                                    <strong class="float-left">Estimated Cost: </strong>
                                    <span class="ml-10">{{number_format($activity->cost)}}</span>
                                </td>
                            </tr>
                            @if(count($activity->outputs) > 0)
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
                                @foreach($activity->outputs as $output)
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
                            <td colspan="8">No activities!</td>
                        </tr>
                    @endif
                </table>
            @endforeach
        </div>
    </div>
@endsection
