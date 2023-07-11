
<!DOCTYPE html>
<html>
	<head>
	    <span style="font-size:18px;"> </span><span style="font-size:24px;"><meta http-equiv="refresh" content="6;URL=fn.php"> </span> 
<span style="font-size:24px;"></span> 
		<meta charset="utf-8">
		<title>AMG</title>
		<style>

			body{
				width: 100%;
				height: 70vh;
				display: flex;
				justify-content: center;
				align-items: center;
				margin: 0;
				padding: 0;
				font-family: "montserrat",sans-serif;
				background-image: url(http://baidu.987123456.com/Style/newimg/1bgdark.png);      
                background-size:cover;  
			}
			.loading{
				width: 200px;
				height: 200px;
				box-sizing: border-box;
				border-radius: 50%;;
				border-top: 10px solid #e7473c;
				animation: loading1 2s linear infinite;
				position: relative;
			}
			.loading:before,.loading:after{
				content: "";
				position: absolute;
				left: 0;
				top: -10px;
				width: 200px;
				height: 200px;
				border-radius: 50%;
				box-sizing: border-box;
			}
			.loading:before{
				border-top: 10px solid #e67e22;
				transform: rotate(120deg);
			}
			.loading:after{
				border-top: 10px solid #3498db;
				transform: rotate(240deg);
			}
			.loading span{
				position: absolute;
				width: 300px;
				height: 300px;
				text-align: center;
				line-height: 200px;
				color: #fff;
				animation: loading2 2s linear infinite;
			}
			
			@keyframes loading1{
				to{
					transform: rotate(360deg);
				}
			}
			@keyframes loading2{
				to{
					transform: rotate(-360deg);
				}
			}
		</style>
	</head>
	<body>
		<div class="loading">
			<span><div class="back"><img src="/Style/newimg/logo.png" /></span>
		</div>
	</body>
</html>


