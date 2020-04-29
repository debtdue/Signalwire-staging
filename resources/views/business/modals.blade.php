

<!-- Modal-->
<div class="modal fade" id="myModal-{{$business->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel-{{$business->id}}">{{ucwords($business->title)}} Agents</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                                
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover agents-details-modal" >
                        <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Agent</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $counters = 1;?>
                        @foreach( $business->agents as $agent)
                            <tr class="gradeC">
                                <td>{{$counters++}}</td>
                                <td>{{ $agent->first_name }}</td>
                                <td>{{ $agent->phone_number }}</td>
                                <td>{{ $agent->email }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

