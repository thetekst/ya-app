<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

</head>
<body>
	<input type="text" name="in" id="in">
	<a href="#refresh">refresh</a>
	<a href="#add">add</a>
	<a href="#publish">publish</a>
	<div class="data"></div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script>
	;(function($) {
		$(function() {
			var token = '';

			$.getJSON('secure.json', function(data) {
				token = data.token;
				refresh();
			});

			function create(val) {
				$.ajax({
					method: 'PUT',
					contentType: "application/json",
					headers: {
						"Authorization": "OAuth ",
						"Accept": "application/json"
					},
					url: "https://cloud-api.yandex.net/v1/disk/resources/?path=%2F"+val,
				}).done(function(data) {
					console.log(data);
					console.log('create');
					refresh();
				});
			}

			function rm(val) {
				$.ajax({
					method: 'DELETE',
					contentType: "application/json",
					headers: {
						"Authorization": "OAuth "+token,
						"Accept": "application/json"
					},
					url: "https://cloud-api.yandex.net/v1/disk/resources/?path=%2F"+val,
				}).done(function(data) {
					console.log(data);
					console.log('rm');
					refresh();
				});
			}

			function publish(val) {
				$.ajax({
					method: 'PUT',
					contentType: "application/json",
					headers: {
						"Authorization": "OAuth "+token,
						"Accept": "application/json"
					},
					url: "https://cloud-api.yandex.net/v1/disk/resources/publish?path=%2F"+val,
				}).done(function(data) {
					console.log(data);
					console.log('publish');
					refresh();
				});
			}

			function refresh(param) {

				switch(param) {
					case 'files':
						param = 'files';
						break;
					case 'last-uploaded':
						param = 'last-uploaded';
						break;
					default:
						param = 'public';
				}
				
				$.ajax({
					method: "GET",
					contentType: "application/json",
					headers: {
						"Authorization": "OAuth "+token,
						"Accept": "application/json"
					},
					url: "https://cloud-api.yandex.net/v1/disk/resources/"+param,
				}).done(function(data) {
					// console.log(data);
					if (data.items) {
						console.log('refresh');
						$('.data').empty();
						$.each(data.items, function(key, item) {
							$('.data').append('<li><span>'+item.name+'</span> <a href="#rm">[x]</a></li>');
						});
					};
				});
			}
			
			$('[href=#add]').click(function(e) {
				var val = $('[name=in]').val();

				if ('' !== val) {
					create(val);
				};
			});

			$('[href=#publish]').click(function(e) {
				var val = $('[name=in]').val();

				if ('' !== val) {
					publish(val);
				};
			});

			$('body').on('click', '[href=#rm]', function() {
				var val = $(this).parent().find('span').text();

				if ('' !== val) {
					rm(val);
				};
			});

			$('[href=#refresh]').click(function(e) {
				refresh();
			});
		});
	})(jQuery)
	</script>
</body>
</html>


<!--
curl:

-d '{"path":"%2Ftest"}' \


# https://tech.yandex.ru/disk/poligon/#!//v1/disk/resources/CreateResource
# Создать папку 'empty'

curl -XPUT -H 'Content-type: application/json' \
-H 'Accept: application/json' \
-H 'Authorization: OAuth a342a9xxxxxxa7ab66990d66b9b1' \
https://cloud-api.yandex.net/v1/disk/resources/?path=%2Fempty

# Удалить папку или файл

curl -XDELETE -H 'Content-type: application/json' \
-H 'Accept: application/json' \
-H 'Authorization: OAuth a342a9xxxxxxa7ab66990d66b9b1' \
https://cloud-api.yandex.net/v1/disk/resources/?path=%2Fempty

# Удалить папку или файл

curl -XGET -H 'Content-type: application/json' \
-H 'Accept: application/json' \
-H 'Authorization: OAuth a342a9xxxxxxa7ab66990d66b9b1' \
https://cloud-api.yandex.net/v1/disk/resources/public
-->