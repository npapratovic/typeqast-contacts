@extends('contacts.layout')

@section('content')

    
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    {!! Form::model($contact, ['method' => 'PATCH', 'files' => true, 'action' => ['ContactController@update', $contact->id]]) !!}
    {!! Form::hidden('contact_id', $contact->id) !!}
    @csrf
    
    <div class="row">
 
        <div class="col-md-2 offset-md-2 pt-5">
            @if ($contact->image)
                <a href="{{ asset('/img/'.$contact->image) }}" target="_blank"><img class="img-fluid" src="{{ asset('/img/thumb/'.$contact->image) }}"></a>
            @endif 
            {!! Form::hidden('image', old('image')) !!}
            {!! Form::file('image', ['class' => 'form-control', 'style' => 'margin-top: 4px;']) !!}
            {!! Form::hidden('image_max_size', 8) !!}
            {!! Form::hidden('image_max_width', 4000) !!}
            {!! Form::hidden('image_max_height', 4000) !!}
            
            @if($errors->has('image'))
                <p class="help-block">
                    {{ $errors->first('image') }}
                </p>
            @endif
        </div>  
 
        <div class="col-md-6 pt-4">

            <div class="row">
                <div class="col-md-6" style="margin-top:10px;margin-bottom: 10px;">
                    <a href="{{ route('contacts.index') }}" class="color-tq"> <i class="fas fa-long-arrow-alt-left"></i> Back</a>
                </div>
                <div class="col-md-6 text-right" style="margin-top:10px;margin-bottom: 10px;">
                    
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-secondary contact-delete-icon" data-toggle="modal" data-target="#exampleModal{{ $contact->id }}">
                      <i class="fas fa-trash-alt">  <span class="color-tq">Delete contact</span> </i>
                    </button>


                </div>
            </div> 

            <hr class="tq">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h5><i class="far fa-user"></i>&nbsp;  First name:</h5>
                        {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'Enter contact first name', 'required'=>'required']) !!}
                     </div>
                </div>
            </div>


            <hr class="tq">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h5><i class="fas fa-user"></i>&nbsp;  Last name:</h5>
                        {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Enter contact last name', 'required'=>'required']) !!}
                     </div>
                </div>
            </div>
 

            <hr class="tq">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h5><i class="far fa-envelope"></i>&nbsp;  Email:</h5>
                        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Enter contact email', 'required'=>'required']) !!}
                     </div>
                </div>
            </div>


            <div class="row" style="margin-top: 15px; margin-bottom: 10px;">
                <div class="col-md-6">
                    <a class="cancel-button float-left" href="{{ URL::to('/') }}">Cancel </a>
                </div>

                <div class="col-md-6">
                      {!! Form::submit(('Save contact data'), ['class' => 'save-button bg-tq float-right']) !!}
                </div>
            </div>

            {!! Form::close() !!}

            <hr class="tq">

            <div class="row">
                <div class="col-md-12">      
                    <div class="form-group">                  
                        <h5><i class="fas fa-phone"></i>&nbsp;  numbers:</h5>
                    </div>
                </div>
            </div>

           {!! Form::open(['method' => 'PATCH', 'action' => ['ContactController@updatePhoneNumbers', $contact->id]]) !!}
           
           {!! Form::hidden('contact_id', $contact->id) !!}

            <div class="append_box">
 
                @foreach($contact->contact_number as $person => $phone)
                    {!! Form::hidden('number['.$phone->id.'][contact_number_id]', $phone->id) !!}

                <div class="row">
 
                    <div class="col-md-5">
                        <div class="form-group"> 

                            {!! Form::text('number['.$phone->id.'][number_name]', $phone->number, ['class' => 'form-control']) !!}
                         </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group"> 
                            
                            {!! Form::text('number['.$phone->id.'][cell]', $phone->cell, ['class' => 'form-control']) !!}
                         
                        </div>
                    </div> 

                    <div class="col-md-2">
                        <div class="form-group">
                            <i class="far fa-trash-alt"></i> {!! Form::checkbox('delete_item[]', $phone->id) !!}
                        </div>
                    </div> 

                </div>

                @endforeach
  
            </div>

            <div class="row" style="padding-bottom: 150px;">
                <div class="col-md-6"> 
                    <a href="#" id="add-more" class="color-tq">
                        <i class="fas fa-plus"></i> &nbsp;Add number                        
                    </a>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::submit("Save numbers",['class'=>'save-button bg-tq float-right']) !!}
                    </div>
                </div>
            </div>
             

            {!! Form::close() !!}

        </div>
    </div>


    <!-- Modal -->
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
                  
            {!!Form::open(['action'=>['ContactController@destroy', $contact->id], 'method'=>'POST'])!!}
                {{Form::hidden('_method','DELETE')}}
                @csrf
                <button type="submit" class="btn btn-secondary contact-delete-icon">Delete contact</button> 
         
            {!!Form::close() !!}   

          </div>
        </div>
      </div>
    </div>

<?php

$largest_id = DB::table('contact_numbers')->orderBy('id', 'desc')->first();
$largest_id = $largest_id->id;
$count = $largest_id + 1;
?>

    <script type="text/javascript">

        $(document).ready(function () {

            // initialize a global count for items.
            var count = <?php echo $count; ?>;
            var contact_number_id = <?php echo $count; ?>;
            // listen the button for clicks
            $('#add-more').on('click', function(e) {

                // prevent the default action ( navigating to /# in URL )
                e.preventDefault();

                // get the jQuery Object
                var appendToHtmlBlock = $('.append_box');

                // build the HTML Block
                var appendHtml = '' +

                '<div class="row"> \n' +
                    '<div class="col-md-5"> \n' +
                        '<div class="form-group"> \n' +
                            '<input name="number['+ contact_number_id +'][contact_number_id]" type="hidden" id="number['+ contact_number_id +'][contact_number_id]"> \n' + 
                            '<input class="form-control" name="number['+ count +'][number_name]" type="text" id="number['+ count +'][number_name]" placeholder="Number"> \n' + 
                         '</div> \n' +
                    '</div> \n' +
                    '<div class="col-md-5"> \n' +
                        '<div class="form-group">  \n' +
                            '<input class="form-control" name="number['+ count +'][cell]" type="text" id="number['+ count +'][cell]" placeholder="Cell"> \n' + 
                         '</div> \n' +
                    '</div> \n' +
                '</div> \n';
  
                // append the html to jQuery object (DOM).
                appendToHtmlBlock.append(appendHtml);

                // increment the counter.
                count++;

            });

        });

    </script>



@endsection