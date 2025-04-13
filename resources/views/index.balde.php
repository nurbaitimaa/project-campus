<!DOCTYPE html>
<html>
<head>
	<title>Tutorial Membuat CRUD Pada Laravel - www.malasngoding.com</title>
</head>
<body>
 
	<h2>Mazzoni Marketing</h2>
	<h3>Data Program</h3>
 
	<a href="/program/tambah"> + Tambah Program</a>
	
	<br/>
	<br/>
 
	<table border="1">
		<tr>
			<th>id_program</th>
			<th>nama_program</th>

		</tr>
		@foreach($program as $p)
		<tr>
			<td>{{ $p-id_program }}</td>
			<td>{{ $p->pegawai_jabatan }}</td>

			<td>
				<a href="/pegawai/edit/{{ $p->pegawai_id }}">Edit</a>
				|
				<a href="/pegawai/hapus/{{ $p->pegawai_id }}">Hapus</a>
			</td>
		</tr>
		@endforeach
	</table>
 
 
</body>
</html>