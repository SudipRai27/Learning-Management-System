<div class="modal fade" id="viewSlider{{$d->id}}" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true" style="">
    <div class="modal-dialog" style="width: 65%;">
     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title text-center">Slider Details</h4>
            </div>
            <?php
                $slider_resource = (new \App\Resource)->getResource($d->id, 'slider');
            ?>
            <div class="modal-body">   
                @if(count($slider_resource))
                    @foreach($slider_resource as $index => $resource)
                    <div class="image" style="width: 100%;"> 
                        <img src="{{$resource['s3_url']}}" style="width: 100% !important; height:auto;"> 
                    </div>
                    @endforeach
                @endif                
                <div class="profile-detail" style="margin-top: 2rem">
                    <div class="main-head" style="text-align:center">    
                        {{$d->title}}           
                    </div>
                    <div class="second-head" style="margin-top:2rem;">
                        <span style="color:#333">{!!$d->description!!}</span> 
                    </div>
                
                </div>                    
            </div>
            <div class="modal-footer">
                <center>                
                </center>
            </div>
        </div> 
    </div>
</div>					


