<!DOCTYPE html>
<html>
<head>
	<title>Export PDF</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
 
	<div class='container'>
		<table class='table table-bordered text-sm'>
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Email</th>
					<th>Biodata</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($users as $p)
				<tr>
					<td>{{$p->id}}</td>
					<td>{{$p->name}}</td>
					<td>{{$p->email}}</td>
					<td>{{$p->biodata}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
 
	</div>
 
</body>
</html>