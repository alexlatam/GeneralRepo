@extends('cms.layout.main')
@section('title')
	Banners
@endsection


@section('content')
	@if(session('error'))
	    <div class="alert alert-danger my-4" role="alert">
	      {{session('error')}}
	    </div>
	@endif
	@if(session('message'))
	    <div class="alert alert-success my-4" role="alert">
	      {{session('message')}}
	    </div>
	@endif
	<section class="my-4">
		<h2>Logo Navbar</h2>
		<div class="row">
			<div class="col-3">
				@if(isset($logo))
				<img id="logo_image" src="{{asset('storage/'. $logo->image)}}" style="width: 100%; height: 100%; object-fit: cover;">
				@else
					<img id="logo_image" src="" style="width: 100%; height: 100%; object-fit: cover;">
				@endif
			</div>
			<div class="col-9 d-flex justify-content-between">
				<div>
					<form action="{{route('banners.logo')}}" method="POST" id="form_logo"  enctype="multipart/form-data">
						@csrf
						<input type="file" name="image" class="image_file">
					</form>
				</div>
				<div>
					<button type="button" id="guardar_submit" class="btn btn-success">Guardar</button>
				</div>
			</div>
		</div>
	</section>
	<hr>
	<section>
		<div class="my-3 d-flex justify-content-between">
			<h2>Imagenes del Banner principal</h2>
			<div>
				<a class="btn btn-outline-success" href="{{route('banners.create')}}">Nuevo</a>
			</div>
		</div>

		<div class="table-responsive">
		  <table class="table table-striped table-sm">
		    <thead>
		      <tr>
		        <th>#</th>
		        <th>Imagen</th>
		        <th>Titulo</th>
		        <th>Descripción</th>
		        <th>Acciones</th>
		      </tr>
		    </thead>
		    <tbody>
		      @foreach($banners as $banner)
		      <tr>
		        <td>{{$banner->id}}</td>
		        <td>
		        	<img src="{{asset('storage/'. $banner->image)}}" width="50">
		        </td>
		        <td>{{$banner->title}}</td>
		        <td>{{$banner->description}}</td>
		        <td class="d-flex">
		          <a href="{{route('banners.show', $banner->id)}}" class="btn btn-outline-success mr-2">editar</a>
		          @if($banner->status == 1)
		          	<a href="{{route('banners.desactive', $banner->id)}}" class="btn btn-outline-success mr-2">Ocultar</a>
		          @elseif($banner->status == 0)
		          	<a href="{{route('banners.active', $banner->id)}}" class="btn btn-outline-success mr-2">Activar</a>
		          @endif
		          <button type="button" id="{{$banner->id}}" class="btn btn-outline-danger eliminar" data-toggle="modal" data-target="#modalEliminar">Eliminar</button>
		        </td>
		      </tr>
		      @endforeach
		    </tbody>
		  </table>
		</div>
	</section>

	<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Eliminar Banner</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form action="" id="eliminar_form" method="POST">
	        	@csrf
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" id="eliminar_submit" class="btn btn-danger">Eliminar Banner</button>
	      </div>
	    </div>
	  </div>
	</div>


	<script type="text/javascript">
		let eliminarButtons = document.querySelectorAll('.eliminar');
		let deleteSubmit = document.getElementById('eliminar_submit');
		let inputFile = document.querySelectorAll('.image_file');
		let image = document.getElementById('logo_image');

		let guardarSubmit = document.getElementById('guardar_submit'),
			formImage = document.getElementById('form_logo');


		guardarSubmit.addEventListener('click', () => {
			formImage.submit();
		});


		//borrar banner
		deleteSubmit.addEventListener('click', (e) => {
			let form = document.getElementById('eliminar_form');

			form.submit();
		});


		//cargar logo
		inputFile.forEach(input => {
		  input.onchange = function (e){
		    
		    let reader = new FileReader();
		    reader.readAsDataURL(e.target.files[0]);

		    reader.onload = function (){
		      image.src = reader.result;
		    }

		  }
		});


		//modal eliminar
		if(eliminarButtons)
		{
			eliminarButtons.forEach(buttons => {
				buttons.addEventListener('click', (e) => {
					let id = e.target.id
					modalEliminar(id)
				});
			});
		}


		function modalEliminar(id){
			let formEliminar = document.getElementById('eliminar_form');
			formEliminar.action = `/cms/eliminar/banner/${id}`;
		}
	</script>
@endsection
