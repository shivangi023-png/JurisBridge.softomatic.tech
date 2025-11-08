 @foreach($clients_list as $row)
 <div class="clients_mycases">
 <a href="javascript:void(0)" class="collapsed main" data-toggle="collapse" data-target="#collapse{{$row->id}}" aria-expanded="false" aria-controls="collapse{{$row->id}}">
        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card shadow">
                <div class="card-body client_card">
                  <div class="row">
                        <div class="col col-xs-8 col-md-8 col-lg-8">
                           <h6 class="card-title client_name">{{$row->case_no}}</h6>
                           <p class="text-muted">{{$row->client_name}}</p>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
      </div>
    </a>
    <div class="card">
     <div id="collapse{{$row->id}}" class="collapse" aria-labelledby="heading{{$row->id}}" data-parent="#accordion">
        <div class="card-body">
        @if($row->mycases_list != '[]')
          <ul class="list-group">
        @foreach($row->mycases_list as $row1)
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center click_case_no" data-case_id="{{$row1->id}}" data-case_no="{{$row1->case_no}}" data-client_id="{{$row1->client_id}}" data-description="{{$row1->description}}">
          <div class="flex-column">
           {{$row1->case_no}}
          </div>
           <div class="image-parent">
            <span> 12:01 </span>
            <span class="badge badge-success badge-pill "> 5 </span>
          </div>
        </a>
        @endforeach
         </ul>
        @else
        <p class="text-danger">Not Available Cases</p>
        @endif
        </div>
      </div>
    </div>
<!-- <hr> -->
<div>
@endforeach