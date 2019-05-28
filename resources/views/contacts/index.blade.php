@extends('contacts.layout')

@section('content')

    <div class="row contacts-nav">
        <div class="col-lg-6">
            <a href="{{ URL::to('/') }}" class="float-right color-tq active-link">All contacts</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ route('my_favorites') }}" class="color-tq">My favourites</a>
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
  
                    <div class="col-md-3">
                        <div class=" contact-box add-new-contact">
                          <a href="{{ route('contacts.create') }}" class="align-middle color-tq"> <i class="fas fa-plus"></i> <br />Add Contact</a>
                        </div>
                    </div>
  
                         
                        @if(sizeof($contacts) > 0)

                            @foreach ($contacts as $contact)
                                <div class="col-lg-3">
                                    <div class=" contact-box">
                                        
                                        {{ Form::open(array('route' => array('contacts.destroy', $contact->id, 'method' => 'post'))) }}
                                     
                                        <div class="row">
                                            <div class="col-6">
  

                                                @guest

                                                <!-- Button trigger to log in modal -->
                                                <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#log_in" style="display: inline; margin: 0px; padding: 0px 20px;">
                                                  <i class="far fa-heart"></i>
                                                </button>

                                                @endguest

                                                @auth

                                                <!-- Button trigger add to favourite modal -->
                                                <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#add_favorite_{{ $contact->id }}">
                                                  <i class="far fa-heart"></i>
                                                </button>

                                                 
                                                @endauth
                                            

                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('contacts.edit',$contact->id) }}" class="contact-edit-icon"><i class="fas fa-pencil-alt"></i></a>
                                                @csrf
                                                
                                                <!-- Button trigger delete contact modal -->
                                                <button type="button" class="btn btn-secondary contact-delete-icon" data-toggle="modal" data-target="#exampleModal{{ $contact->id }}">
                                                  <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Delete contact Modal -->
                                                <div class="modal fade" id="exampleModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <img class="img-responsive img-fluid rounded-circle" src="{{ URL::to('/') }}/img/{{ $contact->image }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="text-center contact-info">
                                                    <a class="contact-name" href="{{ route('contacts.show',$contact->id) }}">{{ $contact->first_name }} {{ $contact->last_name }}</a> 
                                                </h6>
                                            </div>
                                        </div>
                                         
                                        
                                    {{ Form::close() }}
                                    </div>
                                </div>
                            @endforeach
                        
                     
                            @else
                                <div class="alert alert-alert">Start adding contacts to the Database.</div>
                            @endif

                  
                     
                </div>
        </div>
    </div>

    @auth
    @if(sizeof($contacts) > 0)

        @foreach ($contacts as $contact)
    <!-- add to favourite Modal -->
    <div class="modal fade" id="add_favorite_{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add to favorite contact</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             Do you want to add this contact to your favorites?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                
            {{ Form::open(array('action' => 'ContactController@addFavorite', 'method' => 'post')) }}
      
                @csrf
                
                <input type="hidden" id="contact_id" name="contact_id" value="{{ $contact->id }}">
                                                                 
                <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
        
                <button type="submit" class="btn btn-secondary contact-delete-icon">Add to favorite</button> 
                

            {{ Form::close() }}   


          </div>
        </div>
      </div>
    </div>  

    @endforeach

@endif


    @endauth

    
    @guest 
    <!-- log in Modal -->
    <div class="modal fade" id="log_in" tabindex="-1" role="dialog" aria-labelledby="log_in" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="log_in">Please log-in</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             This option is available only to logged-in users.
          </div>
          <div class="modal-footer">
             
            @guest
                
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                @if (Route::has('register'))
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
 
            @endguest

          </div>
        </div>
      </div>
    </div>  
    @endguest
  

@endsection