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


    {!! Form::open(['method' => 'POST', 'route' => ['contacts.store'], 'files' => true,]) !!}
  
    @csrf
    
    <div class="row">
        <div class="col-md-2 offset-md-2 pt-5">
  
            {!! Form::hidden('image', old('image')) !!}
            {!! Form::file('image', ['class' => 'form-control', 'style' => 'margin-top: 4px;', 'required'=>'required']) !!}
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
                    <span class="color-tq">Add new contact</span>
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
                        <h5><i class="fas fa-user"></i>&nbsp;  LAst name:</h5>
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


            <hr class="tq">

            <div class="row">
                <div class="col-md-12">      
                    <div class="form-group">                  
                        <h5><i class="fas fa-phone"></i>&nbsp;  numbers:</h5>
                    </div>
                </div>
            </div>

            <div class="append_box">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::text('number[0][number_name]', old('number[0][number_name]'), ['class' => 'form-control', 'placeholder' => 'Label', 'required'=>'required']) !!}
                         </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"> 
                            {!! Form::text('number[0][cell]', old('number[0][cell]'), ['class' => 'form-control', 'placeholder' => 'Number', 'required'=>'required']) !!}
                         </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"> 
                    <a href="#" id="add-more" class="color-tq">
                        <i class="fas fa-plus"></i> &nbsp;Add number                        
                    </a>
                </div>
            </div>

            <div class="row" style="margin-top: 150px; margin-bottom: 100px;">
                <div class="col-md-6">
                    <a class="cancel-button float-left" href="{{ URL::to('/') }}">Back </a>
                </div>

                <div class="col-md-6">
                      {!! Form::submit(('Save'), ['class' => 'save-button bg-tq float-right']) !!}
                </div>
            </div>
        </div>
    </div>

 

  
    {!! Form::close() !!}



    <script type="text/javascript">

        $(document).ready(function () {

            // initialize a global count for items.
            var count = 1;

            // listen the button for clicks
            $('#add-more').on('click', function(e) {

                // prevent the default action ( navigating to /# in URL )
                e.preventDefault();

                // get the jQuery Object
                var appendToHtmlBlock = $('.append_box');

                // build the HTML Block
                var appendHtml = '' +

                '<div class="row"> \n' +
                    '<div class="col-md-6"> \n' +
                        '<div class="form-group"> \n' +
                            '<input class="form-control" name="number['+ count +'][number_name]" type="text" id="number['+ count +'][number_name]" placeholder="Label"> \n' + 
                         '</div> \n' +
                    '</div> \n' +
                    '<div class="col-md-6"> \n' +
                        '<div class="form-group">  \n' +
                            '<input class="form-control" name="number['+ count +'][cell]" type="text" id="number['+ count +'][cell]" placeholder="Number"> \n' + 
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