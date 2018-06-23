<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016090900468384",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAzV/bA+q4OFY50/tPeE2/JOaGM8Wkg343Br+m6Gzur0mf0z3Zt5putPHY+gZ5v4T3Mrc9ob41rt9q8ywYxhDgqxTCW5dumw1l39ZhfZ89sAk4UM4MFTzu7p3UhrpDVIZTKmuhqm7EFdp+VxslqOZmGaM1Yo0OK5vu6hsbEzf7UO39vDb73mpFEdU0RFSSx3pqtzYvFYCwYsYdP1+dFicaS6mtzuAoB2LTtdCj04YfDSG5OJFrS1yzIXQC4Ce670p4oYklrQAcU5xtH5cjFNRFghxD0B8pKVTPN5XmShANdm0fg9vJLhAvaw4+hTD3yag0fW0f+1ElMwsRoykCIbaeXwIDAQABAoIBAAdwf3iG2iGAThv1sI1FE7V8fEQH1svEmK2v55XnzEWhPx7h0K8r8vKvCBMWhPaBFItLw/nF8B/ji/Y/FK6oEdtgpyG2PJV1SMlw9JseV5e23clfjWR/jpAqO3ad7K51JzIUMAkCdivsfEMfgp+5qN0o4lgtj+PYhDEKxlJN5tapl68A4fiVZjDrufkMQdFNBbQEvd+I2VPg4J6Hktgy++z9y02n5q+P8Jcx4Exv7I/+BOF+YvnyCJZiGblji4vreCTKqdiW1LEBKa+p/7wxaPWUy9l4q8k3gjVFyk6PqfnDTawyIUVwW5gkTuP5RMkalevzVpVg4F5oy0seZI3mwLECgYEA9VI9x2njtFEbSm3dbCIFnxe24PGbviqsFB1sSNOhMO2ZFKBSjy89gp6Fx+9Q/Ethm995iqebmqROVct9P+TWRJxP9GR8o56dpw7UNiBk3hfdMloGyElOeDDIdbJhRQ+A9Xm+8n1+dO5v70TGOzAdFN2y4JgZp4weN3Pk6EsEwVMCgYEA1lB2np9L0dnAt+8+Hu4L3yMCA0Xka/5+6lsVCoZ6QIArvbB42xzggGp9mJGVAEI6Fu4zN4TN+nc/+eoKyL1Zq0H0im96D0MjJbvSTY3eWYFZcPlhyUV3Mn5/2BN69tU1HxgnCFcbNt/WRbtU30hNw0JsHhjeckxvJhwa8XoIEUUCgYA38Gjl799cXqI6dpLm9VsSy/WHlZBN7K0QaSUn9hnrbkJZ0bUBsWP1RPB8hrWQ0h+Py4WLeGyi9pRmO+BzkVrKiloxADjrll+cmGULcpeNjSODN8QUJ6MdBQKw09waQ07Eqt4/rR4Iy9X6WVRaar4249bGH+i5q9m4C1VHItQSwQKBgQC9nkmlhOmvjUtPMpUzBolobu/7913oEy81qUrPmjYWVZDd74Ku0zjrRc1ELSK5LN96pFyadZ1NSy9GrkXGYIoy1VNJHOLRGCTD4Q9sYOZnKOP66hsT2qtg+L2ib3HvbKrojRUT8wX4IknSPrIUMEPlTCKe6n/+fxQkkt9f4gm7RQKBgDbvUfQ+UYNTvEq8EAwzs4qbLnenZHAit+UyfOUYfLMIEGWuXrMVWZG/U/GW+dTyXAfO18Le9G4iYdDmyOGku3TTR5Cn7AJNBHL1Fp8tZY1YwMJ7Nh8jruYmjHTBKdbOqWM10Jvjm5TxGWxMbPTBxvJlTVYmHezbblH+6Xdf5D0G",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuAOpKiizg/WmN15gYAsFoalJ2i4855gwj0TPI7kVZEFFBbrDwZ61ct1kmASp9FHzCbxy6ovS4lTRG/D6Zc+B56Y1adufxe/UUAkVHEg0fPo90aod8lE81XS79rNpYmZ2Hw2qjOrTYoXzpofQJrRPyz9K/AhMWgKkNORXJWaxhUG3bRsFFPICwC3v0PyMx3LLJ+yDOgZJnR9xqboklJ6Gl+tTFl9vUmAXk9Y8ECX/ja+vCyxPI5uVNOhsBIT/LbfNpI6DS6M7d/w5FgJ4kzBG7BR3/ff5j2/Tqz3dKRGxMnq++3likWbEGUbpdofHQOMiI+xYz5rfj2RXHKCoX2ki6QIDAQAB",
);