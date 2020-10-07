<div class="modal fade" id="viewAdmin{{$admin->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog ">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Admin Details</h4>
            </div>
            <div class="modal-body">                 
                <div class="profile-image">            
                    @if($admin->photo)
                    <img class="img-responsive" src = "{{url('/modules/admin/resources/assets/images').'/'. $admin->photo}}" >
                    @else
                    <img class="img-responsive" src = "" >  
                @endif      
                </div>
                <div class="profile-detail">
                    <div class="main-head" style="text-align:center">               
                    </div>                    
                    <ul>
                        <li>
                            <label> Full Name: </label>
                            <span class="text-green"> {{$admin->name}}</span>
                        </li>                        
                        
                        <li>
                            <label>Email:</label> 
                            <span class="text-green"> {{$admin->email}}</span>
                        </li>
                        <li>
                            <label>DOB:</label> 
                            <span class="text-green">{{$admin->dob}}</span>
                        </li>               
                        <li>
                            <label>Address:</label> 
                            <span class="text-green"> {{$admin->address}} </span>
                        </li>
                        <li>
                            <label>Phone Number:</label> 
                            <span class="text-green">  {{$admin->phone}}</span>
                        </li>
                        <li>
                            <label>Emergency Contact Name: </label> 
                            <span class="text-green"> {{$admin->emergency_contact_name}} </span>
                        </li>
                        <li>
                            <label>Emergency Contact Information: 
                            <span class="text-green"> {{$admin->emergency_contact_number}} </span>
                        </li>               
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <center>                    
                </center>
            </div>
        </div> 
    </div>
</div>                  


