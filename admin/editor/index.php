<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta
			name="viewport"
			content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
		/>
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>Jodit Test Document</title>
		<link rel="stylesheet" href="./app.css" />
		<link rel="stylesheet" href="./build/jodit.min.css" />
		<script src="./build/jodit.js"></script>
		<link rel="icon" href="https://xdsoft.net/jodit/pro/favicon.png"/>
	</head>
	<body>
		<style>
			#box {
				padding: 100px;
				margin: 20px;
				position: relative;
				height: 500px;
			}

			@media (max-width: 480px) {
				#box {
					padding: 0;
				}
			}
		</style>
		<div id="box">
			<h1>Jodit Test Document</h1>
			<textarea id="editor">
				&lt;img src="https://xdsoft.net/jodit/build/images/artio.jpg"/&gt;
			</textarea>
		</div>
		<script>
			const editor = Jodit.make('#editor' ,{
				uploader: {
					url: 'https://xdsoft.net/jodit/connector/index.php?action=fileUpload'
				},
				filebrowser: {
					ajax: {
						url: 'https://xdsoft.net/jodit/connector/index.php'
					}
				}
			});
		</script>
	</body>
</html>
