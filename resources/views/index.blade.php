<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>File Management</title>
	<link rel="stylesheet" href="{{ asset('/css/app.css') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
	<div id="app" class="wrapper">
		<div class="container" style="padding-top: 50px;">
			<file-management :settings="{{ json_encode($props) }}"></file-management>
		</div>
	</div>
</body>
<script src="{{ asset('/js/app.js') }}"></script>
</html>