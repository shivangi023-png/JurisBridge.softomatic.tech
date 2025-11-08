@extends('task_layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Task Management')
{{-- vendor styles --}}
@section('vendor-styles')

@endsection
{{-- page style --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/app-todo.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/task.css') }}">
<link rel="stylesheet" type="text/css"
  href="{{ asset('css/datepicker/css/bootstrap-datepicker.css') }}">
@endsection
{{-- page content --}}
@section('content')
<style>
  .TimelineStartEndBtn h4 {
    background-color: #c3ddff;
    padding: 8px;
    display: inline-block;
    font-size: 13px;
    border-radius: 6px;
    color: #000;
    margin-left: 180px;
  }

  .timeline_area {
    position: relative;
    z-index: 1;
  }

  .single-timeline-area {
    position: relative;
    z-index: 1;
    padding-left: 180px;
  }

  @media only screen and (max-width: 575px) {
    .single-timeline-area {
      padding-left: 100px;
    }
  }

  .single-timeline-area .timeline-date {
    position: absolute;
    width: 180px;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -ms-grid-row-align: center;
    align-items: center;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    padding-right: 60px;
  }

  @media only screen and (max-width: 575px) {
    .single-timeline-area .timeline-date {
      width: 100px;
    }
  }

  .single-timeline-area .timeline-date::after {
    position: absolute;
    width: 3px;
    height: 100%;
    content: "";
    background-color: #ebebeb;
    top: 0;
    right: 30px;
    z-index: 1;
  }

  .single-timeline-area .timeline-date::before {
    position: absolute;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background-color: #5a8dee;
    content: "";
    top: 50%;
    right: 26px;
    z-index: 5;
    margin-top: -5.5px;
  }

  .single-timeline-area .timeline-date p {
    margin-bottom: 0;
    color: #020710;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 500;
  }

  .single-timeline-area .single-timeline-content {
    position: relative;
    z-index: 1;
    padding: 20px 30px 16px;
    border-radius: 6px;
    margin-bottom: 15px;
    margin-top: 15px;
    -webkit-box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
    box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
    border: 1px solid #ebebeb;
  }

  @media only screen and (max-width: 575px) {
    .single-timeline-area .single-timeline-content {
      padding: 20px;
    }
  }

  .single-timeline-area .single-timeline-content .timeline-icon {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    width: 30px;
    height: 30px;
    background-color: #ed7171;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 30px;
    flex: 0 0 30px;
    text-align: center;
    max-width: 30px;
    border-radius: 50%;
    margin-right: 15px;
  }

  .single-timeline-area .single-timeline-content .timeline-icon i {
    color: #ffffff;
    line-height: 30px;
  }

  .single-timeline-area .single-timeline-content .timeline-text h6 {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
  }

  .single-timeline-area .single-timeline-content .timeline-text p {
    font-size: 13px;
    margin-bottom: 6px;
  }

  .timeline-text h5 {
    font-size: 12px;
  }

  .single-timeline-area .single-timeline-content:hover .timeline-icon,
  .single-timeline-area .single-timeline-content:focus .timeline-icon {
    background-color: #719df0;
  }

  .single-timeline-area .single-timeline-content:hover .timeline-text h6,
  .single-timeline-area .single-timeline-content:focus .timeline-text h6 {
    color: #719df0;
  }
  .scrollable-timeline {
    max-height: 800px; /* You can adjust this value */
    overflow-y: auto;
    padding-right: 15px;
  }
</style>
<div id="alert" class="mt-1"></div>
<!-- <section id="table-success"> 
  <div class="card _card">
  </div>
</section> -->
<section class="timeline_area section_padding_130">
  <div class="container">
    <br>
    <div class="row">
      <div class="col-12">
        <!-- Timeline Area-->
        <div class="scrollable-timeline">
        @foreach($finalProjectTaskData as $project)
        <div class="apland-timeline-area TimelineStartEndBtn">
          <!-- Project start-->
          <div class="single-timeline-area">
            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s"
              style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
              <p>{{ date('d M Y',strtotime($project['start_date'])) }}</p>
            </div>
            <div class="row">
              <div class="col-lg-11">
                <h4>{{ $project['project_name'] }}</h4>
              </div>
            </div>
          </div>
          <!-- Project start-->

          <!-- Single Timeline Content-->
          @foreach($project['tasks'] as $task)
          @foreach($task['task_types'] as $taskType)
          <div class="single-timeline-area">
            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s"
              style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
              <p>{{ $task['task_start_date'] }} - <br/>{{ $task['task_end_date'] }}</p>
            </div>
            <div class="row">
              <div class="col-lg-11">
                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s"
                  style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                  <div class="timeline-icon">
                    <i class="bx bx-check-shield" aria-hidden="true"></i>
                  </div>
                  <div class="timeline-text">
                    <div class="row">
                    <div class="col-lg-10">
                    <h6>{{ $task['title'] }} - ({{ $taskType['task_type_name'] }})</h6>
                    </div>
                    <div class="col-lg-2"><span class="badge rounded-pill bg-secondary float-right">{{ $task['priority'] }}</span></div>
                    </div>
                    <span>Assignee : {{ $task['assignee_names'] }} - {{ date('d M,Y',strtotime($task['assign_date'])) }}</span>
                    @if($task['created_by_name'])
                    <h5>Created By : <span class="text-primary"> {{ $task['created_by_name'] }}</span></h5>
                    @endif
                    @if($task['updated_by_name'])
                    <h5>Updated By : <span class="text-primary"> {{ $task['updated_by_name'] }}</span></h5>
                    @endif
                    <div class="row">
                    @foreach($taskType['statuses'] as $status)
                      <div class="col-lg-6">
                        <span class="badge badge-success">{{ $status['task_status'] }}</span>
                      </div>
                    @endforeach
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          @endforeach
          <!-- Single Timeline Content-->
          <!-- Project start-->
          <div class="single-timeline-area">
            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s"
              style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
              <p>{{ date('d M, Y',strtotime($project['end_date'])) }}</p>
            </div>
            <div class="row">
              <div class="col-lg-11">
               <h4>Project Completed</h4>
              </div>
            </div>
          </div>
          <!-- Project start-->
        </div>
        @endforeach
        </div>
      </div>
    </div>
  </div>
</section>




@endsection
@section('vendor-scripts')

@endsection

@section('page-scripts')

@endsection
