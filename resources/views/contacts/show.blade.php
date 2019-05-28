@extends('contacts.layout')

@section('content')
  
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
 
    
    <div class="row">
 
        <div class="col-md-2 offset-md-2 pt-5">

            @if ($contact->image)
                <img class="img-fluid rounded-circle" src="{{ asset('/img/thumb/'.$contact->image) }}"> 
            @endif 
              
        </div>  
 
        <div class="col-md-6 pt-4">

            <div class="row">
                <div class="col-md-6" style="margin-top:50px;margin-bottom: 10px;">
                    <a href="{{ route('contacts.index') }}" class="color-tq"> <i class="fas fa-long-arrow-alt-left"></i> 
                        </a>  <span style="font-size: 24px;">{{ $contact->first_name }} {{ $contact->last_name }}</span>
                </div>
                <div class="col-md-6 text-right" style="margin-top:60px;margin-bottom: 0px;">
   
                    @if ($check_fav != '0')

                        @auth
                        {{-- contact is favorite, so lets show option to delete from favorite --}}

                        <!-- Button trigger delete from favourite modal -->
                        <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#del_favorite_{{ $contact->id }}" style="display: inline; margin: 0px; padding: 0px 20px;">
                           <i class="fas fa-heart"></i>
                        </button>

                        @endauth 

                    @else

                        @auth
                        {{-- contact is not favorite, so lets show option to add to favorite --}}

                        <!-- Button trigger add to favourite modal -->
                        <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#add_favorite_{{ $contact->id }}" style="display: inline; margin: 0px; padding: 0px 20px;">
                          <i class="far fa-heart"></i>
                        </button>

                        @endauth 

                        @guest

                        <!-- Button trigger to log in modal -->
                        <button type="button" class="btn btn-secondary contact-favorite-icon" data-toggle="modal" data-target="#log_in" style="display: inline; margin: 0px; padding: 0px 20px;">
                          <i class="far fa-heart"></i>
                        </button>

                        @endguest


                    @endif



                    
                    <a href="{{ route('contacts.edit',$contact->id) }}" class="contact-edit-icon" style="display: inline;"><i class="fas fa-pencil-alt"></i></a>


                </div>
            </div> 

            <hr class="tq">
  
 
 
            <div class="row" style="padding-top: 50px; padding-bottom: 50px;">
                <div class="col-md-4">
                    <div class="form-group">
                        <h5><i class="far fa-envelope"></i>&nbsp;  Email:</h5>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                     <h5><span style="color: rgb(201, 208, 207);">{{ $contact->email }} </span></h5>
                    </div>
                </div>
            </div>

 
  

            <div class="row">
                <div class="col-md-4">      
                    <div class="form-group">                  
                        <h5><i class="fas fa-phone"></i>&nbsp;  numbers:</h5>
                    </div>
                </div>
                <div class="col-md-8">
    
                    @foreach($contact->contact_number as $person => $phone) 

                        <div class="row">
         
                            <div class="col-md-3">
                                <div class="form-group"> 
                                    <span style="text-transform: uppercase; color: rgb(201, 208, 207);">{{ $phone->number }}</span>
                                 </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group"> 
                                    <a href="tel:{{ $phone->cell }}" style="color: rgb(201, 208, 207); text-decoration: underline;">{{ $phone->cell }}</a>
                                 </div>
                            </div> 

                        </div>

                    @endforeach
                </div>  
            </div>
  
        </div>
    </div>



    @auth

    <!-- delete from favourite Modal -->
    <div class="modal fade" id="del_favorite_{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                
                <input type="hidden" id="contact_id" name="contact_id" value="{{ $contact->id }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">

                <button type="submit" class="btn btn-secondary contact-delete-icon">Remove from my favorite</button> 
                

            {!!Form::close() !!}   

          </div>
        </div>
      </div>
    </div>  

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
                

            {!!Form::close() !!}   

          </div>
        </div>
      </div>
    </div>  
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