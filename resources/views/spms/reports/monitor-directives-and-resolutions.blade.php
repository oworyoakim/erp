@extends('spms.reports.layout')
@section('content')
    <div class="row" id="directives-and-resolutions-monitor-report">
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
            @foreach($reportData->directivesAndResolutions as $directiveAndResolution)
                <table class="table-bordered table-sm my-2"
                       style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                    <tr>
                        <th colspan="2" class="text-left">Directive or Resolution</th>
                        <td colspan="6">{{$directiveAndResolution->title}}</td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-left">Responsibility Center</th>
                        <td colspan="6">
                            @if(!empty($directiveAndResolution->responsibilityCenter))
                                {{$directiveAndResolution->responsibilityCenter}}
                            @endif
                        </td>
                    </tr>
                    @if(count($directiveAndResolution->activities) > 0)
                        <tr class="text-center">
                            <th>Activity</th>
                            <th>Output</th>
                            <th>MA</th>
                            <th>TA</th>
                            <th>AV</th>
                            <th>PA</th>
                            <th>VA</th>
                            <th>Comments</th>
                        </tr>
                        @foreach($directiveAndResolution->activities as $activity)
                            @if(count($activity->outputs) > 0)
                                <tr>
                                    <td rowspan="{{count($activity->outputs) + 1}}">{{$activity->title}}</td>
                                </tr>
                                @foreach($activity->outputs as $output)
                                    <tr>
                                        <td>{{$output->title}}</td>
                                        <td class="text-center">
                                            @if($output->unit == 'percent')
                                                <span>%</span>
                                            @else
                                                <span>CT</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$output->target}}</td>
                                        <td class="text-center">{{$output->actual}}</td>
                                        <td class="text-center">{{$output->achieved}}</td>
                                        <td class="text-center">{{$output->variance}}</td>
                                        <td>{{$output->comments}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>{{$activity->title}}</td>
                                    <td colspan="7">No outputs!</td>
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
