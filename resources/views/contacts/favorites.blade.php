@extends('contacts.layout')

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
 
    <div class="row contacts-nav">
        <div class="col-lg-6">
            <a href="{{ URL::to('/') }}" class="float-right color-tq">All contacts</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ route('my_favorites') }}" class="color-tq active-link">My favourites</a>
        </div>
    </div>

    <!-- search -->
    <div class="row" style="padding-top: 30px; padding-bottom: 30px;">
        <div class="col-md-6 offset-md-3">

        {{ Form::open(array('url' => '/search', 'method' => 'post', 'role' => 'search')) }}
     
             {{ csrf_field() }}
                <div class="input-group">
                    {!! Form::text('q', old('q'), ['class' => 'form-control search-contacts-input', 'placeholder' => 'Type and search contact name']) !!} 
                </div>
        {{ Form::close() }}

            
 
         </div>
    </div>
    <!-- end search -->


    <div class="row">
        <div class="col-md-10 offset-md-1">
  
                    @if(!empty($success))
                      <div class="alert alert-success"> {{ $success }}</div>
                    @endif

                    @if(!empty($error))
                      <div class="alert alert-warning"> {{ $error }}</div>
                    @endif

                <div class="row">
    
                        @if(sizeof($favorite_contacts) > 0)

                            @foreach ($favorite_contacts as $favorite)

                           
                                <div class="col-lg-3">
                                    <div class=" contact-box">
                                        
                                        {{ Form::open(array('route' => array('contacts.destroy', $favorite['contact_id'], 'method' => 'post'))) }}
                                     
                                        <div class="row">

                                            <div class="col-md-6"> 

                                                 {{-- contact is favorite, so lets show option to delete from favorite --}}

                                                <!-- Button trigger delete from favourite modal -->
                                                <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#del_favorite_{{ $favorite['contact_id'] }}" style="display: inline; margin: 0px; padding: 0px 20px;">
                                                   <i class="fas fa-heart"></i>
                                                </button>


                                            </div>

                                            <div class="col-md-6">
                                                <a href="{{ route('contacts.edit',$favorite['contact_id']) }}" class="contact-edit-icon"><i class="fas fa-pencil-alt"></i></a>
                                                @csrf
                                                
                                                <!-- Button trigger delete contact modal -->
                                                <button type="button" class="btn btn-secondary contact-delete-icon" data-toggle="modal" data-target="#exampleModal{{ $favorite['contact_id'] }}">
                                                  <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Delete contact Modal -->
                                                <div class="modal fade" id="exampleModal{{ $favorite['contact_id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete contact</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        Caution! Do you really want to delete contact?
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-secondary contact-delete-icon">Delete contact</button>

                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3">
                                                <img class="img-responsive img-fluid rounded-circle" src="{{ URL::to('/') }}/img/{{ $favorite['image'] }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="text-center contact-info">
                                                    <a class="contact-name" href="{{ route('contacts.show',$favorite['contact_id']) }}">{{ $favorite['first_name'] }} {{ $favorite['last_name'] }}</a> 
                                                </h6>
                                            </div>
                                        </div>
                                         
                                        
                                    {{ Form::close() }}
                                    </div>
                                </div>
                            @endforeach
                     
                            @else
                                <div class="alert alert-alert">Start adding contacts to my favorites.</div>
                            @endif

                  
                     
                </div>
        </div>
    </div>


      @if(sizeof($favorite_contacts) > 0)
    <!-- delete from favourite Modal -->
    <div class="modal fade" id="del_favorite_{{ $favorite['contact_id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Remove contact from my favorites</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             Do you want to remove this contact from your favorites?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                

            {{ Form::open(array('action' => 'ContactController@removeFavorite', 'method' => 'post')) }}
      
                @csrf
                
                <input type="hidden" id="contact_id" name="contact_id" value="{{ $favorite['contact_id'] }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">

                <button type="submit" class="btn btn-secondary contact-delete-icon">Remove from my favorite</button> 
                

            {!!Form::close() !!}   

          </div>
        </div>
      </div>
    </div>  
    @endif

@endsection