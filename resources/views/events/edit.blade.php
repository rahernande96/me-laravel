@extends('layouts.app')

@section('head')
	<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
	
	
	
	<script src="{{ asset('global_assets/js/demo_pages/uploader_bootstrap.js')}}"></script>
	
	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/editors/summernote/summernote.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/editor_summernote.js') }}"></script>
	<!-- /theme JS files -->

@endsection

@section('content')

@if (session('status'))
    <div class="alert alert-success alert-styled-left alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        <span class="font-weight-semibold">{{ session('status') }}</span>
    </div>
@endif
@if ($errors->any())

        <div class="alert alert-warning alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">Atención!</span>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>


@endif

<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Crear Evento</h5>
			<div class="header-elements">
				<div class="list-icons">
            		
            	</div>
        	</div>
		</div>

		<div class="card-body">
			<form id="formulario" action="{{ route('events.update',$event->id) }}" method="POST" enctype="multipart/form-data">
				
				@csrf

				@method('PUT')
				
				<div class="form-row">
					
					<div class="form-group col-md-6">
				
						<label for="">Titulo</label>
					
						<input class="form-control" type="text" name="title" value="{{ $event->name}}" required>
					
					</div>

					<div class="form-group col-md-6">
				
						<label for="">Precio</label>
					
						<input class="form-control" type="text" name="price" value="{{ $event->price}}" required>
					
					</div>


				</div>

				
				
				<div class="form-row">

					<div class="form-group col-md-6">
						
						<label for="">Descripcion</label>
						<input name="description" type="text" class="form-control" required value="{{ $event->description}}">

					</div>

					<div class="form-group col-md-6">
						
						<label for="">Direccion</label>
						<input name="address" type="text" class="form-control" value="{{ $event->address}}" required>

					</div>

				</div>

				<div class="form-row">
					
					<div class="form-group col-md-6">
						
						<label for="">Fecha</label>
						<input class="form-control" type="date" name="date_start" required value="{{date('Y-m-d', strtotime($event->date_start))}}">

					</div>

					<div class="form-group col-md-6">
						
						<label for="">Hora</label>
						<input class="form-control" type="text" name="hour" required value="{{ $event->hour}}">

					</div>

				</div>

				<div class="form-row">
					
					<div class="form-group col-md-6">
						
					
						<label for="">Acerca del evento</label>
					
						<div id="aboutnote" class="summernote">{!! $event->about_event !!}</div>
					
						<input id="about" class="form-control" type="hidden" name="about" required>
					

					</div>

					<div class="form-group col-md-6">
						
				
						<label for="">Temario</label>
					
						<div id="temarynote" class="summernote">{!! $event->temary !!}</div>
					
						<input id="temary" class="form-control" type="hidden" name="temary" required>
					
				

					</div>
					
					<div class="form-group col-md-6">
						<label for="">País</label>
					
						<select id="country_id" class="form-control" name="contry_id">
							@forelse($countries as $country)
								<option value="{{$country->id}}">{{$country->name}}</option>
							@empty
							@endforelse
						</select>
					</div>

				</div>


				<div class="form-group">
					
					<label for="">Imagen</label>
				
					<input class="file-input" type="file" data-show-caption="false" data-show-upload="false" data-show-remove="true" name="img">
				
				</div>

				<button type="submit" class="btn btn-success">Guardar</button>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$('#formulario').submit(function(){
		var markupStr = $('#aboutnote').summernote('code');
		$('#about').val(markupStr);

		var markupStr2 = $('#temarynote').summernote('code');
		$('#temary').val(markupStr2);
			
	});

	$('#country_id').val('{{ $event->country_id }}');
	
    $('#events').addClass('active');

</script>
@endsection